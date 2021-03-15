<?php

namespace TMD\ProdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use XLSXWriter;

class PndController extends Controller
{

    const STATUTS_PND_ABREGES = ["PND_A", "PND_B", "PND_C", "PND_D", "PND_E"]; // liste des abrégés de status PND


    /**
     * écran de saisie des PND d'une opération
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function saisiePndAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $statuts_pndTemp = [];
        // récupération des de statuts PND
        foreach (self::STATUTS_PND_ABREGES as $statut_pnd_abrege) {
            $statuts_pndTemp[] =  $em->getRepository('TMDProdBundle:EcommStatut')->getByAbrege($statut_pnd_abrege);
        }
        $statuts_pnd[0] =$statuts_pndTemp[3];
        $statuts_pnd[1] =$statuts_pndTemp[0];
        $statuts_pnd[2] =$statuts_pndTemp[1];
        $statuts_pnd[3] =$statuts_pndTemp[2];
        $statuts_pnd[4] =$statuts_pndTemp[4];



        if ($request->getMethod() === 'POST') {
            $numCmd = $request->request->get('numCmd');
            $abregeStatut = $request->request->get('abregeStatut');
            if (!empty($numCmd) && !empty($abregeStatut)) {
                // marquer la commande (tracking) en statut PND (avec historisation)
                $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(['expRef' => $numCmd]);
                if ( $tracking !== null ) {
                    $sm = $this->get('tmd_statut');
                    $sm->changeStatut($tracking, $abregeStatut, "Saisie PND", $this->getUser());

                    return $this->render('TMDProdBundle:Pnd:saisiePnd.html.twig', array(
                        'numCmd' => $numCmd,
                        'statuts_pnd' => $statuts_pnd,
                    ));
                }
            }
        }

        return $this->render('TMDProdBundle:Pnd:saisiePnd.html.twig', array(
            'numCmd' => null,
            'statuts_pnd' => $statuts_pnd,
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
     * @throws \Exception
     */
    public function downloadAction(Request $request, $idClient, $idOpe) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $pnds = [];
        foreach (self::STATUTS_PND_ABREGES as $pnd) {
            $pnds [] = "'" . $pnd . "'";
        }

        $sql = '
            SELECT T.numLigne, A.AppliName, T.`num_cmde_client`, T.`refClient`, T.exp_ref, T.`date_cmde`, C.codeArticle, C.libelle, C.quantite, S.statut
            FROM `ecomm_tracking` T
                , ecomm_lignes L
                , ecomm_cmdep C
                , ecomm_appli A
                , ecomm_files F
                , ecomm_statut S
            WHERE  A.idAppli = :idOpe
                AND S.abregeStatut IN ('.implode(", ", $pnds).')
                AND T.numLigne = L.numLigne
                AND C.numBL = L.numBL
                AND F.idAppli = A.idAppli
                AND T.idFile = F.idFile
                AND T.idStatut = S.idStatut
            GROUP BY C.numero
        ';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('idOpe', $idOpe);

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
                'quantite' => $row ['quantite'],
                'statut' => $row ['statut'],
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
            'quantite' => 'string',
            'statut' => 'string',
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
