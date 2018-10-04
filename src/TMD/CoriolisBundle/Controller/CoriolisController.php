<?php

namespace TMD\CoriolisBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommHistoStatut;

class CoriolisController extends Controller
{
    public function indexAction()
    {

//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }


        $idApplication = 554;
        $em = $this->getDoctrine()->getManager();

//        $dateNow = (new DateTime());

        $resteAproduireCm = $em->getRepository('TMDProdBundle:EcommBl')->donneCmdCoriolis($idApplication);
        $resteAproduireBl = $em->getRepository('TMDProdBundle:EcommBl')->donneBlCoriolis($idApplication);
        return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
            'resteAproduireCm'        => $resteAproduireCm,
            'resteAproduireBl'        => $resteAproduireBl,
//            'dateJour'                => $dateNow

        ));
    }

    public function envoiNumRefAction(Request $request)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        $idApplication = 554;
        if ($request->isXmlHttpRequest()) {

            $numRef = $request->get('numRef');
            $emanager = $this->getDoctrine()->getManager();

            $numCmdCoriolis = $emanager->getRepository('TMDProdBundle:EcommBl')->donneCmdCoriolisWithNumRef($numRef, $idApplication);

            $allCarteByCmd =  $emanager->getRepository('TMDProdBundle:EcommBl')->donneAllArticleByCmd($numCmdCoriolis);
            $CmdAlreadyLivre = true;
            if ($allCarteByCmd) {
                $allCarteByCmd[0]['tagProdTrue'] = 0;
                $allCarteByCmd[0]['tagCmdComplete'] = 0;
            }
            foreach ( $allCarteByCmd as $k => $v ){
                $allCarteByCmd[$k]['detail'] = utf8_encode ((stream_get_contents($v['record'])));
                $allCarteByCmd[$k]['record'] = null;
                if ( $allCarteByCmd[$k]['numRef'] == $numRef and  $allCarteByCmd[$k]['flagProd'] == true){
                    $allCarteByCmd[0]['tagProdTrue'] = 1;
                }
                if ($CmdAlreadyLivre) {
                    if ($allCarteByCmd[$k]['flagProd'] == false) {
                        $CmdAlreadyLivre = false;
                    }
                }
            }
            if ($allCarteByCmd) {
                if ($CmdAlreadyLivre){
                    $allCarteByCmd[0]['tagCmdComplete'] = 1;
                }
            }

            return new JsonResponse($allCarteByCmd);
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function envoiNumTrackAction(Request $request,$numRef, $numTrack, $numCmd, $dateDepot)
    {
        $user = $this->getUser();
        $idApplication = 554;
        $em = $this->getDoctrine()->getManager();


        $CmdepByCmd = $em->getRepository('TMDProdBundle:EcommCmdep')->findBy(array('numcmde' => $numCmd, 'idclient' => '679' ));
        $cmdComplete = true;
        $Corresp = false;

        $updateHistoOK = false;
        $updateArticleOK = false;
        $updateDateDepotOK = false;
        $updateProdOK = false;


        $articleAverif = null;
        $dateDepotAverif = null;
        $dateProdAverif = null;
        $trackBL = null;
        $BlaProduire = null;
        $numBLUpdate = null;

        foreach ($CmdepByCmd as $article){

            if ( $article->getNumRef() == $numRef and $article->getNumTrack() == $numTrack ){
                $Corresp = true;
                $article->setFlagProd(1);
                $articleAverif = $article;
                $numBLUpdate = $CmdepByCmd[0]->getNumbl()->getNumbl();
            }
            if (!$article->isFlagProd()){
                $cmdComplete = false;
             }
        }

        if ($cmdComplete){
            $BlaProduire = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $CmdepByCmd[0]->getNumbl()));
            $histo = new EcommHistoStatut();
            $histo->setDatestatut(new \DateTime());
            $histo->setIdstatut(2);
            $histo->setNumbl($CmdepByCmd[0]->getNumbl()->getNumbl());
            $histo->setIduser($user->getId());
            $em->persist($histo);
            $dateProdAverif = new \DateTime();
            $BlaProduire->setDateProduction($dateProdAverif);

            if ($dateDepot === 0){
                $date = (new \DateTime())->format('Y-m-d');
                $dateDepotAverif = $date;
                $BlaProduire->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
                $trackBL = $BlaProduire->getBl()->getNumligne();
            }
            else{
                $date = (new \DateTime($dateDepot))->format('Y-m-d');
                $dateDepotAverif = $date;
                $BlaProduire->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
                $trackBL = $BlaProduire->getBl()->getNumligne();
            }
        }
        else{
            $updateHistoOK = true;
            $updateProdOK = true;
            $updateDateDepotOK = true;
        }

        $em->flush();


