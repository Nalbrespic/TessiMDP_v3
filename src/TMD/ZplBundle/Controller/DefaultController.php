<?php

namespace TMD\ZplBundle\Controller;


use DateTime;
use Exception;
use Proxies\__CG__\TMD\ProdBundle\Entity\EcommStatut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommBl;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\Entity\Siteprod;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();
//
//        if ($request->isMethod('POST')) {
//            $cmd = $request->request->get('numCmd');
//
//            dump($cmd);
//
//
//            $bl = $em->getRepository('TMDProdBundle:EcommBl')->findBy(array('numCmdeClient' => $cmd));
//
//            return $this->render('TMDZplBundle:Zpl:index.html.twig', array(
//                'bl'    => $bl,
////                'zpl'   => $zpl,
//                'numCmd'  => $cmd
//            ));
//        }
//
//        $bl = $em->getRepository('TMDProdBundle:EcommBl')->findBy(array('bl' => '10388073'));
//
//
//        return $this->render('TMDZplBundle:Zpl:index.html.twig', array(
//
////            'numCmd' =>1
//        ));
    }

    public function imprimEtiqAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $numCmd = $request->get('numCmd');
            $date = $request->request->get('date');
            $verif = $request->request->get('verif');
            $okverif = $request->request->get('okverif');
            $annule = 0;
            $donne = array();
            if ($date) {
                $dateF = DateTime::createFromFormat('Y-m-d', $date);
                $donne = ['date' => $dateF];
            }

            $env = $this->container->get('kernel')->getEnvironment();

            try {
                $bl = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $numCmd));
            } catch (Exception $e) {
                echo 'Exception reçue : ', $e->getMessage(), "\n";
                return new JsonResponse("Base non accessible !", 409);
            }

            //est ce que le BL n'est pas en statut annulé ??'
            $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('expRef' => $numCmd));
            $statutAnnule =$em->getRepository('TMDProdBundle:EcommStatut')->findOneBy(array('idStatut' => 9));

            if ($bl != null and $tracking->getIdStatut() == $statutAnnule) {
                $annule = 1;
            }

            if (sizeof($donne) == 0) {
                $dateF = $bl->getBl()->getNumligne()->getDateDepot();
            }



            if ($bl == null and $annule == 0) {
                return new JsonResponse("Le BL n'existe pas !", 401);
            }else if($bl == null and $annule == 1){
                return new JsonResponse("La commande a été annulée !", 402);
            } else {
                $blBefore = clone $bl;
            }




//            faut t'il faire un rapprochement pour cette application ??
            $VerifAppli = $bl->getBl()->getNumligne()->getIdfile()->getIdappli()->isMailing();

            if ( $okverif == 1 or date_format($bl->getDateProduction(), 'Y-m-d') != '-0001-11-30' or $verif == 1){
                $VerifAppli = false;
            }



            $zpl = 'inconnu';
            $zplMod = 'inconnu';


//            $isColWS = $bl->getBl()->getNumligne()->getExpCompte();

//            $trans = $bl->getBl()->getNumligne()->getTypeTransport();
//            if ($isColWS != '923108'){
//                if ($trans == 'COL' or $trans == 'COLD' or $trans == 'COLI' and $isColWS != '923108') {
//
//                    $zplMod = 'colissimo';
//                }
//            }




            // Construction du web service Colissimo
//            $productCode = $trans;
//            if ($bl->getBl()->getNumligne()->getMontant() == '0.00'){
//                $totalAmount = '1000';
//            }else{
//                $totalAmount = number_format($bl->getBl()->getNumligne()->getMontant(),0)*100;
//            }
//            if ($bl->getBl()->getNumligne()->getMontant() == '0.00'){
//                $insuranceValue = '0';
//            }else{
//                $insuranceValue = number_format($bl->getBl()->getNumligne()->getMontant(),0)*100;
//            }
//
//            $contentAll = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesDetailsByBL($numCmd);
//
//            $cn23 = null;
//            $nTrack = null;
//            $webServtag = 0;
//            if ( $isColWS == '923108'){
//                $webServtag = 1;
//                if (date_format($bl->getDateProduction(), 'Y-m-d') == '-0001-11-30'){
//
//
//                    $webServ = $this->container->get('tmd_zpl.colissimo');
//                    $donnee = $webServ->send($productCode, $totalAmount, $insuranceValue, $bl, $dateF, $contentAll, $env);
//                    $zplMod = $donnee['zpl'];
//                    $nTrack = $donnee['nTRACK'];
//                    if ($donnee['CN23'] != ''){
//                        $cn23 = 1;
//                    }
//
//                    if (($donnee['erreur']) != '') {
//                        $serializer = $this->get('jms_serializer');
//                        $blArray = $serializer->serialize($blBefore, 'json');
//                        $donneArray = $serializer->serialize($donne, 'json');
//                        $tot = array('bl' => $blArray, 'erreur' => $donnee['erreur'], 'donne' => $donneArray);
//                        return new JsonResponse($tot, 407);
//                    }
//                }
//            }

