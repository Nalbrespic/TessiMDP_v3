<?php

namespace TMD\AppliBundle\Controller;

use BeSimple\SoapBundle\BeSimpleSoapBundle;
use BeSimple\SoapBundle\Controller\SoapWebServiceController;
use BeSimple\SoapClient\SoapClient;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\SimpleXMLElement;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TMD\AppliBundle\Entity\NTracking;
use TMD\AppliBundle\Form\NTrackingType;
use TMD\ProdBundle\Entity\EcommBl;
use TMD\ProdBundle\Entity\EcommCmdep;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\TMDProdBundle;

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
                if (count($retourIfexistCont) > 0) {
                    $request->getSession()->getFlashBag()->add('ErrorRecepCartesContenant', $retourIfexistCont);

                    return $this->redirectToRoute('tmd_appli_cartepage', array(
                        'idClient' => $idClient
                    ));
                }


                $retourIfexistPlage = $em->getRepository('TMDAppliBundle:NTracking')->findCorrespondancePlage($plageDebut, $idClient);
                $retourIfexistPlage2 = $em->getRepository('TMDAppliBundle:NTracking')->findCorrespondancePlage($plageFin, $idClient);
                if (count($retourIfexistPlage) > 0 or count($retourIfexistPlage2) > 0) {
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

    public function editCodeHeinekenAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $bl = $request->get('numBL');
            $tabCode = $request->get('tabCode');
            $em = $this->getDoctrine()->getManager();
//dump($tabCode);
            $cmdps = $em->getRepository('TMDProdBundle:EcommCmdep')->findBy(array('numbl' => $bl , 'codearticle' => '0001' ));
//dump($cmdps);
            foreach ($cmdps as $key=>$val){
                if (isset($tabCode[$key]))
                $val->setPerso2($tabCode[$key]);
            }
            $em->flush();

                return new JsonResponse(array('OK VERIF'));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function editStatutAction($bl, $statut, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($statut != 0 ) {
            $Cmd = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl));

            $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $bl, 'idstatut' => $statut));

            if (count($Cmd) == 0){
                $request->getSession()->getFlashBag()->add('PbBlexistepas', $bl);
                return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                    'statut' => $statut

                ));
            }


            if (count($histo) > 0){
                $request->getSession()->getFlashBag()->add('PbStatutidentique', $Cmd->getBl()->getNumbl());
                return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                    'statut' => $statut

                ));
            }
            if ( $statut === '19'){
                $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $bl, 'idstatut' => '18'));
                if (count($histo) == 0 ){
                    $request->getSession()->getFlashBag()->add('PbStatutsupp', $Cmd->getBl()->getNumbl());
                    return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                        'statut' => $statut
                    ));
                }
            }

            if ( $statut === '2'){
                $histo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $bl, 'idstatut' => '19'));
                if (count($histo) == 0 ){
                    $request->getSession()->getFlashBag()->add('PbStatutsupp', $Cmd->getBl()->getNumbl());
                    return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                        'statut' => $statut
                    ));
                }
            }


////////////////Mis sous pli
            if ($statut == 18) {
                $user = $this->getUser();

                $jouristo = new EcommHistoStatut();
                $jouristo->setDatestatut(new \DateTime());
                $jouristo->setIdstatut(18);
                $jouristo->setObservation("Mis sous pli");
                $jouristo->setNumbl($Cmd->getBl()->getNumbl());
                $jouristo->setIduser($user->getId());

                $em->persist($jouristo);

                $em->flush();

                $updateHistoOK = false;

                $break = 0;
                while (!$updateHistoOK and $break < 3) {
                    $jouristoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $Cmd->getBl()->getNumbl(), 'idstatut' => '11'));
                    if (count($jouristoVerif) == 0) {
                        $break++;
                        $em->persist($jouristo);
                        $em->flush();
                    } else {
                        $updateHistoOK = true;
                        break;
                    }
                }
                if (!$updateHistoOK){
                    $request->getSession()->getFlashBag()->add('NOConfirmMissouspli', $Cmd->getBl()->getNumbl());

                    return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                        'statut' => $statut
                    ));
                }

                $request->getSession()->getFlashBag()->add('ConfirmMissouspli', $Cmd->getBl()->getNumbl());
            }