//      Verification de histoStatut-cmd
        if ($articleAverif != null)
        {
            $break = 0;
            while (!$updateHistoOK and $break < 3 ) {
                $histoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $numBLUpdate, 'idstatut' => '2'));
                if (sizeof($histoVerif) == 0) {
                    $break ++;
                    $em->persist($histo);
                    $em->flush();
                }else{
                    $updateHistoOK = true;
                    break;
                }
            }


            $break1 = 0;
            while (!$updateArticleOK and $break1 < 3) {
                $articleVerif = $em->getRepository('TMDProdBundle:EcommCmdep')->findOneBy(array('numbl' => $numBLUpdate, 'flagProd' => '1'));
                if (sizeof($articleVerif) == 0) {
                    $break1 ++;
                    $articleAverif->setFlagProd(1);
                    $em->flush();
                }else{
                    $updateArticleOK = true;
                    break;
                }
            }
            if ($dateDepotAverif != null ) {
                $break2 = 0;
                while (!$updateDateDepotOK  and $break2 < 3) {
                    $dateVerif = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('expRef' => $numBLUpdate, 'dateDepot' => \DateTime::createFromFormat('Y-m-d', $dateDepotAverif)));
                    if (sizeof($dateVerif) == 0) {
                        $break2++;
                        $trackBL->setDateDepot(\DateTime::createFromFormat('Y-m-d', $dateDepotAverif));
                        $em->flush();
                    } else {
                        $updateDateDepotOK = true;
                        break;
                    }
                }
            }

            if ($dateProdAverif != null) {
                $break3 = 0;
                while (!$updateProdOK and $break3 < 3) {
                    $dateProdVerif = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $numBLUpdate, 'dateProduction' => $dateProdAverif));
                    if (sizeof($dateProdVerif) == 0) {
                        $break3++;
                        $BlaProduire->setDateProduction($dateProdAverif);
                        $em->flush();
                    } else {
                        $updateProdOK = true;
                        break;
                    }
                }
            }

        }




        $resteAproduireCm = $em->getRepository('TMDProdBundle:EcommBl')->donneCmdCoriolis($idApplication);
        $resteAproduireBl = $em->getRepository('TMDProdBundle:EcommBl')->donneBlCoriolis($idApplication);

        if (!$Corresp){
            $res = array();
            $res['numTrack'] = $numTrack;
            $res['numRef'] = $numRef;
            $request->getSession()->getFlashBag()->add('NonCorrespNumTrackCoriolis',$res);

            if ($dateDepot === 0){
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm'        => $resteAproduireCm,
                    'resteAproduireBl'        => $resteAproduireBl

                ));
            }else{
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm'        => $resteAproduireCm,
                    'resteAproduireBl'        => $resteAproduireBl,
                    'dateDepotN'              =>$dateDepot

                ));
            }
        }


        if($updateHistoOK and $updateArticleOK and $updateDateDepotOK and $updateProdOK) {

            if ($cmdComplete) {
                $resu = array();
                $resu['bl'] = $numCmd;
                $resu['numCmd'] = $CmdepByCmd[0]->getNumbl()->getNumligne()->getRefclient();
                $request->getSession()->getFlashBag()->add('ConfirmProdCmdCoriolis', $resu);
                if ($dateDepot === 0) {
                    return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                        'resteAproduireCm' => $resteAproduireCm,
                        'resteAproduireBl' => $resteAproduireBl

                    ));
                } else {
                    return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                        'resteAproduireCm' => $resteAproduireCm,
                        'resteAproduireBl' => $resteAproduireBl,
                        'dateDepotN' => $dateDepot

                    ));
                }

            }


            if ($dateDepot === 0) {
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm' => $resteAproduireCm,
                    'resteAproduireBl' => $resteAproduireBl,
                    'numRefApresValideCoriolis' => $numRef

                ));
            } else {
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm' => $resteAproduireCm,
                    'resteAproduireBl' => $resteAproduireBl,
                    'dateDepotN' => $dateDepot,
                    'numRefApresValideCoriolis' => $numRef

                ));
            }
        }else{
            if ($cmdComplete) {
                $em->remove($histo);
            }
            $articleAverif->setFlagProd(0);
//            if ($dateProdAverif != null) {
//                $BlaProduire->setDateProduction(null);
//            }
//            if ($dateDepotAverif != null) {
//                $dateh = (new \DateTime());
//                $trackBL->setDateDepot(null);
//            }

//
//            $date = (new \DateTime())->format('Y-m-d');
//            $dateDepotAverif = $date;
//            $BlaProduire->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));

            $em->flush();


            $resteAproduireCm = $em->getRepository('TMDProdBundle:EcommBl')->donneCmdCoriolis($idApplication);
            $resteAproduireBl = $em->getRepository('TMDProdBundle:EcommBl')->donneBlCoriolis($idApplication);

            $res = array();
            $res['numTrack'] = $numTrack;
            $res['numRef'] = $numRef;
            $request->getSession()->getFlashBag()->add('NonUpdateNumTrackCoriolis',$res);

            if ($dateDepot === 0){
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm'        => $resteAproduireCm,
                    'resteAproduireBl'        => $resteAproduireBl

                ));
            }else{
                return $this->render('TMDCoriolisBundle:Coriolis:index.html.twig', array(
                    'resteAproduireCm'        => $resteAproduireCm,
                    'resteAproduireBl'        => $resteAproduireBl,
                    'dateDepotN'              =>$dateDepot

                ));
            }
        }

    }

}
