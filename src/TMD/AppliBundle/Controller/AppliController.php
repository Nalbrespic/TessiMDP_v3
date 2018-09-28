<?php

namespace TMD\AppliBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\AppliBundle\Entity\NTracking;
use TMD\AppliBundle\Form\NTrackingType;
use TMD\ProdBundle\Entity\EcommHistoStatut;

class AppliController extends Controller
{
    public function carteAction(Request $request, $idClient)
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em
            ->getRepository('TMDProdBundle:EcommAppli')
            ->findClientWithOperation();




        if ($idClient != 0) {


            $articlesByPlage = new NTracking();
            $form = null;
            $form = $this->get('form.factory')->create(NTrackingType::class, $articlesByPlage);

//            $lotCartes = $em->getRepository('TMDAppliBundle:NTracking')->findBy(array('idclient' => $idClient));

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                $plageDebut = $form->getData()->getPlagedebut();
                $plageFin = $form->getData()->getPlagefin();
                $refCont = $form->getData()->getBox();


                $retourIfexistCont = $em->getRepository('TMDAppliBundle:NTracking')->findBy(array('box' => $refCont, 'idclient' => $idClient));
                if (sizeof($retourIfexistCont) > 0) {
                    $request->getSession()->getFlashBag()->add('ErrorRecepCartesContenant', $retourIfexistCont);

                    return $this->redirectToRoute('tmd_appli_cartepage', array(
                        'idClient' => $idClient
                    ));
                }


                $retourIfexistPlage = $em->getRepository('TMDAppliBundle:NTracking')->findCorrespondancePlage($plageDebut, $idClient);
                $retourIfexistPlage2 = $em->getRepository('TMDAppliBundle:NTracking')->findCorrespondancePlage($plageFin, $idClient);
                if (sizeof($retourIfexistPlage) > 0 or sizeof($retourIfexistPlage2) > 0) {
                    $request->getSession()->getFlashBag()->add('ErrorRecepCartest', "pb d'enregistrement");
                    return $this->redirectToRoute('tmd_appli_cartepage', array(
                        'idClient' => $idClient
                    ));
                }

                $client = $em->getRepository('TMDProdBundle:Client')->findOneBy(array('idclient' => $idClient));

                $nbCartes = intval($plageFin) - intval($plageDebut) + 1;





                $articlesByPlage->setCurrent($plageDebut);
                $articlesByPlage->setIdclient($client);
                $idclientb = $client->getIdclient();

                $tabSynthese = array();
                $tabSynthese['refCont'] = $refCont;
                $tabSynthese['plageDebut'] = $plageDebut;
                $tabSynthese['plageFin'] = $plageFin;
                $tabSynthese['nbCartes'] = $nbCartes;


                $em->persist($articlesByPlage);
                $em->flush();

//                $lotCartes = $em->getRepository('TMDAppliBundle:NTracking')->findBy(array('idclient' => $idclientb));;
                $request->getSession()->getFlashBag()->add('ConfirmRecepCartesbyPlages', $tabSynthese);
                $form = null;
                $form = $this->get('form.factory')->create(NTrackingType::class, $articlesByPlage);

                return $this->render('TMDAppliBundle:Appli:saisiCartes.html.twig', array(
                    'form' => $form->createView(),
//                    'clientForListe' => $lotCartes,
                    'clients' => $clients,
                    'idClient' => $idClient
                ));
            }


