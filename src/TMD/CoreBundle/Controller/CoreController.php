<?php

namespace TMD\CoreBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\ProdBundle\Entity\EcommArticles;
use TMD\ProdBundle\Entity\EcommCmdep;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\Entity\EcommLignes;
use TMD\ProdBundle\Form\EcommLignesType;
use TMD\ProdBundle\Form\EcommTrackingType;

class CoreController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $operationsCournates = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsCourantes(6);

        $user = $this->getUser();
        $roles = $user->getRole()->getOwner()->getRoles();
        foreach ($roles as $role){
            if ($role === 'ROLE_SUPER_ADMIN' or $role === 'ROLE_PROD' or $role === 'ROLE_SUPER_PROD' or $role === 'ROLE_ATC' or $role === 'ROLE_ADMIN'){
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
        $this->get('session')->save(); // close session, which allow concurrent requests for the same session

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $nbNumBl = $em->getRepository('TMDProdBundle:EcommBl')->findMaxIdBl();
            $nbNumBl = intval($nbNumBl[0][1])-200000;

            $operationsJour = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsDuJour($nbNumBl);

            return new JsonResponse(array($operationsJour));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function prodVeilleAction(Request $request)
    {
        $this->get('session')->save(); // close session, which allow concurrent requests for the same session

        if ($request->isXmlHttpRequest()) {
            $date = $request->get('date');
            $em = $this->getDoctrine()->getManager();

            $nbNumBl = $em->getRepository('TMDProdBundle:EcommBl')->findMaxIdBl();
            $nbNumBl = intval($nbNumBl[0][1])-200000;
            $operationsVeille = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsVeille($date, $nbNumBl);

            return new JsonResponse(array($operationsVeille));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function rectificationWSAction(Request $request, $idOpe, $current)
    {
        $em = $this->getDoctrine()->getManager();

        $trackingsErreurWS = $em->getRepository('TMDProdBundle:EcommLignes')->findTrackingsPbWSwithop($idOpe);

        $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($trackingsErreurWS[$current]->getNumbl());
        $trackLength = sizeof($trackingsErreurWS);

        $form = $this->get('form.factory')->create(EcommLignesType::class, $trackingsErreurWS[$current]);

        $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlbuerrorWS($trackingsErreurWS[$current]->getNumbl());
        if ($histo ==  null){
            $histoL ='Pas de motif d\'erreur';
        }else{
            $histoL = $histo[0]['observation'];
        }
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $trackingsErreurWS[$current]->getNumligne()->setFlagEtikt(0);
            $trackingsErreurWS[$current]->getNumligne()->setPoids($trackingsErreurWS[$current]->getPoids());
            $em->flush();
            $request->getSession()->getFlashBag()->add('erreurWSConfirmation', 'Modification enregistrée');

            $trackingsErreurWS = $em->getRepository('TMDProdBundle:EcommLignes')->findTrackingsPbWSwithop($idOpe);
            $trackLength = sizeof($trackingsErreurWS);

            if ($trackLength > 0){

                if ($current == $trackLength) $current--;
                $form = $this->get('form.factory')->create(EcommLignesType::class, $trackingsErreurWS[$current]);
                $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($trackingsErreurWS[$current]->getNumbl());
                $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlbuerrorWS($trackingsErreurWS[$current]->getNumbl());
                if ($histo ==  null){
                    $histoL ='Pas de motif d\'erreur';
                }else{
                    $histoL = $histo[$current]['observation'];
                }

                return $this->render('TMDCoreBundle:Core:modifWS.html.twig',  array(
                    'form' => $form->createView(),
                    'autreErreur' => $trackLength,
                    'current' => $current,
                    'motif' => $histoL,
                    'idope'   => $idOpe,
                    'articles'  => $articles
                ) );
            }

            return $this->redirectToRoute('tmd_core_homepage');
        }

        return $this->render('TMDCoreBundle:Core:modifWS.html.twig', array(
            'form' => $form->createView(),
            'autreErreur' => $trackLength,
            'motif' => $histoL,
            'current' => $current,
            'idope'   => $idOpe,
            'articles'  => $articles
        ));
    }


    public function resteProdAction(Request $request)
    {
        $this->get('session')->save(); // close session, which allow concurrent requests for the same session

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $allBlNonProd = $em->getRepository('TMDProdBundle:EcommLignes')->donneNbAllBlnonProd();


            $tabidAppli=[];
            foreach ($allBlNonProd as $key=>$val){
                $tabidAppli[$key]=$val['idappli'];
            }
            $allArtNonProd = $em->getRepository('TMDProdBundle:EcommLignes')->donneNbAllArticlenonProd( );

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


    public function erreurWSAction(Request $request) {
        $this->get('session')->save(); // close session, which allow concurrent requests for the same session

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $trackingsErreurWS = $em->getRepository('TMDProdBundle:EcommTracking')->findTrackingsPbWS();
            return new JsonResponse(array($trackingsErreurWS));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function rootAction(Request $request)
    {
        // redirects to the homepage
        return $this->redirectToRoute('tmd_core_homepage');
    }



    public function reimpressionEtiktAction(Request $request){

        if ($request->isXmlHttpRequest()) {

            $numTracking = $request->get('numTracking');
            dump($numTracking);
            $em = $this->getDoctrine()->getManager();

            $Cmde = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('nColis' => $numTracking));
            dump($Cmde);
            $Bl = $Cmde->getBl()->getNumbl();
            dump($Bl);

            return new JsonResponse(array($Bl));
        }
    }
}
