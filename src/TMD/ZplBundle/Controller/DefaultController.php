<?php

namespace TMD\ZplBundle\Controller;


use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommBl;
use TMD\ProdBundle\Entity\EcommHistoStatut;


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

        if ($request->isXmlHttpRequest())
        {
            $numCmd = $request->get('numCmd');
            $date = $request->request->get('date');
            $verif = $request->request->get('verif');
            $dateF = DateTime::createFromFormat('Y-m-d', $date);
//            if (!$dateF){
//                $dateF1 = (  new \DateTime());
//                $dateF = DateTime::createFromFormat('Y-m-d', $dateF1->format('Y-m-d'));
//            }
            $donne = ['date' => $dateF];


            $env = $this->container->get('kernel')->getEnvironment();

            try {
                $bl = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' =>$numCmd));

            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
                return new JsonResponse("Base non accessible !", 400);
            }
            if (!$dateF){
               $dateF = $bl->getBl()->getNumligne()->getDateDepot();
            }

            if ( $bl == null){
                return new JsonResponse("Le BL n'existe pas !", 401);
            }else{
                $blBefore = clone $bl;
            }

            $zpl = 'inconnu';
            $zplMod = 'inconnu';

            if ($env != 'dev'){
                try {
                    $zpl = file_get_contents('/tmdsws801/SG/'.$numCmd.'.zpl');
                } catch (Exception $e) {
                    echo 'Exception reçue : ',  $e->getMessage(), "\n";
                    return new JsonResponse("L'étiquette n'est pas créee ou n'existe pas !", 400);
                }
            }else{

                try {
                    $zpl = file_get_contents('\\\\Tmdsws801\sg\sg_savoye\\'.$numCmd.'.zpl');
                }catch(Exception $e) {
//                        echo 'Exception reçue : ',  $e->getMessage(), "\n";
                }

            }


            if ($zpl != 'inconnu'){
                $zplMod1=str_replace('#POIDSKG#',($bl->getPoids())/100,$zpl);
                $zplMod=str_replace('#DATE#',date_format($dateF, 'd/m/Y'),$zplMod1);
            }

            if ($verif != 1 and $zplMod != 'inconnu' and (date_format($bl->getDateProduction(), 'Y-m-d') == '-0001-11-30')) {
                $user = $this->getUser();

                $histo = new EcommHistoStatut();
                $histo->setDatestatut(new \DateTime());
                $histo->setIdstatut(2);
                $histo->setObservation("Validation commande");
                $histo->setNumbl($bl->getBl()->getNumbl());
                $histo->setIduser($user->getId());

                $histo2 = new EcommHistoStatut();
                $histo2->setDatestatut(new \DateTime());
                $histo2->setIdstatut(1);
                $histo2->setObservation("preparation commande");
                $histo2->setNumbl($bl->getBl()->getNumbl());
                $histo2->setIduser($user->getId());

                $em->persist($histo);
                $em->persist($histo2);
                $dateProdAverif = new \DateTime();
                $bl->setDateProduction($dateProdAverif);

                $dateDepotAverif = $dateF;
                $bl->getBl()->getNumligne()->setDateDepot(\DateTime::createFromFormat('Y-m-d', $date));
                $trackBL = $bl->getBl()->getNumligne();

                $em->flush();

                $updateHistoOK = false;
                $updateProdOK = false;
                $updateDateDepotOK = false;

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


                if (!$updateHistoOK OR !$updateProdOK OR !$updateDateDepotOK) {
                    return new JsonResponse("Probléme d'enregistrement ... Veuillez recommencer !", 405);
                } else {
                    $serializer = $this->get('jms_serializer');
                    $blArray = $serializer->serialize($blBefore, 'json');
                    $donneArray = $serializer->serialize($donne, 'json');
                    $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);

                    return new JsonResponse($tot);
                }
            }

            $serializer = $this->get('jms_serializer');
            $blArray = $serializer->serialize($blBefore, 'json');
            $donneArray = $serializer->serialize($donne, 'json');
            $tot = array('bl' => $blArray, 'zpl' => $zplMod, 'donne' => $donneArray);

            return new JsonResponse($tot);

        }
        return new Response("erreur: ce n'est pas du Json", 400);
    }

}
