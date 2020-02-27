<?php

namespace TMD\ProdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use XLSXWriter;

class PndController extends Controller
{

    public function saisiePndAction(Request $request)
    {
        $numCmd = null;
        if ($request->getMethod() === 'POST') {
            $numCmd = $request->request->get('numCmd');
            if (!empty($numCmd)) {
                $em = $this->getDoctrine()->getManager();
                $idStatutPnd = $em->getRepository('TMDProdBundle:EcommStatut')->find(7); // statut PND

                // marquer la commande (tracking) en statut PND
                $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(['expRef' => $numCmd]);
                $tracking->setIdStatut($idStatutPnd);

                // historiser le statut
                $user = $this->getUser();
                $jouristo = new EcommHistoStatut();
                $jouristo->setDatestatut(new \DateTime());
                $jouristo->setIdstatut($idStatutPnd->getIdStatut());
                $jouristo->setObservation("Saisie PND");
                $jouristo->setNumbl($numCmd);
                $jouristo->setIduser($user->getId());
                $em->persist($jouristo);

                $em->flush();
            }
        }

        return $this->render('TMDProdBundle:Pnd:saisiePnd.html.twig', array(
            'numCmd' => $numCmd,
        ));
    }


    /**
     * affiche la page de téléchargement des PND
     * @param Request $request
     * @param $idClient
     * @param $idOpe
     * @return Response
     */
    public function extractAction(Request $request, $idClient, $idOpe)
    {
        $message = $request->query->get('message');

        $em = $this->getDoctrine()->getManager();
        $operationCourante = $em->getRepository('TMDProdBundle:EcommAppli')->findBy(array('idappli' => $idOpe));
        foreach ( $operationCourante as $u) {
            if ($u->getAppliImage() != null) {
                $operationCourante['appliImage'] = (base64_encode(stream_get_contents($operationCourante[0]->getAppliImage())));
            }
        }

        return $this->render('TMDProdBundle:Pnd:pnd.html.twig', [
            'idClient'          => $idClient,
            'idOpe'             => $idOpe,
            'idOperation'       => $idOpe,
            'operation'         => $operationCourante,
            'message'           => $message,
        ]);
    }


    /**
     * Télécharge la liste des PND d'une opération au format XLS
     * mémorise les trakcing téléchargés en session
     * @param $idClient
     * @param $idOpe
     * @return Response
     */
    public function downloadAction(Request $request, $idClient, $idOpe) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $sql = '
            SELECT T.numLigne, A.AppliName, T.`num_cmde_client`, T.`refClient`, T.exp_ref, T.`date_cmde`, C.codeArticle, C.libelle
            FROM `ecomm_tracking` T
                , ecomm_lignes L
                , ecomm_cmdep C
                , ecomm_appli A
                , ecomm_files F
            WHERE  A.idAppli = :idOpe
                AND T.idStatut = :idStatut
                AND T.numLigne = L.numLigne
                AND C.numBL = L.numBL
                AND F.idAppli = A.idAppli
                AND T.idFile = F.idFile
            GROUP BY C.numero
        ';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('idOpe', $idOpe);
        $statement->bindValue('idStatut', 7); // PND

        $statement->execute();
        $data = $statement->fetchAll();

        $content = [];
        $numLignes = [];
        foreach ($data as $row) {
            $numLignes [] = $row ['numLigne'];
            $content [] = [
                'AppliName' => $row ['AppliName'],
                'num_cmde_client' => $row ['num_cmde_client'],
                'refClient' => $row ['refClient'],
                'exp_ref' => $row ['exp_ref'],
                'date_cmde' => $row ['date_cmde'],
                'codeArticle' => $row ['codeArticle'],
                'libelle' => $row ['libelle'],
            ];
        }
        $numLignes = array_unique($numLignes);

        // save into session
        $session->set('pnd_download_numlignes', $numLignes);
        $session->save();

        $header = [
            'AppliName' => 'string',
            'num_cmde_client' => 'number',
            'refClient' => 'string',
            'exp_ref' => 'string',
            'date_cmde' => 'string',
            'codeArticle' => 'string',
            'libelle' => 'string',
        ];
        $writer = new XLSXWriter();
        $writer->writeSheet($content,'extraction', $header);

        $nomExport = "PND_".$idOpe."_".date("Y-m-d_H-i-s");
        return new Response(
            $writer->writeToString(), // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    /**
     * marque les PND téléchargés (mémorisés en session) comme traités
     * @param $idClient
     * @param $idOpe
     * @return Response
     */
    public function saveAction(Request $request, $idClient, $idOpe) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $numLignes = $session->get('pnd_download_numlignes');
        if ( !isset($numLignes) ) { // nothing in session
            $message = 'aucun téléchargement effectué récement.';
        }
        elseif ( count($numLignes) === 0 ) { // session var empty
            $session->remove('pnd_download_numlignes');
            $message = 'le téléchargement était vide.';
        }
        else { // download had content
            // find all EcommTracking corresponding to $numLignes at one
            $trackings = $em->getRepository('TMDProdBundle:EcommTracking')->findAllNumlignes($numLignes);

            // mark as treated
            $sm = $this->get('tmd_statut');
            foreach ($trackings as $tracking) {
                $sm->changeStatut($tracking, "PND_TRAITE", "PND téléchargé", $this->getUser());
            }

            // clear session var
            $session->remove('pnd_download_numlignes');

            $message = 'téléchargement mémorisé. les ' . count($trackings) . ' PND sont maintenant marqués comme traités.';
        }

        return $this->redirect($this->generateUrl('tmd_prod_pnd', [
            'idClient' => $idClient,
            'idOpe' => $idOpe,
        ]).'?message='.$message);
    }

}