//            if (  $zplMod != 'colissimo' and  $webServtag == 0 ){
                if ($bl->getBl()->getNumligne()->getTypeProduction() == 1 or
                        $bl->getBl()->getNumligne()->getTypeProduction() == 3 or
                        $bl->getBl()->getNumligne()->getTypeProduction() == 4 or
                        $bl->getBl()->getNumligne()->getTypeProduction() == 6 or
                        $bl->getBl()->getNumligne()->getTypeProduction() == 11) {

                    $zplMod = 'spool';
                }else{
                    if ($env != 'dev') {

                        try {
                            $zpl = file_get_contents('/tmdsws801/SG/' . $numCmd . '.zpl');
                        } catch (Exception $e) {
                            $serializer = $this->get('jms_serializer');
                            $blArray = $serializer->serialize($blBefore, 'json');
                            $donneArray = $serializer->serialize($donne, 'json');
                            $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);
                            return new JsonResponse($tot, 408);
                        }

                    } else {
                        try {
                            $zpl = file_get_contents('\\\\Tmdsws801\sg\sg_savoye\\' . $numCmd . '.zpl');
                        } catch (Exception $e) {
                            $serializer = $this->get('jms_serializer');
                            $blArray = $serializer->serialize($blBefore, 'json');
                            $donneArray = $serializer->serialize($donne, 'json');
                            $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);
                            return new JsonResponse($tot, 408);
                        }

                    }
                }
//            }
//            elseif (  $zplMod != 'colissimo' and  $webServtag == 1 and $verif == 1 ){
//
//                if ($env != 'dev') {
////
//                    try {
//                        $zpl = file_get_contents('/tmdsws801/WS/' . $numCmd . '.zpl');
//                    } catch (Exception $e) {
//                        $serializer = $this->get('jms_serializer');
//                        $blArray = $serializer->serialize($blBefore, 'json');
//                        $donneArray = $serializer->serialize($donne, 'json');
//                        $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);
//                        return new JsonResponse($tot, 408);
//                    }
//
//                } else {
//
//                    try {
//                        $zpl = file_get_contents('\\\\Tmdsws801\sg\sg_savoye\\' . $numCmd . '.zpl');
//                    } catch (Exception $e) {
//                        $serializer = $this->get('jms_serializer');
//                        $blArray = $serializer->serialize($blBefore, 'json');
//                        $donneArray = $serializer->serialize($donne, 'json');
//                        $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);
//                        return new JsonResponse($tot, 408);
//                    }
//
//                }
//            }

            if ($zpl != 'inconnu'){
                $zplMod1=str_replace('#POIDSKG#',($bl->getPoids())/100,$zpl);
                $zplMod=str_replace('#DATE#',date_format($dateF, 'd/m/Y'),$zplMod1);
            }



            if ($verif != 1 and $zplMod != 'inconnu' and (date_format($bl->getDateProduction(), 'Y-m-d') == '-0001-11-30' and !$VerifAppli and $annule == 0)) {
                $user = $this->getUser();



                $histo2 = new EcommHistoStatut();
                $histo2->setDatestatut(new \DateTime());
                $histo2->setIdstatut(1);
                $histo2->setObservation("preparation commande");
                $histo2->setNumbl($bl->getBl()->getNumbl());
                $histo2->setIduser($user->getId());

                $histo = new EcommHistoStatut();
                $histo->setDatestatut(new \DateTime());
                $histo->setIdstatut(2);
                $histo->setObservation("Validation commande");
                $histo->setNumbl($bl->getBl()->getNumbl());
                $histo->setIduser($user->getId());

                $em->persist($histo2);
                $em->persist($histo);
                $dateProdAverif = new \DateTime();
                $bl->setDateProduction($dateProdAverif);

                $site = $em->getRepository('TMDProdBundle:Siteprod')->findOneBy(array('idsiteprod' => 3));
                $bl->setSiteProduction($site);

                //lié au WS colissimo
//                if ($nTrack != null){
//                    $bl->setNColis($nTrack);
//                }

                $dateDepotAverif = $dateF;
                $bl->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
                $trackBL = $bl->getBl()->getNumligne();

                $em->flush();

                $updateHistoOK = false;
                $updateProdOK = false;
                $updateDateDepotOK = false;
                //lié au WS colissimo  ... force a true
                $updatenTrackOK = true;

//      Verification de histoStatut-cmd

                $break = 0;
                while (!$updateHistoOK and $break < 3) {
                    $histoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $bl->getBl()->getNumbl(), 'idstatut' => '2'));
                    if (sizeof($histoVerif) == 0) {
                        $break++;
                        $em->persist($histo);
                        $em->flush();
                    } else {
                        $updateHistoOK = true;
                        break;
                    }
                }

                $break2 = 0;
                while (!$updateDateDepotOK and $break2 < 3) {
                    $dateVerif = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('expRef' => $bl->getBl()->getNumbl(), 'dateDepot' => $dateDepotAverif));
                    if (sizeof($dateVerif) == 0) {
                        $break2++;
                        $trackBL->setDateDepot($dateDepotAverif);
                        $em->flush();
                    } else {
                        $updateDateDepotOK = true;
                        break;
                    }
                }


                $break3 = 0;
                while (!$updateProdOK and $break3 < 3) {
                    $dateProdVerif = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl->getBl()->getNumbl(), 'dateProduction' => $dateProdAverif));
                    if (sizeof($dateProdVerif) == 0) {
                        $break3++;
                        $bl->setDateProduction($dateProdAverif);
                        $em->flush();
                    } else {
                        $updateProdOK = true;
                        break;
                    }
                }

                //lié au WS colissimo