////////////////Assemblage
            if ($statut == 19) {
                $user = $this->getUser();

                $jouristo = new EcommHistoStatut();
                $jouristo->setDatestatut(new \DateTime());
                $jouristo->setIdstatut(19);
                $jouristo->setObservation("Collage");
                $jouristo->setNumbl($Cmd->getBl()->getNumbl());
                $jouristo->setIduser($user->getId());

                $dateProdAverif = new \DateTime();
                $Cmd->setDateProduction($dateProdAverif);

                $em->persist($jouristo);

                $em->flush();

                $updateHistoOK = false;
                $updateProdOK = false;

                $break = 0;
                while (!$updateHistoOK and $break < 3) {
                    $jouristoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $Cmd->getBl()->getNumbl(), 'idstatut' => '12'));
                    if (count($jouristoVerif) == 0) {
                        $break++;
                        $em->persist($jouristo);
                        $em->flush();
                    } else {
                        $updateHistoOK = true;
                        break;
                    }
                }
                $break3 = 0;
                while (!$updateProdOK and $break3 < 3) {
                    $dateProdVerif = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $Cmd->getBl()->getNumbl(), 'dateProduction' => $dateProdAverif));
                    if (count($dateProdVerif) == 0) {
                        $break3++;
                        $Cmd->setDateProduction($dateProdAverif);
                        $em->flush();
                    } else {
                        $updateProdOK = true;
                        break;
                    }
                }
                if (!$updateHistoOK OR !$updateProdOK){
                    $request->getSession()->getFlashBag()->add('NOConfirmAssemblage', $Cmd->getBl()->getNumbl());

                    return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                        'statut' => $statut
                    ));
                }

                $request->getSession()->getFlashBag()->add('ConfirmAssemblage', $Cmd->getBl()->getNumbl());
            }

