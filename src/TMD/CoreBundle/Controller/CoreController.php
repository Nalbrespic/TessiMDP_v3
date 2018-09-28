<?php

namespace TMD\CoreBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{


    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $operationsCournates = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsCourantes();

        $user = $this->getUser();
        $roles = $user->getRole()->getOwner()->getRoles();
        foreach ($roles as $role){
            if ($role === 'ROLE_SUPER_ADMIN' or $role === 'ROLE_PROD' or $role === 'ROLE_ATC' or $role === 'ROLE_ADMIN'){
                $new = $em->getRepository('TMDConfigBundle:News')->findOneBy(array('role' => 1, 'published' => 1));
            }
            else{
                $new = $em->getRepository('TMDConfigBundle:News')->findOneBy(array('role' => 3, 'published' => 1));
            }
        }
        foreach ( $operationsCournates as $u=>$v)
        {
            if ( $v['appliImage'] != null) {
                $operationsCournates[$u]['appliImage'] = (base64_encode(stream_get_contents($v['appliImage'])));
            }
        }

        $dateN = new DateTime();
        if (date_format($dateN,'w') > 1 and date_format($dateN,'w') <= 6){
            $date = date("Y-m-d", strtotime('-1 day'));
        }
        elseif(date_format($dateN,'w') == 1){
            $date = date("Y-m-d", strtotime('-3 day'));
        }
        elseif(date_format($dateN,'w') == 0){
            $date = date("Y-m-d", strtotime('-2 day'));
        }


        return $this->render('TMDCoreBundle:Core:accueil.html.twig', array(
            'operations'                => $operationsCournates,
        'dateVeille'                    => $date,
            'new'           => $new
        ));
    }

    public function prodJourAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $nbNumBl = $em->getRepository('TMDProdBundle:EcommBl')->findMaxIdBl();
            $nbNumBl = intval($nbNumBl[0][1])-900000;

            $operationsJour = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsDuJour($nbNumBl);

            return new JsonResponse(array($operationsJour));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function prodVeilleAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $date = $request->get('date');
            $em = $this->getDoctrine()->getManager();

            $nbNumBl = $em->getRepository('TMDProdBundle:EcommBl')->findMaxIdBl();
            $nbNumBl = intval($nbNumBl[0][1])-900000;
            $operationsVeille = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsVeille($date, $nbNumBl);


            return new JsonResponse(array($operationsVeille));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function resteProdAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $allBlNonProd = $em->getRepository('TMDProdBundle:EcommBl')->donneNbAllBlnonProd();


            $tabidAppli=[];
            foreach ($allBlNonProd as $key=>$val){
                $tabidAppli[$key]=$val['idappli'];
            }
            $allArtNonProd = $em->getRepository('TMDProdBundle:EcommBl')->donneNbAllArticlenonProd( );



            foreach ($allBlNonProd as $key=>$val){
                foreach ($allArtNonProd as $k => $v){
                    if ($val['idappli'] == $v['idappli']){
                        $allBlNonProd[$key]['article'] = $v[1];
                    }
                }

            }
            $allBlNonProdb  = array();
            foreach ($allBlNonProd as $key=>$val){
                $allBlNonProdb[$val['nomclient']]=array();
            }
            foreach ($allBlNonProdb as $key=>$val){
                foreach ($allBlNonProd as $k=>$v){
                    if ($key == $v['nomclient']){
                        array_push($allBlNonProdb[$key],$allBlNonProd[$k]);
                    }


                }

            }


            return new JsonResponse(array($allBlNonProdb));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }
}