//                if ($nTrack != null) {
//                    $break4 = 0;
//                    while (!$updatenTrackOK and $break4 < 3) {
//                        $nTrackVerif = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl->getBl()->getNumbl(), 'nColis' => $nTrack));
//                        if (sizeof($nTrackVerif) == 0) {
//                            $break4++;
//                            $bl->setNColis($nTrack);
//                            $em->flush();
//                        } else {
//                            $updatenTrackOK = true;
//                            break;
//                        }
//                    }
//                }else{
//                    $updatenTrackOK = true;
//                }

                if (!$updateHistoOK OR !$updateProdOK OR !$updateDateDepotOK OR !$updatenTrackOK) {
                    return new JsonResponse("Probléme d'enregistrement ... Veuillez recommencer !", 405);
                } else {
                    $serializer = $this->get('jms_serializer');
                    $blArray = $serializer->serialize($blBefore, 'json');
                    $donneArray = $serializer->serialize($donne, 'json');
                    //lié au WS colissimo
                    $cn23 = null;
                    $tot = array('bl' =>  $blArray, 'zpl' => $zplMod, 'donne' => $donneArray, 'CN23' => $cn23, 'verifAppli' => $VerifAppli, 'verifAppliOK' => $okverif, 'annule' => $annule);
                    return new JsonResponse($tot);

                }
            }
            $serializer = $this->get('jms_serializer');
            $blArray = $serializer->serialize($blBefore, 'json');
            $donneArray = $serializer->serialize($donne, 'json');
//            'zpl' =>utf8_encode(utf8_decode($zplMod))
            $tot = array('bl' => $blArray, 'zpl' =>$zplMod, 'donne' => $donneArray, 'verifAppli' => $VerifAppli, 'verifAppliOK' => $okverif , 'annule' => $annule);

            return new JsonResponse($tot);


        }
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function imprimColissimoBordereauAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nbBlforBordreau = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlForBordereauTrack();

        $tabTracking = array();
        foreach ($nbBlforBordreau as $v){
            array_push($tabTracking, $v['nColis']);
        }

        if ($request->isXmlHttpRequest()) {

            $env = $this->container->get('kernel')->getEnvironment();

            $webServ = $this->container->get('tmd_zpl_colissimoBordereau');
            $donnee = $webServ->sendFormulaire($env, $tabTracking,'0');

            $message = $donnee['message'];
            $erreur = $donnee['error'];

            if ( $erreur != '') {
                $tot = array('error' => $erreur);
                return new JsonResponse($tot, 407);
            }
            $tot = array('message' => $message);
            return new JsonResponse($tot);

        }
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function imprimColissimoInformationAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {

            $env = $this->container->get('kernel')->getEnvironment();
            $track = $request->request->get('numCol');
            $trackTab = [$track];
            $webServ = $this->container->get('tmd_zpl_colissimoBordereau');
            $donnee = $webServ->sendFormulaire($env, $trackTab,'1');

            $message = $donnee['message'];
            $erreur = $donnee['error'];

            if ( $erreur != '') {
                $tot = array('error' => $erreur);
                return new JsonResponse($tot, 407);
            }
            $tot = array('message' => $message);
            return new JsonResponse($tot);

        }
        return new Response("erreur: ce n'est pas du Json", 400);
    }

}
