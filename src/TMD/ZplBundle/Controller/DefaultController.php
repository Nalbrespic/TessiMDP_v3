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
                $numLigne = $bl->getBl()->getNumLigne();
            }
            catch (Exception $e) {
                echo 'Exception reçue : ', $e->getMessage(), "\n";
                return new JsonResponse("Base non accessible !", 409);
            }

            //est ce que le BL n'est pas en statut annulé ??'
            $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('numligne' => $numLigne));
            $statutAnnule = $em->getRepository('TMDProdBundle:EcommStatut')->findOneBy(array('idStatut' => 9));

            if ($bl != null and $tracking->getIdStatut() == $statutAnnule) {
                $annule = 1;
            }

            if (sizeof($donne) == 0) {
                $dateF = $bl->getBl()->getNumligne()->getDateDepot();
            }

            if ($bl == null and $annule == 0) {
                return new JsonResponse("Le BL n'existe pas !", 401);
            }
            else if($bl == null and $annule == 1){
                return new JsonResponse("La commande a été annulée !", 402);
            }
            else {
                $blBefore = clone $bl;
            }

//            faut t'il faire un rapprochement pour cette application ??
            $VerifAppli = $bl->getBl()->getNumligne()->getIdfile()->getIdappli()->isMailing();

            if ( $okverif == 1 or date_format($bl->getDateProduction(), 'Y-m-d') != '-0001-11-30' or $verif == 1 ) {
                $VerifAppli = false;
            }

            $zpl = 'inconnu';
            $zplMod = 'inconnu';

            if ($bl->getBl()->getNumligne()->getTypeProduction() == 1 or
                    $bl->getBl()->getNumligne()->getTypeProduction() == 3 or
                    $bl->getBl()->getNumligne()->getTypeProduction() == 4 or
                    $bl->getBl()->getNumligne()->getTypeProduction() == 6 or
                    $bl->getBl()->getNumligne()->getTypeProduction() == 11) {

                $zplMod = 'spool';
            }
            else {
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
                }
                else {
                    try {
                        $zpl = file_get_contents('\\\\Tmdsws801\sg\sg_savoye\\' . $numCmd . '.zpl');
                    }
                    catch (Exception $e) {
                        $serializer = $this->get('jms_serializer');
                        $blArray = $serializer->serialize($blBefore, 'json');
                        $donneArray = $serializer->serialize($donne, 'json');
                        $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);
                        return new JsonResponse($tot, 408);
                    }
                }
            }

            if ($zpl != 'inconnu') {
                $zplMod1 = str_replace('#POIDSKG#',($bl->getPoids())/100, $zpl);
                $zplMod = str_replace('#DATE#', date_format($dateF, 'd/m/Y'), $zplMod1);
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
                    }
                    else {
                        $updateProdOK = true;
                        break;
                    }
                }

                if (!$updateHistoOK OR !$updateProdOK OR !$updateDateDepotOK OR !$updatenTrackOK) {
                    return new JsonResponse("Probléme d'enregistrement ... Veuillez recommencer !", 405);
                }
                else {
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
