<?php

namespace TMD\AppliBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\SimpleXMLElement;
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
                    if (sizeof($cheminImage . $v->getNomimg()) > 0  and $cheminImage . $v->getNomimg() != "") {
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
            }elseif($etat === '0v') {
                $messageApresScan = "La commande N° " . $bl . " est en attente de production";
            }elseif($etat === '2') {
                $messageApresScan = "La commande N° " . $bl . " a été mise à jour et ZPL inexistant on non genéré";
            }elseif($etat === '2.0') {
                $messageApresScan = "La commande N° " . $bl . " est en attente de production et ZPL inexistant on non genéré";
            }elseif($etat === '5') {
                $messageApresScan = "Probléme d'enregistrement ... Veuillez recommencer !";
            }
            else{
                $messageApresScan = "Probleme de reseau !";
            }








            return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
                'etat'          =>$etat,
                'apresScan'     =>$messageApresScan,
                'blCmd'         =>$Cmd,
                'logoAppli'     =>$CmdAppliImage,
                'articles'      =>$articleArray,
                'button'        =>$button,
                'histo'         =>$histo,
                'dateDepotN'    =>$dateDepot,
                'verif'         =>$verif
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
            'dateDepotN'    =>$dateDepot,
            'verif'         =>$verif

        ));

    }

    public function testColAction()
    {

        return $this->render('TMDAppliBundle:Appli:colissimo.html.twig', array(

        ));
    }

    public function colissimoAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {

            define("SERVER_NAME", 'https://ws.colissimo.fr'); //TODO : Change server name
            define("LABEL_FOLDER",'./labels/'); //TODO : Change OutPut Folder: this is where the label will be saved

//Build the input request : adapt parameters according to your parcel info and options
$requestParameter = array(
    'contractNumber' => '923108', //TODO : Change contractNumber
    'password' => 'dhu48/y!', //TODO : Change password
    'outputFormat' => array(
        'outputPrintingType' => 'ZPL_10x15_203dpi'
    ),
    'letter' => array(
        'service' => array(
            'productCode' => 'DOM',
            'depositDate' => '2017-04-30' //TODO : Change depositDate (must be at least equal to current date)
                                ),
                        'parcel' => array(
                'weight' => '3',
            ),
                        'sender' => array(
                'address' => array(
                    'companyName' => 'companyName',
                    'line2' => 'main address',
                    'countryCode' => 'FR',
                    'city' => 'Paris',
                    'zipCode' => '75007'
                )
            ),
                        'addressee' => array(
                'address' => array(
                    'lastName' => 'lastName',
                    'firstName' => 'firstName',
                    'line2' => 'main address',
                    'countryCode' => 'FR',
                    'city' => 'Paris',
                    'zipCode' => '75017'
                )
            )
                        )
                    );

            function array_to_xml($soapRequest, $soapRequestXml) {
                foreach($soapRequest as $key => $value) {
                    if(is_array($value)) {
                        if(!is_numeric($key)){
                            $subnode = $soapRequestXml->addChild("$key");
                            array_to_xml($value, $subnode);
                        }
                        else{
                            $subnode = $soapRequestXml->addChild("item$key");
                            array_to_xml($value, $subnode);
                        }
                    }
                    else {
                        $soapRequestXml->addChild("$key",htmlspecialchars("$value"));
                    }
                }
            }


        //+ Generate SOAPRequest
        $xml = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" />');
        $xml->addChild("soapenv:Header");
        $children = $xml->addChild("soapenv:Body");
        $children = $children->addChild("sls:generateLabel", null, 'http://sls.ws.coliposte.fr');
        $children = $children->addChild("generateLabelRequest", null, "");
        array_to_xml($requestParameter,$children);

        $requestSoap = $xml->asXML();
        dump($requestSoap);
        //- Generate SOAPRequest

        //+ Call Web Service

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            try {
                    $resp = new \SoapClient ( 'https://ws.colissimo.fr/sls-ws/SlsServiceWS?wsdl', $options);
        $response = $resp->__doRequest ( $requestSoap, SERVER_NAME .'/sls-ws/SlsServiceWS', 'generateLabel', '2.0', 1 );
            } catch (\Exception $e) {
                var_dump($e->getMessage(), die());
            }






            return new $response;
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }
}