            return $this->render('TMDAppliBundle:Appli:saisiCartes.html.twig', array(
                'form' => $form->createView(),
//                'lotsCarte' => $lotCartes,
                'clients' => $clients,
                'idClient' => $idClient
            ));
        }

        return $this->render('TMDAppliBundle:Appli:saisiCartes.html.twig', array(
            'clients' => $clients,
            'idClient' => $idClient
        ));
    }
    public function deleteAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $id = $request->get('carteLot');
            $em = $this->getDoctrine()->getManager();

            $lotCarte = $em->getRepository('TMDAppliBundle:NTracking')->findOneBy(array('numplage' => $id ));
            if ($lotCarte->getValid() == 1 and $lotCarte->getPlagedebut() == $lotCarte->getCurrent()){
                $em->remove($lotCarte);
                $em->flush();
                return new JsonResponse(array('OK MODIF'));
            }
            else{
                return new JsonResponse(array(1));
            }
        };

        return new Response("erreur: ce n'est pas du Json", 400);

    }

    public function listeCarteAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $idClient = $request->get('idClient');
            $em = $this->getDoctrine()->getManager();
            $lotCartes = $em->getRepository('TMDAppliBundle:NTracking')->findListeCarteByClient($idClient);

            $nbCarte = $em->getRepository('TMDAppliBundle:NTracking')->findNbCarteUtilByClient($idClient);


            return new JsonResponse(array($lotCartes, $nbCarte));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function productionAction($bl, $etat, $dateDepot, $verif)
    {
        $em = $this->getDoctrine()->getManager();
        $button = false;



        if ($etat != 'nc' and $etat != '3') {
            $Cmd = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl));

            if ($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage() != null) {
                $CmdAppliImage['appliImage'] = (base64_encode(stream_get_contents($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage())));
            } else {
                $CmdAppliImage['appliImage'] = '';
            }

            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findBy(array('numbl' => $bl));


            $cheminImage = $Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getDossierimg();
            $env = $this->container->get('kernel')->getEnvironment();

            if ($env != 'dev'){
                $chemeinImageMod = str_replace('\\standard$\\','/standard/',$cheminImage);
                $chemeinImageMod1 = str_replace('\\','/',$chemeinImageMod);
            }else{
                $chemeinImageMod1 = $cheminImage;
            }




            $articleArray = array();
            foreach ($articles as $k=>$v)
            {
                if (!$v->isFlagart()) {
                    $articleArray['art'][$k]['Image'] = null;
                    $articleArray['art'][$k]['perso1'] = $v->getPerso1();
                    $articleArray['art'][$k]['perso2'] = $v->getPerso2();
                    $articleArray['art'][$k]['libelle'] = $v->getLibelle();
                    if (sizeof($cheminImage . $v->getNomimg()) > 0) {
                        $articleArray['art'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                    }
                    $articleArray['art'][$k]['nameImage'] = $v->getNomimg();
                }
                else{
                    if (sizeof($cheminImage . $v->getNomimg()) > 0) {
                        $articleArray['emb'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                    }
                    $articleArray['emb'][$k]['libelle'] = $v->getLibelle();
                }

            }
            $countArticle = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bl);
            $articleArray['countArticle'] = $countArticle;

            $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBl($bl);

            if ($etat === '1') {
                $messageApresScan = "Commande N° " . $bl . " déjà produite !";
                $button = true;
            }elseif($etat === '0') {
                $messageApresScan = "La commande N° " . $bl . " a été mise à jour";
            }elseif($etat === '2') {
                $messageApresScan = "ZPL inexistant on non genéré pour le BL N° " . $bl;
            }
            else{
                $messageApresScan = "Probleme de reseau !";
            }



//            $user = $this->getUser();
//
//                $histo = new EcommHistoStatut();
//                $histo->setDatestatut(new \DateTime());
//                $histo->setIdstatut(2);
//                $histo->setNumbl($bl);
//                $histo->setIduser($user->getId());
//                $em->persist($histo);
//                $dateProdAverif = new \DateTime();
//                $BlaProduire->setDateProduction($dateProdAverif);
//
//                if ($dateDepot === 0){
//                    $date = (new \DateTime())->format('Y-m-d');
//                    $dateDepotAverif = $date;
//                    $BlaProduire->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
//                    $trackBL = $BlaProduire->getBl()->getNumligne();
//                }
//                else{
//                    $date = (new \DateTime($dateDepot))->format('Y-m-d');
//                    $dateDepotAverif = $date;
//                    $BlaProduire->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
//                    $trackBL = $BlaProduire->getBl()->getNumligne();
//                }
//            }
//            else{
//                $updateHistoOK = true;
//                $updateProdOK = true;
//                $updateDateDepotOK = true;
//            }
//
//            $em->flush();
//
//
////      Verification de histoStatut-cmd
//            if ($articleAverif != null)
//            {
//                $break = 0;
//                while (!$updateHistoOK and $break < 3 ) {
//                    $histoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $numBLUpdate, 'idstatut' => '2'));
//                    if (sizeof($histoVerif) == 0) {
//                        $break ++;
//                        $em->persist($histo);
//                        $em->flush();
//                    }else{
//                        $updateHistoOK = true;
//                        break;
//                    }
//                }
//
//
//                $break1 = 0;
//                while (!$updateArticleOK and $break1 < 3) {
//                    $articleVerif = $em->getRepository('TMDProdBundle:EcommCmdep')->findOneBy(array('numbl' => $numBLUpdate, 'flagProd' => '1'));
//                    if (sizeof($articleVerif) == 0) {
//                        $break1 ++;
//                        $articleAverif->setFlagProd(1);
//                        $em->flush();
//                    }else{
//                        $updateArticleOK = true;
//                        break;
//                    }
//                }
//                if ($dateDepotAverif != null ) {
//                    $break2 = 0;
//                    while (!$updateDateDepotOK  and $break2 < 3) {
//                        $dateVerif = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('expRef' => $numBLUpdate, 'dateDepot' => \DateTime::createFromFormat('Y-m-d', $dateDepotAverif)));
//                        if (sizeof($dateVerif) == 0) {
//                            $break2++;
//                            $trackBL->setDateDepot(\DateTime::createFromFormat('Y-m-d', $dateDepotAverif));
//                            $em->flush();
//                        } else {
//                            $updateDateDepotOK = true;
//                            break;
//                        }
//                    }
//                }
//
//                if ($dateProdAverif != null) {
//                    $break3 = 0;
//                    while (!$updateProdOK and $break3 < 3) {
//                        $dateProdVerif = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $numBLUpdate, 'dateProduction' => $dateProdAverif));
//                        if (sizeof($dateProdVerif) == 0) {
//                            dump('4');
//                            $break3++;
//                            $BlaProduire->setDateProduction($dateProdAverif);
//                            $em->flush();
//                        } else {
//                            $updateProdOK = true;
//                            break;
//                        }
//                    }
//                }






            return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
                'etat'          =>$etat,
                'apresScan'     =>$messageApresScan,
                'blCmd'         =>$Cmd,
                'logoAppli'     =>$CmdAppliImage,
                'articles'      =>$articleArray,
                'button'        =>$button,
                'histo'         =>$histo,
                'dateDepotN'    =>$dateDepot
            ));
        }
//        if ( $etat == '2'){
//
//            return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
//                'etat'          =>$etat,
//                'apresScan'     =>$messageApresScan,
//                'button'        =>$button,
//                'dateDepotN'    =>$dateDepot
//            ));
//        }

        $messageApresScan = "La commande N° " . $bl . " n'existe pas !";

        if ($dateDepot == 0){
            $dateDepot = null;

        }

        return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
            'etat'          =>$etat,
            'apresScan'     =>$messageApresScan,
            'dateDepotN'    =>$dateDepot

        ));

    }
}
