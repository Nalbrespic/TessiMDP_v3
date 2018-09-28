<?php

namespace TMD\ZplBundle\Controller;


use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
            $dateF = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateF){
                $dateF = (new \DateTime());
            }

            $donne = ['date' => date_format($dateF, 'Y-m-d')];


            $env = $this->container->get('kernel')->getEnvironment();

            try {
                $bl = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' =>$numCmd));

            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
                return new JsonResponse("Base non accessible !", 400);
            }
//            dump($bl);

            if ( $bl == null){

                    return new JsonResponse("Le BL n'existe pas !", 401);
                }

                $zpl = null;
                $zplMod = null;

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

                if ($zpl != null){

                    $zplMod1=str_replace('#POIDSKG#',($bl->getPoids())/100,$zpl);
                    $zplMod=str_replace('#DATE#',date_format($dateF, 'd/m/Y'),$zplMod1);
                }




            $serializer = $this->get('jms_serializer');
            $blArray = $serializer->serialize($bl, 'json');



            $donneArray = $serializer->serialize($donne, 'json');

            $tot = array('bl' => $blArray,'zpl' => $zplMod,'donne' => $donneArray );

            return new JsonResponse($tot);
        }
        return new Response("erreur: ce n'est pas du Json", 400);
    }

}
