<?php

namespace TMD\CoreBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\AppliBundle\TMDAppliBundle;
use TMD\CoreBundle\Entity\TransporteursTarif;
use TMD\CoreBundle\TMDCoreBundle;
use TMD\ProdBundle\Entity\EcommArticles;
use TMD\ProdBundle\Entity\EcommCmdep;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\Entity\EcommLignes;
use TMD\ProdBundle\Form\EcommLignesType;
use TMD\ProdBundle\Form\EcommTrackingType;
use TMD\ProdBundle\TMDProdBundle;

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
    public function transportsAction(){

        $em = $this->getDoctrine()->getManager();

        $listTarifValide = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findAllValide();
        $transporteurs = $em->getRepository('TMDAppliBundle:EcommTransporteurs')->findallTransporteur();

        dump($listTarifValide);
        dump($transporteurs);

        return $this->render('TMDCoreBundle:Core:transporteurs.html.twig', array(
            'listTarif' => $listTarifValide,
            'listTransporteurs' => $transporteurs
        ));
    }

    public function transportFormAction(){
        $em = $this->getDoctrine()->getManager();

        $transporteurs = $em->getRepository('TMDAppliBundle:EcommTransporteurs')->findallTransporteur();
        dump($transporteurs);
         $typesTransport= $em->getRepository('TMDAppliBundle:EcommTransport')->findallTransport();
        dump($typesTransport);
        return new JsonResponse(array($transporteurs,$typesTransport));
    }

    public function transportFormSuiteAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $idTransporteur = $request->get('idTransporteur');
        dump($idTransporteur);
        $typeTransport = $em->getRepository('TMDAppliBundle:EcommTransport')->findByTransporteur($idTransporteur);
        $zones = $em->getRepository('TMDCoreBundle:TransporteursZones')->zonesByTransporteurs($idTransporteur);
        $tranche = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);
         dump($typeTransport);
         dump($zones);
         dump($tranche);
        return new JsonResponse(array($typeTransport, $zones, $tranche));
    }

    public function searchTransportAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $idTransporteur = $request->get('idTransporteur');
        $idTransport = $request->get('idTransport');
        $zones = $em->getRepository('TMDCoreBundle:TransporteursZones')->zonesByTransporteurs($idTransport);
        $tranche = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);

        dump($zones);
        dump($tranche);
        return new JsonResponse(array($zones, $tranche));
    }

    public function savetarifTrAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $this->em = $em;
        $idtransporteur = $request->get('transporteur');
        $transporteur= $em->getRepository('TMDAppliBundle:EcommTransporteurs')->find($idtransporteur);
        $idtypeTransport = $request->get('typeTransport');
        $typeTransport = $em->getRepository('TMDAppliBundle:EcommTransport')->find($idtypeTransport);
        $idzone = (int)$request->get('zone');
        $zone = $em->getRepository('TMDCoreBundle:TransporteursZones')->find($idzone);
        $tarif = (float)$request->get('tarif');
        $idtranche = $request->get('tranche');
        $tranche = $em->getRepository('TMDCoreBundle:TransporteursTranche')->find($idtranche);
        dump($transporteur);
        dump($tranche);
        dump($zone);
        dump($tarif);
        dump($typeTransport);
        $message = 'ajout effectué avec succès';
        $ancienTarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findSame($idtransporteur, $idtranche, $idzone, $idtypeTransport);
        dump($ancienTarif);
        if ($ancienTarif != []){
            foreach ($ancienTarif as $tarif){
                $tarif->setIsValid(false);
            }
        }


        $tarifTransporteur = new TransporteursTarif();
        $tarifTransporteur->setTypeTransport($typeTransport);
        $tarifTransporteur->setTransporteur($transporteur);
        $tarifTransporteur->setIdTransportTranches($tranche);
        $tarifTransporteur->setidZone($zone);
        $tarifTransporteur->setTarif($tarif);
        $tarifTransporteur->setDateDebut();
        $tarifTransporteur->setIsValid(true);
        $this->em->persist($tarifTransporteur);
        $this->em->flush();

        return new JsonResponse($message);
    }

    public function saveTransportMasseAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $this->em = $em;
        $idTransporteur = $request->get('idTransporteur');
        $transporteur= $em->getRepository('TMDAppliBundle:EcommTransporteurs')->find($idTransporteur);
        $idTransport = $request->get('idTransport');
        $transport = $em->getRepository('TMDAppliBundle:EcommTransport')->find($idTransport);
        $zones = $request->get('zones');
        $tranches = $request->get('tranches');
        $tarifs = $request->get('tarifs');
        $tarifsTranche = array_chunk($tarifs, count($zones));
        $date = $request->get('date');
        $dateFormat = DateTime::createFromFormat('d/m/Y',$date);

        dump($tarifsTranche);
        dump($idTransporteur);
        dump($idTransport);
        dump($zones);
        dump($tranches);
        dump($tarifs);
        dump($dateFormat);

        for ($i = 0; $i < count($tranches); $i++){
            for ($t =0; $t<count($zones);$t++){
                $tranche= $em->getRepository('TMDCoreBundle:TransporteursTranche')->find($tranches[$i]['idTransportTranches']);
                $zone = $em->getRepository('TMDCoreBundle:TransporteursZones')->find($zones[$t]['idTransporteursZones']);
                $ancienTarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findSame($idTransporteur, $tranches[$i]['idTransportTranches'],$zones[$t]['idTransporteursZones'], $idTransport);
                dump($tranche);
                dump($zone);
                dump($ancienTarif);
                if ($ancienTarif != [])
                {
                    foreach ($ancienTarif as $onetarif){
                        $onetarif->setValide(false);
                    }

                }


                $tarifsTransporteur = new TransporteursTarif();
                $tarifsTransporteur->setTransporteur($transporteur);
                $tarifsTransporteur->setTypeTransport($transport);
                $tarifsTransporteur->setIdTransportTranches($tranche);
                $tarifsTransporteur->setTarif($tarifsTranche[$i][$t]);
                $tarifsTransporteur->setidZone($zone);
                $tarifsTransporteur->setDateDebut($dateFormat);
                $tarifsTransporteur->getIsValid(true);
                $this->em->persist($tarifsTransporteur);
                $this->em->flush();
            }
            }




        $message = 'opération effectuée avec succès';
        return new JsonResponse($message);
    }

    public function searchClientsAction(){
        $em = $this->getDoctrine()->getManager();

        $listClients = $em->getRepository('TMDProdBundle:EcommAppli')->findClientWithOperation();
        dump($listClients);

        return new JsonResponse(array($listClients));
    }
}