////////////////Validation Commande
            if ($statut == 2) {
                $user = $this->getUser();

                $jouristo = new EcommHistoStatut();
                $jouristo->setDatestatut(new \DateTime());
                $jouristo->setIdstatut(2);
                $jouristo->setObservation("Expédié");
                $jouristo->setNumbl($Cmd->getBl()->getNumbl());
                $jouristo->setIduser($user->getId());

                $dateProdAverif = new \DateTime();
                $Cmd->getBl()->getNumligne()->setDateDepot($dateProdAverif);


                $em->persist($jouristo);
                $em->flush();

                $updateHistoOK = false;
                $updateDateDepotOK = false;

                $break = 0;
                while (!$updateHistoOK and $break < 3) {
                    $jouristoVerif = $em->getRepository('TMDProdBundle:EcommHistoStatut')->findOneBy(array('numbl' => $Cmd->getBl()->getNumbl(), 'idstatut' => '2'));
                    if (count($jouristoVerif) == 0) {
                        $break++;
                        $em->persist($jouristo);
                        $em->flush();
                    } else {
                        $updateHistoOK = true;
                        break;
                    }
                }
                $break2 = 0;
                while (!$updateDateDepotOK and $break2 < 3) {
                    $dateVerif = $em->getRepository('TMDProdBundle:EcommTracking')->findOneBy(array('expRef' => $Cmd->getBl()->getNumligne()->getExpRef(), 'dateDepot' => DateTime::createFromFormat('Y-m-d', $dateProdAverif->format('Y-m-d')) ));
                    if (count($dateVerif) == 0) {
                        $break2++;
                        $Cmd->getBl()->getNumligne()->setDateDepot($dateProdAverif);
                        $em->flush();
                    } else {
                        $updateDateDepotOK = true;
                        break;
                    }
                }


                if (!$updateHistoOK  OR !$updateDateDepotOK){
                    $request->getSession()->getFlashBag()->add('NOConfirmValidationcommande', $Cmd->getBl()->getNumbl());

                    return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                        'statut' => $statut
                    ));
                }

                $request->getSession()->getFlashBag()->add('ConfirmValidationcommande', $Cmd->getBl()->getNumbl());
            }

            return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
                'statut' => $statut
            ));
        }

        return $this->render('TMDAppliBundle:Appli:editStatut.html.twig', array(
            'statut' => $statut

        ));

    }

    public function fichierAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbBlforBordreau = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlForBordereau();

        return $this->render('TMDAppliBundle:Appli:fichier.html.twig', array(
            'bls'  => $nbBlforBordreau

        ));
    }

    public function traitementFichierAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('carteLot');
            $em = $this->getDoctrine()->getManager();

            $lotCarte = $em->getRepository('TMDAppliBundle:NTracking')->findOneBy(array('numplage' => $id ));


            return new JsonResponse(array('OK MODIF'));

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


    public function productionAction($bl, $etat, $dateDepot, $verif, $vue, $error, $appAverif)
    {
        $em = $this->getDoctrine()->getManager();
        $button = true;

        if ($etat != 'nc' and $etat != '3' ) {
            if ($etat != '9'){
                $Cmd = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl));

                if ($Cmd->getBl()->getNumligne()->getTypeProduction() == 1 or
                    $Cmd->getBl()->getNumligne()->getTypeProduction() == 3 or
                    $Cmd->getBl()->getNumligne()->getTypeProduction() == 4 or
                    $Cmd->getBl()->getNumligne()->getTypeProduction() == 6 or
                    $Cmd->getBl()->getNumligne()->getTypeProduction() == 11)
                {
                    $button = false;
                }
    //            $NumHeineken = $em->getRepository('TMDProdBundle:EcommTempHeineken')->findallCodeUnused();

                if ($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage() != null) {
                    $CmdAppliImage['appliImage'] = (base64_encode(stream_get_contents($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage())));
                } else {
                    $CmdAppliImage['appliImage'] = '';
                }

                $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findBy(array('numbl' => $bl));

                $idClient = $articles[0]->getIdclient();
                $cheminImage = $Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getDossierimg();
                $env = $this->container->get('kernel')->getEnvironment();
                if ($env != 'dev'){
                    $chemeinImageMod = str_replace('\\standard$\\','/standard/',$cheminImage);
                    $chemeinImageMod1 = str_replace('\\','/',$chemeinImageMod);
                }else{
                    $chemeinImageMod1 = $cheminImage;
                }

                $articleArray = array();
                $articlesAverif = array();
                $indexeArtVerif = 0;
                foreach ($articles as $k=>$v)
                {
                    if (!$v->isFlagart()) {
                        $articleArray['art'][$k]['Image'] = null;
                        if (substr($v->getCodearticle(),0,6) == '370072'){
                            $articleArray['art'][$k]['artAverif']['codeArticle'] = $v->getCodearticle();
                         }
                        $articleArray['art'][$k]['id'] = $v->getNumero();
                        $articleArray['art'][$k]['perso1'] = $v->getPerso1();
                        $articleArray['art'][$k]['quantite'] = $v->getQuantite();
                        $articleArray['art'][$k]['perso2'] = $v->getPerso2();
                        $articleArray['art'][$k]['codeArticle']=$v->getCodearticle();
                        $articleArray['art'][$k]['libelle'] = $v->getLibelle();
                        if ($v->getQuantite() > 1){
                            for ($u = 1 ; $u <= $v->getQuantite() ; $u++){
                                $articlesAverif[$indexeArtVerif]['codeAverif'] = $v->getNumTrack();
                                $articlesAverif[$indexeArtVerif]['id'] = $v->getNumero()."-".$u;
                                $articlesAverif[$indexeArtVerif]['indexVerif'] = 0;
                                $indexeArtVerif++;
                            }

                        }else{
                            $articlesAverif[$indexeArtVerif]['codeAverif'] = $v->getNumTrack();
                            $articlesAverif[$indexeArtVerif]['id'] = $v->getNumero()."-1";
                            $articlesAverif[$indexeArtVerif]['indexVerif'] = 0;
                            $indexeArtVerif++;
                        }

                        if (strlen($cheminImage . $v->getNomimg()) > 0  and $cheminImage . $v->getNomimg() != "") {
                            if ( file_exists($chemeinImageMod1 . $v->getNomimg())) {
                                $articleArray['art'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                                $dimensions = getimagesizefromstring(file_get_contents($chemeinImageMod1.$v->getNomimg()));
                                $articleArray['art'][$k]['dimensionL'] = $dimensions[0];
                                $articleArray['art'][$k]['dimensionH'] = $dimensions[1];
                            }
                        }

                        $articleArray['art'][$k]['nameImage'] = $v->getNomimg();
                    }
                    else{
                        if (strlen($cheminImage . $v->getNomimg()) > 0 and $cheminImage . $v->getNomimg() != ""  ) {
                            if ( file_exists($chemeinImageMod1 . $v->getNomimg())){
                                    $articleArray['emb'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                            }
                        }
                        $articleArray['emb'][$k]['libelle'] = $v->getLibelle();
                    }
                }
                $countArticle = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bl);
                $articleArray['countArticle'] = $countArticle;
                $ouvrirCarton = false;
                foreach ($articleArray['art'] as $art){
                    if ( $art['codeArticle'] == "21LP08" or $art['codeArticle'] == "21LP09") {
                        $ouvrirCarton = true;
                    }
                }
                $jouristo = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBl($bl);

                if ($etat === '1') {
                    $messageApresScan = "Commande N° " . $bl . " déjà produite !";
    //                $button = true;
                }elseif($etat === '0') {
                    $messageApresScan = "La commande N° " . $bl . " a été mise à jour";
                }elseif($etat === '0v') {
                    $messageApresScan = "La commande N° " . $bl . " est en attente de production";
                }elseif($etat === '0code') {
                    $messageApresScan = "La commande N° " . $bl . " est en attente de rapprochement";
                }
                elseif($etat === '2') {
                    $messageApresScan = "La commande N° " . $bl . " est en attente de production - ZPL inexistant";
                }
//                elseif($etat === '9'){
//                $messageApresScan = "La commande N° " . $bl . " a été annulée !!";
//                }
                elseif($etat === '2.0') {
                    $messageApresScan = "La commande N° " . $bl . " est en attente de production et ZPL inexistant";
                }elseif($etat === '5') {
                    $messageApresScan = "Probléme d'enregistrement ... Veuillez recommencer !";
                }elseif($etat === '4') {
                    $messageApresScan = " ZPL inexistant on non genéré";
                }elseif($etat === '7') {
                    $messageApresScan = "Impossible de generer l'etiquette transport";
                }
                else{
                    $messageApresScan = "Probleme de reseau !";
                }

                if ($dateDepot == 0){
                    $dateDepot = null;
                }

                return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
                    'etat'          =>$etat,
                    'apresScan'     =>$messageApresScan,
                    'blCmd'         =>$Cmd,
                    'logoAppli'     =>$CmdAppliImage,
                    'articles'      =>$articleArray,
                    'articlesAverif' => $articlesAverif,
                    'button'        =>$button,
                    'histo'         =>$jouristo,
                    'dateDepotN'    =>$dateDepot,
                    'verif'         =>$verif,
                    'vue'           =>$vue,
                    'error'         =>$error,
                    'appAverif'     =>$appAverif,
                    'ouvrirCarton'  =>$ouvrirCarton,
                    'idclient'      =>$idClient,
    //                'numHeineken'   => $NumHeineken
                ));
            }
        }

        if ($etat == '9') {
            $messageApresScan = "La commande a été annulée !";
        }else {
            $messageApresScan = "La commande N° " . $bl . " n'existe pas !";
        }
        if ($dateDepot == 0){
            $dateDepot = null;
        }

        $articlesAverif = null;
        return $this->render('TMDAppliBundle:Appli:production.html.twig', array(
            'etat'          =>$etat,
            'apresScan'     =>$messageApresScan,
            'dateDepotN'    =>$dateDepot,
            'verif'         =>$verif,
            'vue'           =>$vue,
            'error'         =>$error,
            'appAverif'     =>$appAverif,
            'articlesAverif' => $articlesAverif,
        ));
    }

    public function consultSejerAction(Request $request){
        $bl = $request->get('bl');

        if ($bl !="") {
            $Cmd = $this->getDoctrine()->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl));
            if ($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage() != null) {
                $CmdAppliImage['appliImage'] = (base64_encode(stream_get_contents($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage())));
            } else {
                $CmdAppliImage['appliImage'] = '';
            }

            $articles = $this->getDoctrine()->getRepository('TMDProdBundle:EcommCmdep')->findBy(array('numbl' => $bl));

            $idClient = $articles[0]->getIdclient();
            $cheminImage = $Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getDossierimg();
            $env = $this->container->get('kernel')->getEnvironment();
            if ($env != 'dev'){
                $chemeinImageMod = str_replace('\\standard$\\','/standard/',$cheminImage);
                $chemeinImageMod1 = str_replace('\\','/',$chemeinImageMod);
            }else{
                $chemeinImageMod1 = $cheminImage;
            }
            if ($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage() != null) {
                $CmdAppliImage['appliImage'] = (base64_encode(stream_get_contents($Cmd->getBl()->getNumligne()->getIdfile()->getIdappli()->getAppliImage())));
            } else {
                $CmdAppliImage['appliImage'] = '';
            }
            $articleArray = array();
            $articlesAverif = array();
            $indexeArtVerif = 0;
            foreach ($articles as $k=>$v)
            {
                if (!$v->isFlagart()) {
                    $articleArray['art'][$k]['Image'] = null;
                    if (substr($v->getCodearticle(),0,6) == '370072'){
                        $articleArray['art'][$k]['artAverif']['codeArticle'] = $v->getCodearticle();
                    }
                    $articleArray['art'][$k]['id'] = $v->getNumero();
                    $articleArray['art'][$k]['perso1'] = $v->getPerso1();
                    $articleArray['art'][$k]['quantite'] = $v->getQuantite();
                    $articleArray['art'][$k]['perso2'] = $v->getPerso2();
                    $articleArray['art'][$k]['codeArticle']=$v->getCodearticle();
                    $articleArray['art'][$k]['libelle'] = $v->getLibelle();
                    if ($v->getQuantite() > 1){
                        for ($u = 1 ; $u <= $v->getQuantite() ; $u++){
                            $articlesAverif[$indexeArtVerif]['codeAverif'] = $v->getNumTrack();
                            $articlesAverif[$indexeArtVerif]['id'] = $v->getNumero()."-".$u;
                            $articlesAverif[$indexeArtVerif]['indexVerif'] = 0;
                            $indexeArtVerif++;
                        }

                    }else{
                        $articlesAverif[$indexeArtVerif]['codeAverif'] = $v->getNumTrack();
                        $articlesAverif[$indexeArtVerif]['id'] = $v->getNumero()."-1";
                        $articlesAverif[$indexeArtVerif]['indexVerif'] = 0;
                        $indexeArtVerif++;
                    }

                    if (strlen($cheminImage . $v->getNomimg()) > 0  and $cheminImage . $v->getNomimg() != "") {
                        if ( file_exists($chemeinImageMod1 . $v->getNomimg())) {
                            $articleArray['art'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                            $dimensions = getimagesizefromstring(file_get_contents($chemeinImageMod1.$v->getNomimg()));
                            $articleArray['art'][$k]['dimensionL'] = $dimensions[0];
                            $articleArray['art'][$k]['dimensionH'] = $dimensions[1];
                        }
                    }

                    $articleArray['art'][$k]['nameImage'] = $v->getNomimg();
                }
                else{
                    if (strlen($cheminImage . $v->getNomimg()) > 0 and $cheminImage . $v->getNomimg() != ""  ) {
                        if ( file_exists($chemeinImageMod1 . $v->getNomimg())){
                            $articleArray['emb'][$k]['Image'] = (base64_encode(file_get_contents($chemeinImageMod1 . $v->getNomimg())));
                        }
                    }
                    $articleArray['emb'][$k]['libelle'] = $v->getLibelle();
                }
            }
            $countArticle = $this->getDoctrine()->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bl);
            $articleArray['countArticle'] = $countArticle;
            dump($articleArray);
            $ouvrirCarton = false;
            foreach ($articleArray['art'] as $art){
                if ( $art['codeArticle'] == "21LP08" or $art['codeArticle'] == "21LP09") {
                    $ouvrirCarton = true;
                }
            }
        }

        if ($bl != "") {
            return $this->render('TMDAppliBundle:Appli:pluginSejer.html.twig', array(
                'bl' => $bl,
                'listArticle' => $articleArray['art'],
                'countArticle'=> $articleArray['countArticle'],
                'ouvrirCarton' => $ouvrirCarton,
                'logoAppli'=>$CmdAppliImage,
            ));
        }else{
            return $this->render('TMDAppliBundle:Appli:pluginSejer.html.twig', array(
                'bl' => $bl,

            ));
        }
    }
    public function verifArticleAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $cab = $request->get('cab');
            $numero = $request->get('cmdp');
            $em = $this->getDoctrine()->getManager();

            $article = $em->getRepository('TMDProdBundle:EcommCmdep')->findOneBy(array('numero' => $numero ));

            if ($article->getCodearticle() == $cab){

                return new JsonResponse(array('OK VERIF'));
            }
            else{
                return new JsonResponse(array('pas bon'));
            }
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function insertNumHeineken(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $cab = $request->get('cab');
            $numero = $request->get('cmdp');
            $em = $this->getDoctrine()->getManager();

            $article = $em->getRepository('TMDProdBundle:EcommCmdep')->findOneBy(array('numero' => $numero ));

            if ($article->getCodearticle() == $cab){

                return new JsonResponse(array('OK VERIF'));
            }
            else{
                return new JsonResponse(array('pas bon'));
            }
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function electionChoixDepAction(){

        return $this->render('TMDAppliBundle:Appli:electionChoixDep.html.twig', array(

        ));
    }

    public function verifElectionAction($statut , $jour, $dep)
    {

        $dateJour = ['2021-05-10',
            '2021-05-11 ',
            '2021-05-12 ',
            '2021-05-14 ',
            '2021-05-15 ',
            '2021-05-17 ',
            '2021-05-18 ',
            '2021-05-19 ',
            '2021-05-21 ',
            '2021-05-25 ',
            '2021-05-26 ',
            '2021-05-27 ',

            ];
        $joureureInterro = ['06:00:00',
            '07:00:00',
            '08:00:00',
            '09:00:00',
            '10:00:00',
            '11:00:00',
            '12:00:00',
            '13:00:00',
            '14:00:00',
            '15:00:00',
            '16:00:00',
            '17:00:00',
            '18:00:00',
            '19:00:00',
            '20:00:00',
            '21:00:00'];
        
        $em = $this->getDoctrine()->getManager();


            if ($dep == 2) {
                $Files = $em->getRepository('TMDProdBundle:EcommFiles')->idfileByOperation(1832);
            } elseif ($dep == 10){
                $Files = $em->getRepository('TMDProdBundle:EcommFiles')->idfileByOperation(1833);
            } else{
                $Files = $em->getRepository('TMDProdBundle:EcommFiles')->idfileByOperation(1831);
            }

            $listFiles ="";
            foreach ($Files as $file){
                foreach ($file as $key => $v)
               $listFiles .= $v.', ';
            }
            $listFiles = substr($listFiles,0, -2);
            dump($listFiles);


            $nbKubTotal = $em->getRepository('TMDProdBundle:EcommBl')->findnbKubTotal(1,$listFiles);
            $nbElecteurTotal = $em->getRepository('TMDProdBundle:EcommBl')->findnbElecteurTotal(1,$listFiles);

            $nbKubByStatut = $em->getRepository('TMDProdBundle:EcommBl')->findnbKubTotalJ($statut, $dateJour[$jour],$listFiles);
            $nbElecteurByStatut = $em->getRepository('TMDProdBundle:EcommBl')->findnbElecteurTotalJ($statut, $dateJour[$jour],$listFiles);

            $pieChart = new PieChart();
            $pieChart->getData()->setArrayToDataTable(
                [
                    ['Etat', 'Mise sous pli'],
                    ['MSP', count($nbKubByStatut)],
                    ['Reste à produire', count($nbKubTotal)-count($nbKubByStatut)]

                ]
            );
            $pieChart->getOptions()->setPieSliceText('label');
            //        $pieChart->getOptions()->setTitle('Par KUB');
            $pieChart->getOptions()->setPieStartAngle(0);
            $pieChart->getOptions()->setHeight(180);
            $pieChart->getOptions()->setWidth(350);
            $pieChart->getOptions()->getLegend()->setPosition('none');


            $pieChart2 = new PieChart();
            $pieChart2->getData()->setArrayToDataTable(
                [
                    ['Etat', 'Mise sous pli'],
                    ['MSP', intval($nbElecteurByStatut[0][1])],
                    ['Reste à produire', intval($nbElecteurTotal[0][1])-intval($nbElecteurByStatut[0][1])]

                ]
            );
            $pieChart2->getOptions()->setPieSliceText('label');
            //        $pieChart2->getOptions()->setTitle('Par électeur');
            $pieChart2->getOptions()->setPieStartAngle(0);
            $pieChart2->getOptions()->setHeight(180);
            $pieChart2->getOptions()->setWidth(350);
            $pieChart2->getOptions()->getLegend()->setPosition('none');

                for ($j = 0; $j < count($joureureInterro); $j++) {
                    $nbKubByStatut[$j] = $em->getRepository('TMDProdBundle:EcommBl')->findnbKubbyHour($statut, $dateJour[$jour] . ' 00:00:00', $dateJour[$jour] . ' ' . $joureureInterro[$j], $listFiles);
                    $nbElecteurByStatut[$j] = $em->getRepository('TMDProdBundle:EcommBl')->findnbElecteurbyHour($statut, $dateJour[$jour] . ' 00:00:00', $dateJour[$jour] . ' ' . $joureureInterro[$j],$listFiles);
                }

//                dump( date_format(new DateTime(),"d" ));
//                dump(intval(date_format(new DateTime(),"d" )));

                $tabLine=[];
                for ($j = 0; $j < count($joureureInterro); $j++) {
                    if ($j == 0) {
                        array_push($tabLine, ['', 'Nombre de plis', 'Nombre de KUB']);
                    }
                    if (intval(substr($joureureInterro[$j],0,2)) <= intval(date_format(new DateTime(),"H")) or
                            intval(substr($dateJour[$jour],8,2)) < intval(date_format(new DateTime(),"d" ))){
                        array_push($tabLine, [new DateTime($joureureInterro[$j]), intval($nbElecteurByStatut[$j][0][1]), count($nbKubByStatut[$j])]);
                    }else{
                        array_push($tabLine, [new DateTime($joureureInterro[$j]), 'none', 'none']);
                    }
                }
        $tabChart=[];
        $nbKubByStatut[-1][0][1]=null;
        $nbElecteurByStatut[-1][0][1]=null;
        for ($j = 0; $j < count($joureureInterro); $j++) {
            if ($j == 0) {
                array_push($tabChart, ['', 'Nombre de plis', 'Nombre de KUB']);
            }
            if (intval(substr($joureureInterro[$j],0,2)) <= intval(date_format(new DateTime(),"H")) or
                intval(substr($dateJour[$jour],8,2)) < intval(date_format(new DateTime(),"d" ))){
                array_push($tabChart, [new DateTime($joureureInterro[$j]),intval($nbElecteurByStatut[$j][0][1])-intval($nbElecteurByStatut[$j-1][0][1]), count($nbKubByStatut[$j])-count($nbKubByStatut[$j-1])]);
            }else{
                array_push($tabChart, [new DateTime($joureureInterro[$j]), 'none', 'none']);
            }
        }

                $line = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
                $line->getData()->setArrayToDataTable(
                     $tabLine
                );
                //        $line->getOptions()->getChart()
                //            ->setTitle('Average Temperatures and Daylight in Iceland Throughout the Year');
                $line->getOptions()
                    ->setHeight(150)
                    ->setWidth(800)
                    ->setSeries([['axis' => 'Nombre de plis'], ['axis' => 'Nombre de KUB']])
                    ->setAxes(['y' => ['Nombre de plis' => ['label' => 'Nombre de plis'], 'Nombre de KUB' => ['label' => 'Nombre de KUB']]]);

                $line->getOptions()
                    ->getHAxis()->setFormat('H:mm');

                $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
                $chart->getData()->setArrayToDataTable(
                    $tabChart
                );

//                $chart->getOptions()->getChart()
//                    ->setTitle('Production journalier')
//                    ->setSubtitle('Sales, Expenses, and Profit: 2014-2017');
                $chart->getOptions()
                    ->setBars('vertical')
                    ->setHeight(150)
                    ->setWidth(800)
                    ->setColors(['#3366CC','#DC3912'])
                    ->setSeries([['axis' => 'Nombre de plis'], ['axis' => 'Nombre de KUB']])
                    ->setAxes(['y' => ['Nombre de plis' => ['label' => 'Nombre de plis'], 'Nombre de KUB' => ['label' => 'Nombre de KUB']]]);

                $chart->getOptions()
                    ->getHAxis()->setFormat('H:mm');

        return $this->render('TMDAppliBundle:Appli:election.html.twig', array(
            'statut' => $statut,
            'jour' => $jour,
            'dep'=> $dep,
            'piechart' => $pieChart,
            'piechart2' => $pieChart2,
            'line' => $line,
            'chart' => $chart,
            'listJour' => $dateJour,
            ));
    }

    public function colissimoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbBlforBordreau = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlForBordereau();

        return $this->render('TMDAppliBundle:Appli:colissimo.html.twig', array(
            'bls'  => $nbBlforBordreau
        ));
    }

    public function colissimoInfoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbBlforBordreau = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlForBordereau();

        return $this->render('TMDAppliBundle:Appli:Infocolissimo.html.twig', array(
            'bls'  => $nbBlforBordreau
        ));
    }
}
