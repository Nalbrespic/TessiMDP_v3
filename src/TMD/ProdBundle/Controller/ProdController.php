<?php

namespace TMD\ProdBundle\Controller;

use DateTime;

use Dompdf\Dompdf;

use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use TMD\ProdBundle\Entity\EcommAppli;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\Entity\EcommStatut;
use TMD\ProdBundle\Form\EcommAppliType;
use XLSXWriter;

class ProdController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

        return $this->render('TMDProdBundle:Prod:index.html.twig', array(
        ));
    }


    public function addApplicationAction(Request $request){

        $appli = new EcommAppli();
        $form = $this->get('form.factory')->create(EcommAppliType::class, $appli);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if( $form->getData()->getappliImage()) {
                $blob = $form->getData()->getappliImage()->getpathname();
                $appli->setappliImage(file_get_contents($blob));
            }

            $em->persist($appli);
            $em->flush();

            $request->getSession()->getFlashBag()->add('addApp', 'Application bien enregistrée.');

            return $this->redirectToRoute('tmd_core_homepage', array());
        }

        return $this->render('TMDProdBundle:Prod:addApplication.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    public function modifDateDepotAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $id = $request->get('idFile');
            $dateDepot = $request->get('dateDepot');
            $emanager = $this->getDoctrine()->getManager();
            $date = DateTime::createFromFormat('Y-m-d',$dateDepot );
            $allTrByFile = $emanager->getRepository('TMDProdBundle:EcommTracking')->findBlbyFileWithDepotNull($id);

            foreach ($allTrByFile as $tr){
                $tr->setDateDepot($date);
            }
//dump($allTrByFile);
            $emanager->flush();

            return new JsonResponse(array( 'ok', 'd-m-Y'));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function modifDateProductionAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('idFile');
            $dateDepot = $request->get('dateProd');
            $emanager = $this->getDoctrine()->getManager();
            $date = DateTime::createFromFormat('Y-m-d',$dateDepot );
            $allBlByFile = $emanager->getRepository('TMDProdBundle:EcommBl')->findBy(array('idfile' => $id));

            foreach ($allBlByFile as $bl){
                $bl->setDateProduction($date);
            }

            $emanager->flush();

            return new JsonResponse(array( 'OK MODIF'));
        };

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function modifDateProductionDepotTrackingAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $bl = $request->get('bl');
            $tracking = $request->get('tracking');
            $dateDepot = $request->get('dateDepot');
            $dateProd = $request->get('dateProd');
            $em = $this->getDoctrine()->getManager();

            $bl = $em->getRepository('TMDProdBundle:EcommBl')->findOneBy(array('bl' => $bl));

            $bl->setNColis($tracking);
            if ($dateDepot != '0000-00-00'){
                $date = DateTime::createFromFormat('Y-m-d',$dateDepot );
                $bl->getBl()->getNumligne()->setDateDepot($date);
            }
            if ($dateProd != '0000-00-00'){
                $date = DateTime::createFromFormat('Y-m-d',$dateProd );
                $bl->setDateProduction($date);
            }

            $em->flush();

            return new JsonResponse(array("bl" => $bl, "tracking" => $tracking, "dateProd" => $dateProd, "dateDepot" => $dateDepot));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function modifDateProductionDepotAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $file = $request->get('idfile');
            $dateDepot = $request->get('dateDepot');
            $dateProd = $request->get('dateProd');

            $em = $this->getDoctrine()->getManager();
            $allTrByFile = $em->getRepository('TMDProdBundle:EcommBl')->findBy(array('idfile' => $file));

            if ($dateDepot != '0000-00-00'){
                $date = DateTime::createFromFormat('Y-m-d',$dateDepot );
                foreach ($allTrByFile as $tr){
                    if ( date_format($tr->getBl()->getNumligne()->getDateDepot(), "Y-m-d") == '-0001-11-30'){
                        $tr->getBl()->getNumligne()->setDateDepot($date);
                    }
                }
            }
            if ($dateProd != '0000-00-00'){
                $date = DateTime::createFromFormat('Y-m-d',$dateProd );
                foreach ($allTrByFile as $tr){
                    if ( date_format($tr->getDateProduction(), "Y-m-d") == '-0001-11-30') {
                        $tr->setDateProduction($date);
                    }
                }
            }

            $em->flush();

            $name = $tr->getBl()->getNumligne()->getIdfile()->getFilename();

            return new JsonResponse(array("name" => $name, "dateProd" => $dateProd, "dateDepot" => $dateDepot));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function rechercheByCmdAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $cmd = $request->get('cmd');
            $idClient = $request->get('idClient');
            $idope = $request->get('idope');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlByCmd($cmd, $idClient, $idope);

            $tabBls=[];
            foreach ($allBlByOpe as $i){
                array_push($tabBls,$i['numbl']);
            }
            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBLsArray($tabBls);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByBLsArray($tabBls);

            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpe as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }
            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();

            foreach ($allBlByOpe as $key=>$item){
                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                }
            }

            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray, 'perso' => $articlesPersos);

            return new JsonResponse($reponse);
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function rechercheByCodeArticleAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {

            $code= $request->get('code');
            $idClient = $request->get('idClient');
            $idope = $request->get('idope');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmdandCodeArticle($code,$idClient, $idope );

            $tabBls=[];
            foreach ($allBlByOpe as $i){
                array_push($tabBls,$i['numbl']);
            }
            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBLsArray($tabBls);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByBLsArray($tabBls);

            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpe as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }

            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpe as $key=>$item){
                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                }
            }
            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray, 'perso' => $articlesPersos);

            return new JsonResponse($reponse);
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function rechercheModifAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $cmd = $request->get('cmd');
            $idClient = $request->get('idClient');
            $idope = $request->get('idope');

            $em = $this->getDoctrine()->getManager();

            $allBlByOpeBL = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmdModifb($cmd, $idClient, $idope);
            $allBlByOpeFILE = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmdModiff($cmd, $idClient, $idope);
            $allBlByOpeFILEAUpdateDepot = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmdModiffaUpdateDepot($cmd, $idClient, $idope);
            $allBlByOpeFILEAUpdateFab = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmdModiffaUpdateFab($cmd ,$idClient, $idope);

            $tabBlComplet = array();
            $tabBlComplet['file'] = array();
            $tabBlComplet['fileNoCompletedepot'] = array();
            $tabBlComplet['fileNoCompletefab'] = array();
            $tabBlComplet['bl'] = array();
            if (sizeof($allBlByOpeBL) > 0){
                foreach ($allBlByOpeBL as $val){
                    array_push($tabBlComplet['bl'],$val);
                }
            }

            if (sizeof($allBlByOpeFILE) > 0){
                foreach ($allBlByOpeFILE as $val){
                    array_push($tabBlComplet['file'],$val);
                }
            }
            if (sizeof($allBlByOpeFILEAUpdateDepot) > 0){
                foreach ($allBlByOpeFILEAUpdateDepot as $val){
                    $tabBlComplet['fileNoCompletedepot'][$val[0]['idfile']] = 1;
                }
            }
            if (sizeof($allBlByOpeFILEAUpdateFab) > 0){
                foreach ($allBlByOpeFILEAUpdateFab as $val){
                    $tabBlComplet['fileNoCompletefab'][$val[0]['idfile']] = 1;
                }
            }
            return new JsonResponse($tabBlComplet);
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function suppNameOperationAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {

            $idClient = $request->get('idClient');
            $ipOpe = $request->get('ipOpe');

            $em = $this->getDoctrine()->getManager();

            $allBLs = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlsByFile($idClient,$ipOpe);

            foreach ($allBLs as $v){
                if ($v->getBl()->getNumligne()->getDestCiv() != ""){
                    $v->getBl()->getNumligne()->setDestCiv("*******");
                }
                if ($v->getBl()->getNumligne()->getDestPrenom() != ""){
                    $v->getBl()->getNumligne()->setDestPrenom("*******");
                }
                if ($v->getBl()->getNumligne()->getDestNom() != ""){
                    $v->getBl()->getNumligne()->setDestNom("*******");
                }
                if ($v->getBl()->getNumligne()->getDestinataire() != ""){
                    $v->getBl()->getNumligne()->setDestinataire("*******");
                }
                if ($v->getBl()->getNumligne()->getDestRue() != ""){
                    $v->getBl()->getNumligne()->setDestRue("*******");
                }
                if ($v->getBl()->getNumligne()->getDestAd2() != ""){
                    $v->getBl()->getNumligne()->setDestAd2("*******");
                }
                if ($v->getBl()->getNumligne()->getDestAd3() != ""){
                    $v->getBl()->getNumligne()->setDestAd3("*******");
                }
                if ($v->getBl()->getNumligne()->getDestAd4() != ""){
                    $v->getBl()->getNumligne()->setDestAd4("*******");
                }
                if ($v->getBl()->getNumligne()->getDestAd5() != ""){
                    $v->getBl()->getNumligne()->setDestAd5("*******");
                }
                if ($v->getBl()->getNumligne()->getDestAd6() != ""){
                    $v->getBl()->getNumligne()->setDestAd6("*******");
                }
                if ($v->getBl()->getNumligne()->getDestTel() != ""){
                    $v->getBl()->getNumligne()->setDestTel("*******");
                }
                if ($v->getBl()->getNumligne()->getDestMail() != ""){
                    $v->getBl()->getNumligne()->setDestMail("*******");
                }

            }
            $em->flush();
//            dump($allBLs);
            return new JsonResponse(array("client" => $idClient, "ope" => $ipOpe));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function syntheseCokeAction(Request $request, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $artWoPSG = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokeFlagZero($date);
        $artPSG = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokePSG($date);
        $artCollector = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokeCollector($date);

        $cmdArt = array();
        foreach ($artWoPSG as $k=>$v){
            if (!$v['flagart'] and !preg_match('/Kit Noel/', $v['libelle'])) {
                if ( !preg_match('/nyle Emy L TR Ninety O/', $v['libelle'])
                    and !preg_match('/offret Plan de Pari/', $v['libelle'])
                        and !preg_match('/offret Caroussel Pa/', $v['libelle'])
                            and !preg_match('/Récla/', $v['libelle'])
                                and !preg_match('/Tee Shirt Cuisse de Grenouille/', $v['libelle'])
                                    and !preg_match('/Recla/', $v['libelle'])
                                        and !preg_match('/Kit Noel/', $v['libelle'])){

                    if ( array_key_exists($v['numbl'], $cmdArt) ) {
                        if (preg_match('/Basket Pack/', $v['libelle'])){
                            $cmdArt[$v['numbl']] = $cmdArt[$v['numbl']] + intval($v[1])*8;
                        }
                        elseif (preg_match('/ffret 6 cans Collector C/', $v['libelle'])){
                            $cmdArt[$v['numbl']] = $cmdArt[$v['numbl']] + intval($v[1])*6;
                        }
                        else{
                            $cmdArt[$v['numbl']] = $cmdArt[$v['numbl']] + intval($v[1]);
                        }

                    } else {
                        if (preg_match('/Basket Pack/', $v['libelle'])){
                            $cmdArt[$v['numbl']] = intval($v[1])*8;
                        }
//
                        elseif (preg_match('/ffret 6 cans Collector C/', $v['libelle'])){
                            $cmdArt[$v['numbl']] = intval($v[1])*6;
                        }
                        else{
                            $cmdArt[$v['numbl']] =  intval($v[1]);
                        }

                    }
                }
            }
            elseif (!$v['flagart'] and preg_match('/Kit Noel/', $v['libelle'])) {
                if ( array_key_exists($v['numbl'], $cmdArt) ) {
                    if (preg_match('/Kit Noel/', $v['libelle'])){
                        $cmdArt[$v['numbl']] = $cmdArt[$v['numbl']] + intval($v[1])*2;
                    }

                } else {
                    if (preg_match('/Kit Noel/', $v['libelle'])){
                        $cmdArt[$v['numbl']] = intval($v[1])*2;
                    }

                }
            }
    }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
            ->setLastModifiedBy('Rob Gravelle')
            ->setTitle('A Simple Excel Spreadsheet')
            ->setSubject('PhpSpreadsheet')
            ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
            ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
            ->setCategory('Test file');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SyntheseNbBouteilles.xlsx.xlsx"');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue( 'A1', 'Nbre de commande');
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue( 'B1', 'quantite de bouteilles');
        ;

        $indice = 2;
        foreach (array_count_values($cmdArt) as $h=>$b) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $indice, $b)
                ->setCellValue('B' . $indice, $h);
            $indice ++;
        }

        $spreadsheet->setActiveSheetIndex(0)
            ->getColumnDimension('A')->setWidth(15);
        $spreadsheet->setActiveSheetIndex(0)
            ->getColumnDimension('B')->setWidth(15);

        $writer =  IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;
    }


    public function syntheseCoriolisAction(Request $request, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $multiP1 = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisCoriolis1($date);
        $compt1 =null;
        foreach ($multiP1 as $v){
            $compt1 = $compt1 + 1;
        }
        $multiP2 = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisCoriolis2($date);
        $compt2 =null;
        foreach ($multiP2 as $v){
            $compt2 = $compt2 + 1;
        }
        $multiP3 = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisCoriolis3($date);
        $compt3 =null;
        foreach ($multiP3 as $v){
            $compt3 = $compt3 + 1;
        }
        $multiP4 = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisCoriolis4($date);
        $compt4 =null;
        foreach ($multiP4 as $v){
            $compt4 = $compt4 + 1;
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
            ->setLastModifiedBy('Rob Gravelle')
            ->setTitle('A Simple Excel Spreadsheet')
            ->setSubject('PhpSpreadsheet')
            ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
            ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
            ->setCategory('Test file');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SyntheseCoriolis.xlsx"');

        $ligne1 =1;
        $ligne2 =2;

        if (($compt1) >1 ) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A'.$ligne1, 'Tranche de 2 à 4 cartes');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B'.$ligne2, 'Nb commandes: ');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C'.$ligne2, $compt1);
            $ligne1 = $ligne1 + 2;
            $ligne2 = $ligne2 + 2;
        }
        if (($compt2) >1 ) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $ligne1, 'Tranche de 5 à 10 cartes');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $ligne2, 'Nb commandes: ');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C' . $ligne2, $compt2);

            $ligne1 = $ligne1 + 2;
            $ligne2 = $ligne2 + 2;
        }
        if (($compt3) >1 ) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $ligne1, 'Tranche de 11 à 30 cartes');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $ligne2, 'Nb commandes: ');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C' . $ligne2, $compt3);

            $ligne1 = $ligne1 + 2;
            $ligne2 = $ligne2 + 2;
        }
        if (($compt4) >1 ) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $ligne1, 'Tranche 31 à 1500 cartes');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B' . $ligne2, 'Nb commandes: ');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C' . $ligne2, $compt4);
        }

        if ( ($compt3) == null and ($compt3) == null and ($compt3) == null and ($compt4) == null){
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Aucune ligne de commande en multi !');
        }else{
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B11', 'Total ');
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C11', '= C2 + C4 + C6 + C8');
        }

        $spreadsheet->setActiveSheetIndex(0)
            ->getColumnDimension('A')->setWidth(20);
        $spreadsheet->setActiveSheetIndex(0)
            ->getColumnDimension('B')->setWidth(15);

        $writer =  IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;
    }


    public function exportTrackingExcelAction(Request $request, $idFile, $prod)
    {
        $idFiles = explode(",", $idFile);
        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');

        $allBlByOpekey=array();

            if ($prod == '0') {
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlByFile($idFiles);
            }
            else if ($prod == '1') {
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlNonProdByFile($idFiles);
            }else if ($prod == '2') {
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByFile($idFiles);
            }else if ($prod == '3') {
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlRuptureByFile($idFiles);
            }else {
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlDeleteByFile($idFiles);

            }

        $numBLs = array();
        foreach ($allBlByOpe as $key=>$file)
        {
            if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
            }
            if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                $numBLs['DPD']['numBl'][$key] = $file['numbl'];
            }

        }
        $statuts = array();
        if ( isset($numBLs['CPRV']['numBl'])) {
            $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
            foreach ($trackingColisPrive as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }
        if (isset($numBLs['DPD']['numBl']) ) {
            $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
            foreach ($trackingDpd as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }

        $tabBlComplet = array();
        foreach ($allBlByOpe as $key=>$item){
            if (isset($statuts[$item['numbl']])){
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];

            }
            else{
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut']['libelle']="";
                $tabBlComplet[$key]['statut']['dateStatut']= new DateTime('00000-00-00');
            }
        }

//var_dump($tabBlComplet);
        $header = array('Date fichier' => 'string',
                                'n° BL'=> 'string',
                                'Ref Client'=> 'string',
                                'exp_ref'=> 'string',
                                'n° Cmde Client'=> 'string',
                                'Date Cmd'=> 'string',
                                'Destinataire'=> 'string',
                                'Rue'=> 'string',
                                'Adresse 2'=> 'string',
                                'Adresse 3'=> 'string',
                                'Adresse 4'=> 'string',
                                'Adresse 5'=> 'string',
                                'Adresse 6'=> 'string',
                                'CP'=> 'string',
                                'Ville'=> 'string',
                                'Pays'=> 'string',
                                'Telephone'=> 'string',
                                'Mail'=> 'string',
                                'Instr_Livraison 1'=> 'string',
                                'Instr_Livraison 2'=> 'string',
                                'Date Dépôt'=> 'string',
                                'N° Tracking'=> 'string',
                                'Date Production'=> 'string',
                                'Mode Expedition'=> 'string',
                                'Type'=> 'string',
                                'Poids (gr)'=> 'string',
                                'Qté total articles'=> 'string',
                                'Nb lignes Cmde '=> 'string',
                                'Date statut'=> 'string',
                                'Statut livraison'=> 'string',
                                'Nb pages'=> 'string',
                                'Statut cmd'=> 'string',
                                'Nom fichier'=> 'string',
                                'Type_cmd'  => 'string'
                            );

        $tabBlComplet2 = array();
        foreach ($tabBlComplet as $k=>$v){
            dump($v);
            if (date_format($v['statut']['dateStatut'], "d-m-Y") == '30-11-1999') {
                $v['statut']['dateStatut'] = '';
            }else{
                $v['statut']['dateStatut'] = date_format($v['statut']['dateStatut'], "d-m-Y ");
            }
            if (isset($v['nColis'])){
                if ($v['nColis'] == '0') {
                    $v['nColis'] = '';
                }
            }else{
                $v[0]['nColis'] = '';
            }
            $v['dateDepot'] = date_format($v['dateDepot'], "d-m-Y");
            if($v['dateDepot'] == "30-11--0001") $v['dateDepot'] ="";
            if (isset($v['dateProduction'])){
                $v['dateProduction'] = date_format($v['dateProduction'], "d-m-Y h:m");
                if($v['dateProduction'] == "30-11--0001 12:11") $v['dateProduction'] ="";
            }else{
                $v['dateProduction'] ="";
            }
            if (!isset($v['modexp'])) {
                $v['modexp'] = '';
            }

            if($v['idStatut'] == "10"){
                $v['idStatut'] ="En rupture";
            }elseif ($v['idStatut'] == "9"){
                $v['idStatut'] ="Annulé";
            }else{
                $v['idStatut'] ="";
            }
            $tabJson = json_decode($v['json'], true);
            if (isset($tabJson['TYPE'])){
                $type = $tabJson['TYPE'];
            }else{
                $type = "";
            }
//            var_dump($tabJson);
            array_push($tabBlComplet2,[date_format($v['dateFile'], "d-m-Y"),
                                        $v['numbl'],
                                        $v['refclient'],
                                        $v['expRef'],
                                        $v['numCmdeClient'],
                                        date_format($v['dateCmde'], "d-m-Y"),
                                        $v['destinataire'],
                                        $v['destRue'],
                                        $v['destAd2'],
                                        $v['destAd3'],
                                        $v['destAd4'],
                                        $v['destAd5'],
                                        $v['destAd6'],
                                        $v['destCp'],
                                        $v['destVille'],
                                        $v['destPays'],
                                        $v['destTel'],
                                        $v['destMail'],
                                        $v['instrLivrais1'],
                                        $v['instrLivrais2'],
                                        $v['dateDepot'],
                                        (string)($v['nColis']),
                                        $v['dateProduction'],
                                        $v['modexp'],
                                        $v['type'],
                                        ltrim($v['poidsReel'], "0"),
                                        $v['quantite'],
                                        $v['nbCmd'],
                                        $v['statut']['dateStatut'],
                                        $v['statut']['libelle'],
                                        $v['nbpages'],
                                        $v['idStatut'],
                                        $v['filename'],
                                        $type

                                    ]);
        }
        $dateFiles = new DateTime();
        $dateExtract = $dateFiles->format('Ymd_His');
        $titre= '';

        if ($prod == '1') {
            $titre= '_attenteProd-';
        }else if ($prod == '0') {
            $titre= '_all';
        }else if ($prod == '2') {
            $titre= '_produits-';
        }
        else if ($prod == '3') {
            $titre= '_enRupture-';
        }else {
            $titre= '_annule-';

        }
        $titre = $titre.$dateExtract;

        if (!isset($tabBlComplet[0])){
            $tabBlComplet[0]['appliname'] = 'pas de fichiers';
            $tabBlComplet[0]['filename']= 'pas de fichiers';
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);
        if ( sizeof($idFiles) > 1) {
            $nomExport = str_replace(' ','',$tabBlComplet[0]['appliname'].$titre);

        }else{
            $nomExport = preg_replace ('/\s+/',"_",($tabBlComplet[0]['filename'].$titre));
        }

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingArticleExcelAction(Request $request, $idFile, $prod)
    {
        $idFiles = explode(",", $idFile);
        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');

        if ($prod == '0') {
            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlByFileArticle($idFiles);
        }
        else if ($prod == '1') {
            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlNonProdByFileArticle($idFiles);
        }else if ($prod == '2') {
            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByFileArticle($idFiles);
        }else if ($prod == '3') {
            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlRuptureByFileArticle($idFiles);
        }else {
            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlDeleteByFile($idFiles);

        }

        $numBLs = array();
        foreach ($allBlByOpe as $key=>$file)
        {
            if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
            }
            if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                $numBLs['DPD']['numBl'][$key] = $file['numbl'];
            }

        }
        $statuts = array();
        if ( isset($numBLs['CPRV']['numBl'])) {
            $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
            foreach ($trackingColisPrive as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }
        if (isset($numBLs['DPD']['numBl']) ) {
            $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
            foreach ($trackingDpd as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }

        $tabBlComplet = array();
        foreach ($allBlByOpe as $key=>$item){
            if (isset($statuts[$item['numbl']])){
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];

            }
            else{
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut']['libelle']="";
                $tabBlComplet[$key]['statut']['dateStatut']= new DateTime('00000-00-00');
            }
        }

//var_dump($tabBlComplet);
        $header = array('Date fichier' => 'string',
            'n° BL'=> 'string',
            'Ref Client'=> 'string',
            'exp_ref'=> 'string',
            'n° Cmde Client'=> 'string',
            'Date Cmd'=> 'string',
            'Destinataire'=> 'string',
            'Rue'=> 'string',
            'Adresse 2'=> 'string',
            'Adresse 3'=> 'string',
            'Adresse 4'=> 'string',
            'Adresse 5'=> 'string',
            'Adresse 6'=> 'string',
            'CP'=> 'string',
            'Ville'=> 'string',
            'Pays'=> 'string',
            'Telephone'=> 'string',
            'Mail'=> 'string',
            'Instr_Livraison 1'=> 'string',
            'Instr_Livraison 2'=> 'string',
            'Date Dépôt'=> 'string',
            'N° Tracking'=> 'string',
            'Date Production'=> 'string',
            'Mode Expedition'=> 'string',
            'Type'=> 'string',
            'Poids (gr)'=> 'string',
            'Date statut'=> 'string',
            'Statut livraison'=> 'string',
            'Nb pages'=> 'string',
            'Statut commande'=> 'string',
            'Nom fichier'=> 'string',
            'Type_cmd'  => 'string',
            'codearticle' => 'string',
            'libelle'    =>'string',
            'quantite'  =>'string',
            'Statut article'  =>'string'
        );
//var_dump($tabBlComplet);
        $tabBlComplet2 = array();
        foreach ($tabBlComplet as $k=>$v){
            if (date_format($v['statut']['dateStatut'], "d-m-Y") == '30-11-1999') {
                $v['statut']['dateStatut'] = '';
            }else{
                $v['statut']['dateStatut'] = date_format($v['statut']['dateStatut'], "d-m-Y ");
            }
            if (isset($v['nColis'])){
                if ($v['nColis'] == '0') {
                    $v['nColis'] = '';
                }
            }else{
                $v[0]['nColis'] = '';
            }
            $v['dateDepot'] = date_format($v['dateDepot'], "d-m-Y");
            if($v['dateDepot'] == "30-11--0001") $v['dateDepot'] ="";
            if (isset($v['dateProduction'])){
                $v['dateProduction'] = date_format($v['dateProduction'], "d-m-Y h:m");
                if($v['dateProduction'] == "30-11--0001 12:11") $v['dateProduction'] ="";
            }else{
                $v['dateProduction'] ="";
            }
            if (!isset($v['modexp'])) {
                $v['modexp'] = '';
            }

            if($v['trStatut'] == "10"){
                $v['trStatut'] ="En rupture";
            }elseif ($v['trStatut'] == "9"){
                $v['trStatut'] ="Annulé";
            }elseif ($v['trStatut'] == "11"){
                $v['trStatut'] ="Sortie-Rupture";
            }else{
                $v['trStatut'] ="";
            }

            if($v['cmdStatut'] == "10"){
                $v['cmdStatut'] ="En rupture";
            }elseif ($v['cmdStatut'] == "9"){
                $v['cmdStatut'] ="Annulé";
            }elseif ($v['cmdStatut'] == "11"){
                $v['cmdStatut'] ="Sortie-Rupture";
            }else{
                $v['cmdStatut'] ="";
            }

            $tabJson = json_decode($v['json'], true);
            if (isset($tabJson['TYPE'])){
                $type = $tabJson['TYPE'];
            }else{
                $type = "";
            }

            array_push($tabBlComplet2,[date_format($v['dateFile'], "d-m-Y"),
                $v['numbl'],
                $v['refclient'],
                $v['expRef'],
                $v['numCmdeClient'],
                date_format($v['dateCmde'], "d-m-Y"),
                $v['destinataire'],
                $v['destRue'],
                $v['destAd2'],
                $v['destAd3'],
                $v['destAd4'],
                $v['destAd5'],
                $v['destAd6'],
                $v['destCp'],
                $v['destVille'],
                $v['destPays'],
                $v['destTel'],
                $v['destMail'],
                $v['instrLivrais1'],
                $v['instrLivrais2'],
                $v['dateDepot'],
                (string)($v['nColis']),
                $v['dateProduction'],
                $v['modexp'],
                $v['type'],
                ltrim($v['poidsReel'], "0"),
                $v['statut']['dateStatut'],
                $v['statut']['libelle'],
                $v['nbpages'],
                $v['trStatut'],
                $v['filename'],
                $type,
                $v['codearticle'],
                $v['libelle'],
                $v['quantite'],
                $v['cmdStatut'],

            ]);
        }

        $titre= '';
        if ($prod == '1') {
            $titre= '_attenteProd';
        }else if ($prod == '0') {
            $titre= '_all';
        }else if ($prod == '2') {
            $titre= '_produits';
        }
        else if ($prod == '3') {
            $titre= '_enRupture';
        }else {
            $titre= '_annule';

        }

        if (!isset($tabBlComplet[0])){
            $tabBlComplet[0]['appliname'] = 'pas de fichiers';
            $tabBlComplet[0]['filename']= 'pas de fichiers';
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);
        if ( sizeof($idFiles) > 1) {
            $nomExport = str_replace(' ','',$tabBlComplet[0]['appliname'].$titre);

        }else{
            $nomExport = preg_replace ('/\s+/',"_",($tabBlComplet[0]['filename'].$titre));
        }

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingExcelbyBLAction(Request $request, $idBLs, $code)
    {
        $idFiles = explode(",", $idBLs);

        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');


        $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByBLS($idFiles);

        $numBLs = array();
        foreach ($allBlByOpe as $key=>$file)
        {
            if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
            }
            if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                $numBLs['DPD']['numBl'][$key] = $file['numbl'];
            }

        }
        $statuts = array();
        if ( isset($numBLs['CPRV']['numBl'])) {
            $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
            foreach ($trackingColisPrive as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }
        if (isset($numBLs['DPD']['numBl']) ) {
            $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
            foreach ($trackingDpd as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }

        $tabBlComplet = array();
        foreach ($allBlByOpe as $key=>$item){
            if (isset($statuts[$item['numbl']])){
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];

            }
            else{
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut']['libelle']="";
                $tabBlComplet[$key]['statut']['dateStatut']= new DateTime('00000-00-00');
            }
        }

        $header = array('Date fichier' => 'string',
            'n° BL'=> 'string',
            'Ref Client'=> 'string',
            'exp_ref'=> 'string',
            'n° Cmde Client'=> 'string',
            'Date Cmd'=> 'string',
            'Destinataire'=> 'string',
            'Rue'=> 'string',
            'Adresse 2'=> 'string',
            'Adresse 3'=> 'string',
            'Adresse 4'=> 'string',
            'Adresse 5'=> 'string',
            'Adresse 6'=> 'string',
            'CP'=> 'string',
            'Ville'=> 'string',
            'Pays'=> 'string',
            'Telephone'=> 'string',
            'Mail'=> 'string',
            'Instr_Livraison 1'=> 'string',
            'Instr_Livraison 2'=> 'string',
            'Date Dépôt'=> 'string',
            'N° Tracking'=> 'string',
            'Date Production'=> 'string',
            'Mode Expedition'=> 'string',
            'Type'=> 'string',
            'Poids (gr)'=> 'string',
            'Qté'=> 'string',
            'Date statut'=> 'string',
            'Statut livraison'=> 'string',
            'Nb pages'=> 'string',
            'NbLignesCmde'=> 'string',
            'Nom fichier'=> 'string'
        );

        $tabBlComplet2 = array();
        foreach ($tabBlComplet as $k=>$v){
            if (date_format($v['statut']['dateStatut'], "d-m-Y") == '30-11-1999') {
                $v['statut']['dateStatut'] = '';
            }else{
                $v['statut']['dateStatut'] = date_format($v['statut']['dateStatut'], "d-m-Y ");
            }
            if ($v[0]['nColis'] == '0') {
                $v[0]['nColis'] = '';
            }
            $v['dateDepot'] = date_format($v['dateDepot'], "d-m-Y");
            if($v['dateDepot'] == "30-11--0001") $v['dateDepot'] ="";

            $v['dateProduction'] = date_format($v['dateProduction'], "d-m-Y h:m");
            if($v['dateProduction'] == "30-11--0001 12:11") $v['dateProduction'] ="";

            array_push($tabBlComplet2,[date_format($v['dateFile'], "d-m-Y"),
                $v['numbl'],
                $v['refclient'],
                $v['expRef'],
                $v['numCmdeClient'],
                date_format($v['dateCmde'], "d-m-Y"),
                $v['destinataire'],
                $v['destRue'],
                $v['destAd2'],
                $v['destAd3'],
                $v['destAd4'],
                $v['destAd5'],
                $v['destAd6'],
                $v['destCp'],
                $v['destVille'],
                $v['destPays'],
                $v['destTel'],
                $v['destMail'],
                $v['instrLivrais1'],
                $v['instrLivrais2'],
                $v['dateDepot'],
                (string)($v[0]['nColis']),
                $v['dateProduction'],
                $v[0]['modexp'],
                $v['type'],
                ltrim($v['poidsReel'], "0"),
                $v['quantite'],
                $v['statut']['dateStatut'],
                $v['statut']['libelle'],
                $v['nbpages'],
                $v['nbCmd'],
                $v['filename']
            ]);
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);

            $nomExport = 'Export_'.$code;

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingExcelbyDateAction(Request $request, $date, $appli)
    {
        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');

        $Bls = $em->getRepository('TMDProdBundle:EcommLignes')->findTrackingsbyDateDepotandop($appli, $date);
        $BlsTab= array();
        foreach ($Bls as $v){
            array_push($BlsTab, $v['numbl']);
        }

        $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByBLS($BlsTab);

        $numBLs = array();
        foreach ($allBlByOpe as $key=>$file)
        {
            if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
            }
            if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                $numBLs['DPD']['numBl'][$key] = $file['numbl'];
            }

        }
        $statuts = array();
        if ( isset($numBLs['CPRV']['numBl'])) {
            $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
            foreach ($trackingColisPrive as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }
        if (isset($numBLs['DPD']['numBl']) ) {
            $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
            foreach ($trackingDpd as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }

        $tabBlComplet = array();
        foreach ($allBlByOpe as $key=>$item){
            if (isset($statuts[$item['numbl']])){
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
            }
            else{
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut']['libelle']="";
                $tabBlComplet[$key]['statut']['dateStatut']= new DateTime('00000-00-00');
            }
        }

        $header = array('Date fichier' => 'string',
            'n° BL'=> 'string',
            'Ref Client'=> 'string',
            'exp_ref'=> 'string',
            'n° Cmde Client'=> 'string',
            'Date Cmd'=> 'string',
            'Destinataire'=> 'string',
            'Rue'=> 'string',
            'Adresse 2'=> 'string',
            'Adresse 3'=> 'string',
            'Adresse 4'=> 'string',
            'Adresse 5'=> 'string',
            'Adresse 6'=> 'string',
            'CP'=> 'string',
            'Ville'=> 'string',
            'Pays'=> 'string',
            'Telephone'=> 'string',
            'Mail'=> 'string',
            'Instr_Livraison 1'=> 'string',
            'Instr_Livraison 2'=> 'string',
            'Date Dépôt'=> 'string',
            'N° Tracking'=> 'string',
            'Date Production'=> 'string',
            'Mode Expedition'=> 'string',
            'Type'=> 'string',
            'Poids (gr)'=> 'string',
            'Qté'=> 'string',
            'Date statut'=> 'string',
            'Statut livraison'=> 'string',
            'Nb pages'=> 'string',
            'NbLignesCmde'=> 'string',
            'Nom fichier'=> 'string'
        );

        $tabBlComplet2 = array();
        foreach ($tabBlComplet as $k=>$v){
            if (date_format($v['statut']['dateStatut'], "d-m-Y") == '30-11-1999') {
                $v['statut']['dateStatut'] = '';
            }else{
                $v['statut']['dateStatut'] = date_format($v['statut']['dateStatut'], "d-m-Y ");
            }
            if ($v[0]['nColis'] == '0') {
                $v[0]['nColis'] = '';
            }
            $v['dateDepot'] = date_format($v['dateDepot'], "d-m-Y");
            if($v['dateDepot'] == "30-11--0001") $v['dateDepot'] ="";

            $v['dateProduction'] = date_format($v['dateProduction'], "d-m-Y h:m");
            if($v['dateProduction'] == "30-11--0001 12:11") $v['dateProduction'] ="";

            array_push($tabBlComplet2,[date_format($v['dateFile'], "d-m-Y"),
                $v['numbl'],
                $v['refclient'],
                $v['expRef'],
                $v['numCmdeClient'],
                date_format($v['dateCmde'], "d-m-Y"),
                $v['destinataire'],
                $v['destRue'],
                $v['destAd2'],
                $v['destAd3'],
                $v['destAd4'],
                $v['destAd5'],
                $v['destAd6'],
                $v['destCp'],
                $v['destVille'],
                $v['destPays'],
                $v['destTel'],
                $v['destMail'],
                $v['instrLivrais1'],
                $v['instrLivrais2'],
                $v['dateDepot'],
                (string)($v[0]['nColis']),
                $v['dateProduction'],
                $v[0]['modexp'],
                $v['type'],
                ltrim($v['poidsReel'], "0"),
                $v['quantite'],
                $v['statut']['dateStatut'],
                $v['statut']['libelle'],
                $v['nbpages'],
                $v['nbCmd'],
                $v['filename']
            ]);
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);


        $nomExport = 'Export_'.$Bls[0]['appliname'].'_'.$date;

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingExcelbyBLArticleAction(Request $request, $date, $appli)
    {
        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');


        $Bls = $em->getRepository('TMDProdBundle:EcommLignes')->findTrackingsbyDateDepotandopAndPress($appli, $date);
        $BlsTab= array();
        foreach ($Bls as $v){
            array_push($BlsTab, $v['numbl']);
        }

        $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByBLS($Bls);

        $tabBls=[];
        foreach ($allBlByOpe as $i){
            array_push($tabBls,$i['numbl']);
        }
        $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBLsArticleArray($tabBls);

        $articleArray = array();
        foreach ($articles as $key => $item) {
            $articleArray[$key] = $item;
        }
//var_dump($articleArray);

        $numBLs = array();
        foreach ($allBlByOpe as $key=>$file)
        {
            if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
            }
            if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                $numBLs['DPD']['numBl'][$key] = $file['numbl'];
            }
        }
        $statuts = array();
        if ( isset($numBLs['CPRV']['numBl'])) {
            $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
            foreach ($trackingColisPrive as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }
        if (isset($numBLs['DPD']['numBl']) ) {
            $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
            foreach ($trackingDpd as $cp)
            {
                $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
            }
        }

        $tabBlComplet = array();
        foreach ($allBlByOpe as $key=>$item){
            if (isset($statuts[$item['numbl']])){
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];

            }
            else{
                $tabBlComplet[$key] = $item;
                $tabBlComplet[$key]['statut']['libelle']="";
                $tabBlComplet[$key]['statut']['dateStatut']= new DateTime('00000-00-00');
            }
        }

        $header = array(
            'n°' => 'string',
            'Date fichier' => 'string',
            'n° BL'=> 'string',
            'Ref Client'=> 'string',
            'exp_ref'=> 'string',
            'n° Cmde Client'=> 'string',
            'Date Cmd'=> 'string',
            'Destinataire'=> 'string',
            'Rue'=> 'string',
            'Adresse 2'=> 'string',
            'Adresse 3'=> 'string',
            'Adresse 4'=> 'string',
            'Adresse 5'=> 'string',
            'Adresse 6'=> 'string',
            'CP'=> 'string',
            'Ville'=> 'string',
            'Pays'=> 'string',
            'Telephone'=> 'string',
            'Mail'=> 'string',
            'Instr_Livraison 1'=> 'string',
            'Instr_Livraison 2'=> 'string',
            'Date Dépôt'=> 'string',
            'N° Tracking'=> 'string',
            'Date Production'=> 'string',
            'Mode Expedition'=> 'string',
            'Type'=> 'string',
            'Poids (gr)'=> 'string',
            'Qté'=> 'string',
            'Date statut'=> 'string',
            'Statut livraison'=> 'string',
            'Nb pages'=> 'string',
            'NbLignesCmde'=> 'string',
            'Nom fichier'=> 'string'
        );

        $tabBlComplet2 = array();
        $index=1;
        foreach ($tabBlComplet as $k=>$v){
            if (date_format($v['statut']['dateStatut'], "d-m-Y") == '30-11-1999') {
                $v['statut']['dateStatut'] = '';
            }else{
                $v['statut']['dateStatut'] = date_format($v['statut']['dateStatut'], "d-m-Y ");
            }
            if ($v[0]['nColis'] == '0') {
                $v[0]['nColis'] = '';
            }
            $v['dateDepot'] = date_format($v['dateDepot'], "d-m-Y");
            if($v['dateDepot'] == "30-11--0001") $v['dateDepot'] ="";

            $v['dateProduction'] = date_format($v['dateProduction'], "d-m-Y h:m");
            if($v['dateProduction'] == "30-11--0001 12:11") $v['dateProduction'] ="";

            array_push(
                $tabBlComplet2,[
                $index,
                date_format($v['dateFile'], "d-m-Y"),
                $v['numbl'],
                $v['refclient'],
                $v['expRef'],
                $v['numCmdeClient'],
                date_format($v['dateCmde'], "d-m-Y"),
                $v['destinataire'],
                $v['destRue'],
                $v['destAd2'],
                $v['destAd3'],
                $v['destAd4'],
                $v['destAd5'],
                $v['destAd6'],
                $v['destCp'],
                $v['destVille'],
                $v['destPays'],
                $v['destTel'],
                $v['destMail'],
                $v['instrLivrais1'],
                $v['instrLivrais2'],
                $v['dateDepot'],
                (string)($v[0]['nColis']),
                $v['dateProduction'],
                $v[0]['modexp'],
                $v['type'],
                ltrim($v['poidsReel'], "0"),
                $v['quantite'],
                $v['statut']['dateStatut'],
                $v['statut']['libelle'],
                $v['nbpages'],
                $v['nbCmd'],
                $v['filename']
            ]);

            foreach ($articleArray as $key){
                if ($key['numbl'] ==  $v['numbl']){
                    array_push($tabBlComplet2,
                        ["","",
                        $v['numbl'],
                        "code article: ".$key['codearticle'],
                        "libelle: ".$key['libelle'],
                        "poids: ".$key['poids']." gr",
                        "Qté: ".$key['quantite'],

                    ]);
                }
            }
            $index++;
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);

        $nomExport = 'Export_Article_'.$Bls[0]['appliname'].'_'.$date;

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingPresseAction(Request $request, $date, $appli)
    {
        $em = $this->getDoctrine()->getManager();

        $Bls = $em->getRepository('TMDProdBundle:EcommLignes')->findTrackingsbyDateDepotandopAndPress($appli, $date);
        $BlsTab= array();
        foreach ($Bls as $v){
            array_push($BlsTab, $v['numbl']);
        }

        $allBlByOpe = $em->getRepository('TMDProdBundle:EcommCmdep')->findAllBlByBLSandPresse($BlsTab);

        $groupByArticle = $em->getRepository('TMDProdBundle:EcommCmdep')->findregroupArticleByBLSandPresse($BlsTab);

        $tabpresseTri=array();
        $maxQuantite = $allBlByOpe[sizeof($allBlByOpe)-1]['quantite'];
        for ($i = 1 ; $i <= $maxQuantite ; $i++){
            $compteurQuantite=0;
            $compteurPoids=0;
            $inBoucle=false;
            $tab=array();
            foreach ($allBlByOpe as $key){
                if ($key['quantite'] == $i){
                    $inBoucle=true;
                    $compteurQuantite++;
                    $compteurPoids = $compteurPoids + $key['poids'];
                }
            }
            if ($inBoucle){
                $tab['compo'] = $i;
                $tab['qte'] = $compteurQuantite;
                $tab['poidsTot'] = $compteurPoids;
                $tab['poidsMoy'] = $compteurPoids/$compteurQuantite;
                array_push($tabpresseTri,$tab );
            }

        }

        $header = array(
            'detail composition' => 'string',
            'Quantite'=> 'string',
            'poids total (gr)'=> 'string',
            'poids moyen (gr)'=> 'number'
        );

        $tabBlComplet2 = array();
        foreach ($tabpresseTri as $v){

            array_push($tabBlComplet2,
                [$v['compo'],
                 $v['qte'],
                 $v['poidsTot'],
                 $v['poidsMoy']
            ]);

        }

        array_push($tabBlComplet2,
            ["","","",""]);
        array_push($tabBlComplet2,
            ["","","",""]);
        array_push($tabBlComplet2,
            ["Total des articles"]);
        array_push($tabBlComplet2,
            ["Libelle article","quantite","poids unitaire(gr)"]);
        foreach ($groupByArticle as $v){
            array_push($tabBlComplet2,
                [$v['libelle'],
                    $v['quantite'],
                    $v['poids']
                ]);
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);

        $nomExport = 'Export_Presse_'.$Bls[0]['appliname'].'_'.$date;

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportTrackingbyDateAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $date = $request->get('infoDate');
            $idAppli = $request->get('infoAppli');

            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpeByDate = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByDateProdByAppli($idAppli, $date);

            $numBLs = array();
            foreach ($allBlByOpeByDate as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }
            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpeByDate as $key=>$item){
                $histoStatut = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlASC($item['numbl']);
                $historiqueStat =[];
                for ($i=0; $i < count($histoStatut); $i++){

                    $historiqueStat[$i]['idstatut'] = $histoStatut[$i]['idstatut'];
                    $historiqueStat[$i]['statut'] = $em->getRepository('TMDProdBundle:EcommStatut')->find($histoStatut[$i]['idstatut'])->getStatut();
                    $historiqueStat[$i]['observation'] = $histoStatut[$i]['observation'];
                    $historiqueStat[$i]['datestatut'] = $histoStatut[$i]['datestatut'];



                }

                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
            }

            return new JsonResponse($tabBlComplet);
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function exportTrackingbyDateDepotAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $date = $request->get('infoDate');
            $idAppli = $request->get('infoAppli');

            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpeByDate = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByDateDepotByAppli($idAppli, $date);
            $nbDateDepotPresse = $em->getRepository('TMDProdBundle:EcommBl')->nbPresseByDateDepotByAppli($idAppli, $date);

            $tabBls=[];
            foreach ($allBlByOpeByDate as $i){
                array_push($tabBls,$i['numbl']);
            }
            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBLsArray($tabBls);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByBLsArray($tabBls);

            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpeByDate as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }

            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpeByDate as $key=>$item){

                    $histoStatut = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlASC($item['numbl']);
                    $historiqueStat =[];
                    for ($i=0; $i < count($histoStatut); $i++){

                        $historiqueStat[$i]['idstatut'] = $histoStatut[$i]['idstatut'];
                        $historiqueStat[$i]['statut'] = $em->getRepository('TMDProdBundle:EcommStatut')->find($histoStatut[$i]['idstatut'])->getStatut();
                        $historiqueStat[$i]['observation'] = $histoStatut[$i]['observation'];
                        $historiqueStat[$i]['datestatut'] = $histoStatut[$i]['datestatut'];



                    }
                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
            }

            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray, 'perso' => $articlesPersos, 'nbDateDepotPresse' => $nbDateDepotPresse );

            return new JsonResponse($reponse);

        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function viewClientAction($idClient, $idOpe, $page, $nbFichierPage)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'exciste pas. ");
        }

        $clients = null;
        $OperationsByClient = null;
        $filesByClient = null;

        $em = $this ->getDoctrine() ->getManager();
        $clients = $em->getRepository('TMDProdBundle:EcommAppli')->findClientWithOperation();

        if ($idClient != 0)
        {
            $OperationsByClient = $em->getRepository('TMDProdBundle:EcommAppli')->findAppliByClient($idClient);
        }

        if ($idOpe !=0 )
        {
            $logo = $em->getRepository('TMDProdBundle:EcommAppli')->findBy(array('idappli' => $idOpe));

            if( $logo[0]->getAppliImage() != null) {
                $logo['appliImage'] = (base64_encode(stream_get_contents($logo[0]->getAppliImage())));
            }

            $nbParPage = $nbFichierPage;

            $fileByOpe = $em->getRepository('TMDProdBundle:EcommTracking')->findFileByOpe($idOpe, $page, $nbParPage);
            $fileTotByOp = $em->getRepository('TMDProdBundle:EcommTracking')->findFileTotByOpe($idOpe);
            $fileBLTotByOp = $em->getRepository('TMDProdBundle:EcommLignes')->findFileTotByOpe($idOpe);
            $nbTrackingByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbTrackingByFile($idOpe);

            $filesPaginator = array();
            foreach ($fileByOpe as $f=>$k){
                $filesPaginator[$f] = $k['idfile'];
            }

            //synthese Statut par idFile
            $syntheseStatut = $em->getRepository('TMDProdBundle:EcommTracking')->findSyntheseStatut($idOpe);
            $tabSyntheseStatut = array();
            foreach ($syntheseStatut as $key=>$val)
            {
                if (!isset($tabSyntheseStatut[$val['idfile']]))$tabSyntheseStatut[$val['idfile']] = array();
//                $tab = array();
                array_push($tabSyntheseStatut[$val['idfile']],  array((strval($val['idStatut'])) => $val[1]));
            }

            $trackings = array();
            $files= array();
            foreach ($nbTrackingByFile as $key=>$file)
            {
                $trackings[$file['idfile']] = intval($file[1]);
                $files[$key] = $file ;
            }
            $nbBlByFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlByFile($files);

            $filesBl= array();
            $dateMaxProdByApplication = (new DateTime('1980-01-01 '))->format('Y-m-d h:i');

            // on comptabise le nombre de BL par fichier dont date depot n'est pas renseigner = 0000-00-00
            $nbBlWithDepotNull = $em->getRepository('TMDProdBundle:EcommTracking')->findnbBlWithDepotNull($idOpe);
            $TrDepotNull = array();
            foreach ($nbBlWithDepotNull as $key=>$file)
            {
                $TrDepotNull[$file['idfile']] = intval($file[1]);
            }

            $ArticlesByIdFile = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFile($filesPaginator);
            $ArticlesSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpe($idOpe);
            $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommLignes')->findArticlesByOpeByDateNonProd($idOpe);
            $ArticlesSyntheseRupture = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpeRupture($idOpe);
            $ArticlesReclaSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesReclaByFile($files);

            $nbRetentionByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbRetentionByFile($files);

            $nbRetentionByFileS = array();
            foreach($nbRetentionByFile as $k)
            {
                $nbRetentionByFileS[$k['idfile']] = $k;
            }

            $ArticlesReclaSyntheseTab = array();
            if ( $idClient == 518 or $idClient == 146 or $idClient == 685 or $idClient == 633) {
                foreach ($ArticlesReclaSynthese as $k) {
                    $ArticlesReclaSyntheseTab[$k['idfile']] = $k;
                }
            }

            $syntheseArticles=[];
            foreach($ArticlesSynthese as $article)
            {
                $name = $article['codearticle']." -> ".$article['libelle'];
                $syntheseArticles['tot'][$article['flagart']][$name]= $article['quantite'];
            }
            foreach($ArticlesSyntheseRupture as $articler)
            {
                $name = $articler['codearticle']." -> ".$articler['libelle'];
                $syntheseArticles['rupture'][$articler['flagart']][$name]= $articler['quantite'];
            }
            foreach($ArticlesSyntheseNonProd as $article)
            {
                $syntheseArticles['aProd'][$article['flagart']][$article['codearticle']]= $article['quantite'];
                $name = $article['codearticle']." -> ".$article['libelle'];
                $syntheseArticles['aProdLibelle'][$article['flagart']][$name]= $article['quantite'];
            }

            if ( $idOpe == 554){
                $nbCarte = $em->getRepository('TMDAppliBundle:NTracking')->findNbCarteUtilByClient($idClient);
                $syntheseArticles['coriolisCarte'] = $nbCarte;
            }


            $articles=[];
            foreach ($ArticlesByIdFile as $key=>$ar)
            {
                $articles[$ar['idfile']][$ar['flagart']][$ar['libelle']] =  ($ar['quantite']);

            }
            $modeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findDistinctTransportByFile($filesPaginator);

            dump($nbBlByFile);
            foreach ($nbBlByFile as $bl)
            {
                $filesBl[$bl['idfile']]['nbBl'] = $bl[1];
                if (isset($nbRetentionByFileS[$bl['idfile']])) {
                    $filesBl[$bl['idfile']]['flagXport'] = $nbRetentionByFileS[$bl['idfile']][1];
                }
                $filesBl[$bl['idfile']]['sitexp'] = $bl['abregesiteprod'];
                $filesBl[$bl['idfile']]['dateMaxProd'] = $bl['maxDate'];

                if (!empty($bl['dateDepot']) ) {
                    $dateDepot = $bl['dateDepot']->format('Y-m-d h:i');
                    $filesBl[$bl['idfile']]['dateDepot'] = $dateDepot;
                }
                foreach ($modeTransport as $transport)
                {
                    $filesBl[$bl['idfile']]['transport'][$transport['typeTransport']]=0;
                }
                if ( $bl['maxDate'] > $dateMaxProdByApplication)
                {
                    $dateMaxProdByApplication = $bl['maxDate'];
                }
                if ( array_key_exists($bl['idfile'], $articles)) {
                    $filesBl[$bl['idfile']]['articles'] = [$articles[$bl['idfile']]][0];
                }
                if ( array_key_exists($bl['idfile'], $ArticlesReclaSyntheseTab)) {
                    $filesBl[$bl['idfile']]['articlesReclamation'] = $ArticlesReclaSyntheseTab[$bl['idfile']]['q'];
                }
            }
            $countTransportbyFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbTransportByFile($files);
            foreach ($countTransportbyFile  as $bl)
            {
                        $filesBl[$bl['idfile']]['transport'][$bl['modexp']] = $bl[1];
            }

            // donne un tableau qui fait la synthese du transport: liste des transports utilisé avec le total des BL pour chacun
            $transportCount=[];
            foreach ($filesBl as $file)
            {
                foreach ($file['transport'] as $key=>$val) {
                    if (isset($transportCount[$key])) {
                        $transportCount[$key] = $transportCount[$key] + $val;
                    } else {
                        $transportCount[$key] = $val;
                    }
                }
            }
            dump($filesBl);
            $nbBlByFileProd = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlByFileProd($files);
            $filesBlProd = array();
            $dateMinProdByApplication = '2040-01-01 ';
            foreach ($nbBlByFileProd as $blp)
            {
                $filesBlProd[$blp['idfile']]['totProd'] = $blp[1];
                $filesBlProd[$blp['idfile']]['minProd'] = $blp['minDate'];
                if ( $blp['minDate'] < $dateMinProdByApplication)
                {
                    $dateMinProdByApplication = $blp['minDate'];
                }
            }

            $tabSyntheseGeneralaProd = 0;
            $tabSyntheseGeneralaRupture = 0;
            $tabSyntheseGeneralaSupp = 0;
            foreach ($tabSyntheseStatut as $tab)
            {
                foreach ($tab as $tab1) {
                    foreach ($tab1 as $u => $v) {
                        if ($u == 9) {
                            $tabSyntheseGeneralaSupp += intval($v);
                        }
                        if ($u == 10) {
                            $tabSyntheseGeneralaRupture  += intval($v);
                        }
                    }
                }
            }
            foreach ($filesBlProd as $tab)
            {
                $tabSyntheseGeneralaProd += intval($tab['totProd']);
            }

            //pagination
            $nbPages = ceil(count($nbTrackingByFile) / $nbParPage);
            if ($page > $nbPages) {

                return $this->render('TMDProdBundle:Prod:suiviProd.html.twig', array(
                'clients'        => $clients,
                'OperationsByClient'  => $OperationsByClient,
                'idClient'      => $idClient,
                'plusFichiers' => true

            ));
            }


            $blretour = null;

            return $this->render('TMDProdBundle:Prod:suiviProd.html.twig', array(
                'idOperation'               => $idOpe,
                'idClient'                  => $idClient,
                'clients'                   => $clients,
                'OperationsByClient'        => $OperationsByClient,
                'logo'                      => $logo,
                'nbPages'                   => $nbPages,
                'page'                      => $page,
                'nbFichiersPage'            => $nbFichierPage,
                'files'                     => $fileByOpe,
                'filesTot'                  => $fileTotByOp,
                'filesBLtot'                => intval($fileBLTotByOp[0][1]),
                'nbTrByFile'                => $trackings,
                'nbBlbyfile'                => $filesBl,
                'nbBlByFileProd'            => $filesBlProd,
//                'quantiteBlaProduire'       => $quantiteBlaProduire,
                'dateMaxProdByApplication'  => $dateMaxProdByApplication,
                'dateMinProdByApplication'  => $dateMinProdByApplication,
                'TypeTransport'             => $modeTransport,
                'syntheseTransport'         => $transportCount,
                'syntheseArticle'           => $syntheseArticles,
                'blRetour'                  => $blretour,
                'syntheseStatut'            => $syntheseStatut,
                'tabsyntheseStatut'         => $tabSyntheseStatut,
                'tabSyntheseGeneralaProd'   => $tabSyntheseGeneralaProd,
                'tabSyntheseGeneralaSupp'   => $tabSyntheseGeneralaSupp,
                'tabSyntheseGeneralaRupture'=> $tabSyntheseGeneralaRupture,
                'nbBLWtihDepotNull'         => $TrDepotNull
            ));
        }

        return $this->render('TMDProdBundle:Prod:suiviProd.html.twig', array(
            'clients'        => $clients,
            'OperationsByClient'  => $OperationsByClient,
            'idClient'      => $idClient,
        ));

    }


    public function viewSuiviCourantAction($idClient, $idOpe, $blR, $page, $nbFichierPage)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $em = $this->getDoctrine()->getManager();

        if ($idOpe == 0 ) {

            $operationsCournates = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsCourantes(12);

            foreach ( $operationsCournates as $u=>$v)
            {
                if ( $v['appliImage'] != null) {
                    $operationsCournates[$u]['appliImage'] = (base64_encode(stream_get_contents($v['appliImage'])));
                }
            }
        }
        else {
            $operationCourante = $em->getRepository('TMDProdBundle:EcommAppli')->findBy(array('idappli' => $idOpe));
            foreach ( $operationCourante as $u) {
                if ($u->getAppliImage() != null) {
                    $operationCourante['appliImage'] = (base64_encode(stream_get_contents($operationCourante[0]->getAppliImage())));
                }
            }
            $nbParPage = $nbFichierPage;
            $fileByOpe = $em->getRepository('TMDProdBundle:EcommTracking')->findFileByOpe($idOpe, $page, $nbParPage);
            $fileTotByOp = $em->getRepository('TMDProdBundle:EcommTracking')->findFileTotByOpe($idOpe);
            $fileBLTotByOp = $em->getRepository('TMDProdBundle:EcommLignes')->findFileTotByOpe($idOpe);
            $nbTrackingByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbTrackingByFile($idOpe);

            $filesPaginator = array();
            foreach ($fileByOpe as $f=>$k){
                $filesPaginator[$f] = $k['idfile'];
            }

            //synthese Statut par idFile
            $syntheseStatut = $em->getRepository('TMDProdBundle:EcommTracking')->findSyntheseStatut($idOpe);
            $tabSyntheseStatut = array();
            foreach ($syntheseStatut as $key=>$val)
            {
                if (!isset($tabSyntheseStatut[$val['idfile']]))$tabSyntheseStatut[$val['idfile']] = array();
               //$tab = array();
                array_push($tabSyntheseStatut[$val['idfile']],  array((strval($val['idStatut'])) => $val[1]));

            }

            $trackings = array();
            $files= array();
            foreach ($nbTrackingByFile as $key=>$file)
            {
                $trackings[$file['idfile']] = intval($file[1]);
                $files[$key] = $file ;
            }

            $nbBlByFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlByFile($files);

            $filesBl= array();
            $dateMaxProdByApplication = (new DateTime('1980-01-01 '))->format('Y-m-d h:i');

            // on comptabise le nombre de BL par fichier dont date depot n'est pas renseigner = 0000-00-00
            $nbBlWithDepotNull = $em->getRepository('TMDProdBundle:EcommTracking')->findnbBlWithDepotNull($idOpe);
            $TrDepotNull = array();
            foreach ($nbBlWithDepotNull as $key=>$file)
            {
                $TrDepotNull[$file['idfile']] = intval($file[1]);
            }

            $ArticlesByIdFile = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFile($filesPaginator);
            $ArticlesSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpe($idOpe);
            $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommLignes')->findArticlesByOpeByDateNonProd($idOpe);
            $ArticlesSyntheseRupture = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpeRupture($idOpe);
            $ArticlesReclaSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesReclaByFile($files);

            $nbRetentionByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbRetentionByFile($files);

            $nbRetentionByFileS = array();
            foreach($nbRetentionByFile as $k)
            {
                $nbRetentionByFileS[$k['idfile']] = $k;
            }

            $ArticlesReclaSyntheseTab = array();
            foreach($ArticlesReclaSynthese as $k)
            {
                $ArticlesReclaSyntheseTab[$k['idfile']] = $k;
            }

            $syntheseArticles=[];
            foreach($ArticlesSynthese as $article)
            {
                $name = $article['codearticle']." -> ".$article['libelle'];
                $syntheseArticles['tot'][$article['flagart']][$name]= $article['quantite'];
//                $syntheseArticles['tot'][$article['flagart']][$article['libelle']]= $article['quantite'];
            }
            foreach($ArticlesSyntheseRupture as $articler)
            {
                $name = $articler['codearticle']." -> ".$articler['libelle'];
                $syntheseArticles['rupture'][$articler['flagart']][$name]= $articler['quantite'];
            }
            foreach($ArticlesSyntheseNonProd as $article)
            {
                $syntheseArticles['aProd'][$article['flagart']][$article['codearticle']]= $article['quantite'];
                $name = $article['codearticle']." -> ".$article['libelle'];
                $syntheseArticles['aProdLibelle'][$article['flagart']][$name]= $article['quantite'];
            }

            if ( $idOpe == 554){
                $nbCarte = $em->getRepository('TMDAppliBundle:NTracking')->findNbCarteUtilByClient($idClient);
                $syntheseArticles['coriolisCarte'] = $nbCarte;

            }

            $articles=[];
            foreach ($ArticlesByIdFile as $key=>$ar)
            {
                $articles[$ar['idfile']][$ar['flagart']][$ar['libelle']] =  ($ar['quantite']);

            }

            $modeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findDistinctTransportByFile($filesPaginator);
            foreach ($nbBlByFile as $bl)
            {
                $filesBl[$bl['idfile']]['nbBl'] = $bl[1];
                if (isset($nbRetentionByFileS[$bl['idfile']])) {
                    $filesBl[$bl['idfile']]['flagXport'] = $nbRetentionByFileS[$bl['idfile']][1];
                }
                $filesBl[$bl['idfile']]['sitexp'] = $bl['abregesiteprod'];
                $filesBl[$bl['idfile']]['dateMaxProd'] = $bl['maxDate'];

                if (!empty($bl['dateDepot']) ) {
                    $dateDepot = $bl['dateDepot']->format('Y-m-d h:i');
                    $filesBl[$bl['idfile']]['dateDepot'] = $dateDepot;
                }
                foreach ($modeTransport as $transport)
                {
                    $filesBl[$bl['idfile']]['transport'][$transport['typeTransport']]=0;
                }
                if ( $bl['maxDate'] > $dateMaxProdByApplication)
                {
                    $dateMaxProdByApplication = $bl['maxDate'];
                }
                if ( array_key_exists($bl['idfile'], $articles)) {
                    $filesBl[$bl['idfile']]['articles'] = [$articles[$bl['idfile']]][0];
                }
                if ( array_key_exists($bl['idfile'], $ArticlesReclaSyntheseTab)) {
                    $filesBl[$bl['idfile']]['articlesReclamation'] = $ArticlesReclaSyntheseTab[$bl['idfile']]['q'];
                }

            }

            $countTransportbyFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbTransportByFile($files);
            foreach ($countTransportbyFile  as $bl)
            {
                $filesBl[$bl['idfile']]['transport'][$bl['modexp']] = $bl[1];
            }

            // donne un tableau qui fait la synthese du transport: liste des transports utilisé avec le total des BL pour chacun
            $transportCount=[];
            foreach ($filesBl as $file)
            {
                foreach ($file['transport'] as $key=>$val) {
                    if (isset($transportCount[$key])) {
                        $transportCount[$key] = $transportCount[$key] + $val;
                    } else {
                        $transportCount[$key] = $val;
                    }
                }
            }

            $nbBlByFileProd = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlByFileProd($files);
            $filesBlProd = array();
            $dateMinProdByApplication = '2040-01-01 ';
            foreach ($nbBlByFileProd as $blp)
            {
                $filesBlProd[$blp['idfile']]['totProd'] = $blp[1];
                $filesBlProd[$blp['idfile']]['minProd'] = $blp['minDate'];
                if ( $blp['minDate'] < $dateMinProdByApplication)
                {
                    $dateMinProdByApplication = $blp['minDate'];
                }
            }

            $tabSyntheseGeneralaProd = 0;
            $tabSyntheseGeneralaRupture = 0;
            $tabSyntheseGeneralaSupp = 0;
            foreach ($tabSyntheseStatut as $tab)
            {
                foreach ($tab as $tab1) {
                    foreach ($tab1 as $u => $v) {
                        if ($u == 9) {
                            $tabSyntheseGeneralaSupp += intval($v);
                        }
                        if ($u == 10) {
                            $tabSyntheseGeneralaRupture  += intval($v);
                        }
                    }
                }
            }
            foreach ($filesBlProd as $tab)
            {
                $tabSyntheseGeneralaProd += intval($tab['totProd']);
            }
            dump($tabSyntheseGeneralaProd);
            //pagination
            $nbPages = ceil(count($nbTrackingByFile) / $nbParPage);
            if ($page > $nbPages) {
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
            }

            //retour d'une modification de BL ou fichier
            $blretour = null;
            if ( $blR != 0){
                $blretour = $blR;
            }
            return $this->render('TMDProdBundle:Prod:SuiviProdCourant.html.twig', array(
                'idOperation'               => $idOpe,
                'idClient'                  => $idClient,
                'operation'                => $operationCourante,
                'nbPages'                   => $nbPages,
                'page'                      => $page,
                'files'                     => $fileByOpe,
                'filesTot'                  => $fileTotByOp,
                'filesBLtot'                => intval($fileBLTotByOp[0][1]),
                'nbTrByFile'                => $trackings,
                'nbBlbyfile'                => $filesBl,
                'nbBlByFileProd'            => $filesBlProd,
                'nbFichiersPage'            => $nbFichierPage,
                'dateMaxProdByApplication'  =>$dateMaxProdByApplication,
                'dateMinProdByApplication'  =>$dateMinProdByApplication,
                'TypeTransport'             => $modeTransport,
                'syntheseTransport'         => $transportCount,
                'syntheseArticle'           => $syntheseArticles,
                'blRetour'                  => $blretour,
                'syntheseStatut'            => $syntheseStatut,
                'tabsyntheseStatut'         => $tabSyntheseStatut,
                'tabSyntheseGeneralaProd'   => $tabSyntheseGeneralaProd,
                'tabSyntheseGeneralaSupp'   => $tabSyntheseGeneralaSupp,
                'tabSyntheseGeneralaRupture'=> $tabSyntheseGeneralaRupture,
                'nbBLWtihDepotNull'         => $TrDepotNull,
                'idOpe'                     => $idOpe,
                'blR'                       => $blR
            ));
        }

        return $this->render('TMDProdBundle:Prod:SuiviProdCourant.html.twig', array(
                'operations' => $operationsCournates
        ));
    }


    public function donneBlNonProdAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('annonce_idd');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlNonProdByFile($id);
            $articles = $em->getRepository('TMDProdBundle:EcommLignes')->findArticlesByFileArrayNonProd($id);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommLignes')->findArticlesPersoByFileArrayNonProd($id);

            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }
            $numBLs = array();
            foreach ($allBlByOpe as $key => $file) {
                if ($file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT') {
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ($file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS') {
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }

            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpe as $key => $item) {

                if (isset($statuts[$item['numbl']])) {
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
//
                } else {
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                }
            }

            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray, 'perso' => $articlesPersos);

            return new JsonResponse($reponse);
        }

        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function donneDepotNullAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('annonce_depot');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlDepotNullByFile($id);

            $listBL = array();
            foreach ($allBlByOpe as $bl){
                array_push($listBL, $bl[0]['numbl']);
            }

            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesRuptureByFileArray($listBL);
//            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByFileArray($id);
            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpe as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }
            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpe as $key=>$item){
                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                }

            }
            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray);
            return new JsonResponse($reponse);

        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function donneTrackingsByFileAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('annonce_idbl');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlByFile($id);
            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFileArray($id);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByFileArray($id);
            dump($articlesPersos);
            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpe as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }


            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            dump($statuts);
            dump($allBlByOpe);
            $tabBlComplet = array();
            foreach ($allBlByOpe as $key=>$item){
                $histoStatut = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlASC($item['numbl']);
                dump($histoStatut);
                $historiqueStat =[];

                for ($i=0; $i < count($histoStatut); $i++) {
                    if ($histoStatut != [] and $histoStatut[$i]['idstatut'] !=0){
                    $historiqueStat[$i]['idstatut'] = $histoStatut[$i]['idstatut'];
                    dump($em->getRepository('TMDProdBundle:EcommStatut')->find($histoStatut[$i]['idstatut']));
                    $historiqueStat[$i]['statut'] = $em->getRepository('TMDProdBundle:EcommStatut')->find($histoStatut[$i]['idstatut'])->getStatut();
                    $historiqueStat[$i]['observation'] = $histoStatut[$i]['observation'];
                    $historiqueStat[$i]['datestatut'] = $histoStatut[$i]['datestatut'];

                } else {

                    $historiqueStat[$i]['idstatut'] = $histoStatut[$i]['idstatut'];
                    $historiqueStat[$i]['observation'] = $histoStatut[$i]['observation'];
                    $historiqueStat[$i]['datestatut'] = $histoStatut[$i]['datestatut'];
                    }

                }

                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                    $tabBlComplet[$key]['histoStatut'] = $historiqueStat;
                }
//                $statutLibelle = $this->container->get('tmd_getInfo');
//                $tabBlComplet[$key]['statutLibelle'] = $statutLibelle->getStatutWithId($tabBlComplet[$key]['idStatut'])->getStatut();
            }
            dump($tabBlComplet);
            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray, 'perso' => $articlesPersos);
            return new JsonResponse($reponse);

        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function annuleTrackingAction (Request $request){
        $numbl = $request->get('numbl');
        dump($numbl);
        $em = $this->getDoctrine()->getManager();

        $ligne = $em->getRepository('TMDProdBundle:EcommLignes')->findBlNumligne($numbl);
        $numLigne = $ligne[0]->getNumligne()->getNumligne();
        $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->trackingByNumligne($numLigne);
        dump($tracking[0][0]);
        $statut = $em->getRepository('TMDProdBundle:EcommStatut')->find(9);
        dump($statut);
        $abregeStatut = $statut->getAbregestatut();
        $observation="commande annulée par opérateur";
        $sm = $this->get('tmd_statut');
        dump($statut->getIdStatut());
        $sm->changeStatut($tracking[0][0],$abregeStatut,$observation, $this->getUser());
//
//        $tracking[0][0]->setIdStatut($statut);
//
//        $histoStatut = new EcommStatut();
//        $histoStatut->setDatestatut(new \DateTime());
//        $histoStatut->getIdStatut($statut->getIdStatut());
//        $histoStatut->setObservation("Commande annulée par opérateur");
//        $histoStatut->setNumbl($numbl);
//        $histoStatut->setIduser($user->getId());
//        $this->em->persist($histoStatut);
//
//        $em->flush();

//


        $response = array('ligne'=> $ligne, 'tracking'=>$tracking);

        return new JsonResponse($response);

    }


    public function donneRuptureByFileAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('rupture_idbl');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlRuptureByFile($id);

            $listBL = array();
            foreach ($allBlByOpe as $bl){
                array_push($listBL, $bl[0]['numbl']);
            }

            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesRuptureByFileArray($listBL);
//            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByFileArray($id);
            $articleArray = array();
            foreach ($articles as $key => $item) {
                $articleArray[$key] = $item;
            }

            $numBLs = array();
            foreach ($allBlByOpe as $key=>$file)
            {
                if ( $file['typeTransport'] == 'CPRV' OR $file['typeTransport'] == 'CPRVE' OR $file['typeTransport'] == 'CPRVTC' OR $file['typeTransport'] == 'CPRVT'){
                    $numBLs['CPRV']['numBl'][$key] = $file['numbl'];
                }
                if ( $file['typeTransport'] == 'DPD' OR $file['typeTransport'] == 'DPDPREDI' OR $file['typeTransport'] == 'DPDRELAIS'){
                    $numBLs['DPD']['numBl'][$key] = $file['numbl'];
                }
            }

            $statuts = array();
            if ( isset($numBLs['CPRV']['numBl'])) {
                $trackingColisPrive = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByBL($numBLs['CPRV']['numBl']);
                foreach ($trackingColisPrive as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }
            if (isset($numBLs['DPD']['numBl']) ) {
                $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByBL($numBLs['DPD']['numBl']);
                foreach ($trackingDpd as $cp)
                {
                    $statuts[$cp['numbl']]['libelle'] = $cp['libelle'];
                    $statuts[$cp['numbl']]['dateStatut'] = $cp['dateStatut'];
                }
            }

            $tabBlComplet = array();
            foreach ($allBlByOpe as $key=>$item){
                if (isset($statuts[$item['numbl']])){
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut'] = $statuts[$item['numbl']];
                }
                else{
                    $tabBlComplet[$key] = $item;
                    $tabBlComplet[$key]['statut']['libelle']="";
                    $tabBlComplet[$key]['statut']['dateStatut']="";
                }
//                $statutLibelle = $this->container->get('tmd_getInfo');
//                $tabBlComplet[$key]['statutLibelle'] = $statutLibelle->getStatutWithId($tabBlComplet[$key]['idStatut'])->getStatut();
            }

            $reponse = array('add' => $tabBlComplet, 'articles' => $articleArray);
            return new JsonResponse($reponse);

        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function exportResteProdExcelAction($idoperation)
    {
        $em = $this->getDoctrine()->getManager();

        $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommLignes')->findArticlesByOpeNonProd($idoperation);

        $header = array('Code article' => 'string',
                        'Libelle'=> 'string',
                        'Reste à produire'=> 'number'
                    );

        $tabBlComplet2 = array();
        foreach ($ArticlesSyntheseNonProd as $k=>$v){
            array_push($tabBlComplet2,[
                $v['codearticle'],
                $v['libelle'],
                $v['quantite']
            ]);
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);

            $nomExport = preg_replace ('/\s+/',"_",($ArticlesSyntheseNonProd[0]['appliname']));

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'_resteAproduire.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportRuptureProdExcelAction($idoperation)
    {
        $em = $this->getDoctrine()->getManager();

        $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpRupture($idoperation);

        $header = array('Code article' => 'string',
            'Libelle'=> 'string',
            'Qté en rupture'=> 'number'
        );

        $tabBlComplet2 = array();
        foreach ($ArticlesSyntheseNonProd as $k=>$v){

            array_push($tabBlComplet2,[
                $v['codearticle'],
                $v['libelle'],
                $v['quantite']
            ]);
        }

        $writer = new XLSXWriter();
        $writer->writeSheet($tabBlComplet2,'extraction',$header);

        $nomExport = preg_replace ('/\s+/',"_",($ArticlesSyntheseNonProd[0]['appliname']));

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment ;filename='.$nomExport.'_article_rupture.xlsx',
                'Content-Transfer-Encoding' =>  'binary',
                'Cache-Control' => 'must-revalidate',
                'Pragma' =>  'public'
            )
        );
    }


    public function exportSynthesebyDateAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $date = $request->get('infoDate');
            $idAppli = $request->get('infoAppli');

            $em = $this->getDoctrine()->getManager();

            $nbBlByFile = $em->getRepository('TMDProdBundle:EcommBl')->findBlByOpeByDate( $date, $idAppli);
            $bls = array();
            foreach ($nbBlByFile as $f=>$k){
                array_push($bls,intval($k->getBl()->getNumbl()) ) ;
            }

            $filesBl= array();

            $ArticlesByBl = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bls);
            $syntheseArticles=[];
            foreach($ArticlesByBl as $article)
            {
                $syntheseArticles[$article['flagart']][$article['libelle']]= $article['quantite'];
            }

            $countTransportbyFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbTransportByBls($bls);
            foreach ($countTransportbyFile  as $bl)
            {
                $filesBl[$bl['modexp']] = $bl[1];
            }

            return new JsonResponse(array(sizeof($bls), $syntheseArticles, $filesBl));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function exportSynthesebyDateDepotAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $date = $request->get('infoDate');
            $idAppli = $request->get('infoAppli');

            $em = $this->getDoctrine()->getManager();

            $nbBlByFile = $em->getRepository('TMDProdBundle:EcommBl')->findBlByOpeByDateDepot( $date, $idAppli);
            dump($nbBlByFile);
            $bls = array();
            foreach ($nbBlByFile as $f=>$k){
                array_push($bls,intval($k->getBl()->getNumbl()) ) ;
            }

            $filesBl= array();

            $ArticlesByBl = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bls);

            $syntheseArticles=[];
            foreach($ArticlesByBl as $article)
            {
                $syntheseArticles[$article['flagart']][$article['libelle']]= $article['quantite'];
            }

            $countTransportbyFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbTransportByBls($bls);
            foreach ($countTransportbyFile  as $bl)
            {
                $filesBl[$bl['modexp']] = $bl[1];
            }
            dump($countTransportbyFile);
            dump($syntheseArticles);
            dump($filesBl);
            return new JsonResponse(array(sizeof($bls), $syntheseArticles, $filesBl));

        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function calendarAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('idappli');

            $em = $this->getDoctrine()->getManager();
            //test
            $test = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlProduitsByDate($id);

//            $tab = array(["title" =>"New Event","start" =>"2017-11-24","end" =>"2017-11-26","allDay" => 0 ]);

            return new JsonResponse( $test );
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function calendarDepotAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('idappli');

            $em = $this->getDoctrine()->getManager();
            //test
            $test = $em->getRepository('TMDProdBundle:EcommBl')->findNbBlDepotByDate($id);

//            $tab = array(["title" =>"New Event","start" =>"2017-11-24","end" =>"2017-11-26","allDay" => 0 ]);

            return new JsonResponse( $test );
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function articleByBlAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $numBl = $request->get('numBl');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFileBl($numBl);
            $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->findTrackingByBl($numBl);
            $statut = $em->getRepository('TMDProdBundle:EcommTracking')->findStatutByBl($numBl);
            $histStatut = $em->getRepository('TMDProdBundle:EcommHistoStatut')->donneHistoByBlASC($numBl);
            $historiqueStat =[];
            for ($i=0; $i < count($histStatut); $i++) {
                if ($histStatut != [] and $histStatut[$i]['idstatut'] !=0){
                    $historiqueStat[$i]['idstatut'] = $histStatut[$i]['idstatut'];
                    dump($em->getRepository('TMDProdBundle:EcommStatut')->find($histStatut[$i]['idstatut']));
                    $historiqueStat[$i]['statut'] = $em->getRepository('TMDProdBundle:EcommStatut')->find($histStatut[$i]['idstatut'])->getStatut();
                    $historiqueStat[$i]['observation'] = $histStatut[$i]['observation'];
                    $historiqueStat[$i]['datestatut'] = $histStatut[$i]['datestatut'];

                } else {

                    $historiqueStat[$i]['idstatut'] = $histStatut[$i]['idstatut'];
                    $historiqueStat[$i]['observation'] = $histStatut[$i]['observation'];
                    $historiqueStat[$i]['datestatut'] = $histStatut[$i]['datestatut'];
                }

            }
            // récupérer le statut de la livraison lorsqu'il est disponible:
           $statutLiv = [];
            $numLigne = $tracking[0]['numligne'];
            dump($numLigne);
         if ($tracking[0]['typeTransport'] == "CPRVE" OR $tracking[0]['typeTransport'] == "CPRV" OR $tracking[0]['typeTransport'] == "CPRVTC" OR $tracking[0]['typeTransport'] == "CPRVT")
         {
             $trackingCprve = $emCP->getRepository('TMDColisPriveBundle:Trackings')->findStatutByNumligne($numLigne);
             dump($trackingCprve);
             if ($trackingCprve == []){
                 $statutLiv = "";
             } else {
             $statutLiv = $trackingCprve[0]['libelle'];}
         } elseif ($tracking[0]['typeTransport'] == "DPD" OR $tracking[0]['typeTransport'] == "DPDPREDI" OR $tracking[0]['typeTransport'] == "DPDRELAIS" ){

             $trackingDpd = $emDpd->getRepository('TMDDpdBundle:Trackings')->findStatutByNumligne($numLigne);
             if ( $trackingDpd == []){
                $statutLiv = "";
             }
             $statutLiv = $trackingDpd[0]['libelle'];
         } else {
             $statutLiv = "";
         }


            return new JsonResponse(array($allBlByOpe, $tracking, $statut, $historiqueStat, $statutLiv));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }


    public function donneAllTransportAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $allTransport= $em->getRepository('TMDAppliBundle:EcommTransport')->findallTransport();
            $allTransporteur= $em->getRepository('TMDAppliBundle:EcommTransporteurs')->findallTransporteur();
            $allTransporteurCompte= $em->getRepository('TMDAppliBundle:EcommCompteTransport')->findallTransporteurCompte();
            $allCompte= $em->getRepository('TMDAppliBundle:EcommCompte')->findallCompte();

            $arrayClientCompte = array();
            foreach ($allCompte as $v){
                    array_push($arrayClientCompte,$v['idclient']);
            }
            $allCLient= $em->getRepository('TMDProdBundle:Client')->findallCLientCompte($arrayClientCompte);

            return new JsonResponse(array($allTransport,$allTransporteur,$allTransporteurCompte,$allCompte,$allCLient));
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function SearchRuptAction( Request $request){

        $idOpe = $request->get('idope');

        $em = $this->getDoctrine()->getManager();
        $TrackingRupt = $em->getRepository('TMDProdBundle:EcommLignes')->findAllBlRuptureByOpe($idOpe);
        dump($TrackingRupt);
        return new JsonResponse(array($TrackingRupt));

    }

    public function AnnulTrackingAllAction (Request $request){
        $listTracking = $request->get('listtracking');
        dump($listTracking);

        $em = $this->getDoctrine()->getManager();
        foreach ($listTracking as $bl) {
            $ligne = $em->getRepository('TMDProdBundle:EcommLignes')->findBlNumligne($bl);
            $numLigne = $ligne[0]->getNumligne()->getNumligne();
            $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->trackingByNumligne($numLigne);
            dump($tracking[0][0]);
            $statut = $em->getRepository('TMDProdBundle:EcommStatut')->find(9);
            dump($statut);
            $abregeStatut = $statut->getAbregestatut();
            $observation = "commande annulée par opérateur";
            $sm = $this->get('tmd_statut');
            dump($statut->getIdStatut());
            $sm->changeStatut($tracking[0][0], $abregeStatut, $observation, $this->getUser());
        }

         return new Response('changement effectué',200);
    }

    public function SyntheseBlByDateProdAction(Request $request){
        $idope = $request->get('idope');
        $date = $request->get('date');
dump($date);
        $em = $this->getDoctrine()->getManager();
        $bls= $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisBl($date, $idope);

        dump($bls);

        return new JsonResponse($bls);
    }

    public function generate_pdfAction($idClient, $idOpe, $thisdate)
    {

        $em = $this->getDoctrine()->getManager();
        $options = new Options();
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);
        dump($thisdate);
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('TMDProdBundle:Client')->find($idClient);
        $ope = $em->getRepository('TMDProdBundle:EcommAppli')->find($idOpe);
        $logo = (base64_encode(stream_get_contents($ope->getAppliImage())));

        $listBl = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByDateProdByAppli($idOpe, $thisdate);
        if ($idClient == 713) {
            $colis['Enveloppe PRESS'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "ENVPRS");
            $colis['pochette'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "po");
            $colis['petit carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C1");
            $colis['moyen carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C2");
            $colis['grand carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C3");
            $colis['Enveloppe PRESS']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "ENVPRS");
            $colis['pochette']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "po");
            $colis['petit carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C1");
            $colis['moyen carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C2");
            $colis['grand carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C3");
            $totalColis[$value] = $colis['Enveloppe PRESS'][$value] + $colis['pochette'][$value] + $colis['petit carton'][$value] + $colis['moyen carton'][$value] + $colis['grand carton'][$value];
            $totalColis['PRIME'] = $colis['Enveloppe PRESS']['PRIME'] + $colis['pochette']['PRIME'] + $colis['petit carton']['PRIME'] + $colis['moyen carton']['PRIME'] + $colis['grand carton']['PRIME'];
            dump($colis);
            $totalArticles[$value] = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDate($idOpe,$thisdate,$value);
            $totalArticles['PRIME']  = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDate($idOpe,$thisdate,'PRIME');

            $typeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findTypeTransport($idOpe, $thisdate);

            $nbrTypeTransport['total'][$value] = 0;
            $nbrTypeTransport['total']['PRIME'] = 0;

            foreach ($typeTransport as $type) {
                $nbrTypeTransport[$type['typeTransport']][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'],"VPC");
                $nbrTypeTransport[$type['typeTransport']]['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'],"PRIME");
                $nbrTypeTransport['total'][$value] +=  $nbrTypeTransport[$type['typeTransport']][$value];
                $nbrTypeTransport['total']['PRIME'] += $nbrTypeTransport[$type['typeTransport']]['PRIME'];
                $idTransporteur = $em->getRepository('TMDAppliBundle:EcommTransport')->findByCodeTransport($type['typeTransport']);
                $tranches[$type['typeTransport']] = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);
                $tranches[$type['typeTransport']]['totalTarif'][$value] = 0;
                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] = 0;
                $tranches[$type['typeTransport']]['totalBl'][$value] = 0;
                $tranches[$type['typeTransport']]['totalBl']['PRIME'] = 0;
                if (isset($tranches[$type['typeTransport']]) && count($tranches[$type['typeTransport']]) > 0) {
                    for ($i = 0; $i < count($tranches[$type['typeTransport']]) - 2; $i++) {
                        if ($i == 0) {
                            $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsVPC($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlVPC = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tarifVPC = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            dump($tarifVPC);
                            if ($tarifVPC != []) {
                                $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBlVPC * $tarifVPC[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsPRIME($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlPRIME = $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tarifPRIME = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifPRIME != []) {
                                $tranches[$type['typeTransport']][$i]['PRIME']['tarif'] = $nbrBlPRIME * $tarifPRIME[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['tarif'];
                            }

                        } else {
                            $minusI = $i - 1;
                            $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTrancheVPC($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlVPC = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tarifVPC = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifVPC != []) {
                                $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBlVPC * $tarifVPC[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTranchePRIME($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlPRIME = $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tarifPRIME = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifPRIME != []) {
                                $tranches[$type['typeTransport']][$i]['PRIME']['tarif'] = $nbrBlPRIME * $tarifPRIME[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['tarif'];
                            }
                        }
                    }
                }
            }
        } else if ($idClient == 712){
            $colis['Enveloppe PRESS'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "ENVPRS");
            $colis['pochette'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "po");
            $colis['petit carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C1");
            $colis['moyen carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C2");
            $colis['grand carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "VPC", "C3");
            $colis['Enveloppe PRESS']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "ENVPRS");
            $colis['pochette']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "po");
            $colis['petit carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C1");
            $colis['moyen carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C2");
            $colis['grand carton']['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "PRIME", "C3");
            $colis['Enveloppe PRESS']['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "REASS", "ENVPRS");
            $colis['pochette']['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "REASS", "po");
            $colis['petit carton']['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "REASS", "C1");
            $colis['moyen carton']['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "REASS", "C2");
            $colis['grand carton']['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, "REASS", "C3");
            $totalColis[$value] = $colis['Enveloppe PRESS'][$value] + $colis['pochette'][$value] + $colis['petit carton'][$value] + $colis['moyen carton'][$value] + $colis['grand carton'][$value];
            $totalColis['PRIME'] = $colis['Enveloppe PRESS']['PRIME'] + $colis['pochette']['PRIME'] + $colis['petit carton']['PRIME'] + $colis['moyen carton']['PRIME'] + $colis['grand carton']['PRIME'];
            $totalColis['REASS'] = $colis['Enveloppe PRESS']['REASS'] + $colis['pochette']['REASS'] + $colis['petit carton']['REASS'] + $colis['moyen carton']['REASS'] + $colis['grand carton']['REASS'];
            dump($colis);
            $totalArticles[$value] = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDate($idOpe,$thisdate,$value);
            $totalArticles['PRIME']  = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDate($idOpe,$thisdate,'PRIME');
            $totalArticles['REASS']  = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDate($idOpe,$thisdate,'REASS');

            $typeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findTypeTransport($idOpe, $thisdate);

            $nbrTypeTransport['total'][$value] = 0;
            $nbrTypeTransport['total']['PRIME'] = 0;
            $nbrTypeTransport['total']['REASS'] = 0;

            foreach ($typeTransport as $type) {
                $nbrTypeTransport[$type['typeTransport']][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'],"VPC");
                $nbrTypeTransport[$type['typeTransport']]['PRIME'] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'],"PRIME");
                $nbrTypeTransport[$type['typeTransport']]['REASS'] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'],"REASS");
                $nbrTypeTransport['total'][$value] +=  $nbrTypeTransport[$type['typeTransport']][$value];
                $nbrTypeTransport['total']['PRIME'] += $nbrTypeTransport[$type['typeTransport']]['PRIME'];
                $nbrTypeTransport['total']['REASS'] += $nbrTypeTransport[$type['typeTransport']]['REASS'];
                $idTransporteur = $em->getRepository('TMDAppliBundle:EcommTransport')->findByCodeTransport($type['typeTransport']);
                $tranches[$type['typeTransport']] = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);
                $tranches[$type['typeTransport']]['totalTarif'][$value] = 0;
                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] = 0;
                $tranches[$type['typeTransport']]['totalTarif']['REASS'] = 0;
                $tranches[$type['typeTransport']]['totalBl'][$value] = 0;
                $tranches[$type['typeTransport']]['totalBl']['PRIME'] = 0;
                $tranches[$type['typeTransport']]['totalBl']['REASS'] = 0;
                if (isset($tranches[$type['typeTransport']]) && count($tranches[$type['typeTransport']]) > 0) {
                    for ($i = 0; $i < count($tranches[$type['typeTransport']]) - 2; $i++) {
                        if ($i == 0) {
                            $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsVPC($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlVPC = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tarifVPC = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            dump($tarifVPC);
                            if ($tarifVPC != []) {
                                $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBlVPC * $tarifVPC[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsPRIME($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlPRIME = $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tarifPRIME = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifPRIME != []) {
                                $tranches[$type['typeTransport']][$i]['PRIME']['tarif'] = $nbrBlPRIME * $tarifPRIME[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsREASS($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlREASS = $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['REASS'] += $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'];
                            $tarifREASS = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifREASS != []) {
                                $tranches[$type['typeTransport']][$i]['REASS']['tarif'] = $nbrBlPRIME * $tarifREASS[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['REASS'] += $tranches[$type['typeTransport']][$i]['REASS']['tarif'];
                            }

                        } else {
                            $minusI = $i - 1;
                            $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTrancheVPC($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlVPC = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                            $tarifVPC = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifVPC != []) {
                                $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBlVPC * $tarifVPC[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTranchePRIME($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlPRIME = $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['nombreBl'];
                            $tarifPRIME = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifPRIME != []) {
                                $tranches[$type['typeTransport']][$i]['PRIME']['tarif'] = $nbrBlPRIME * $tarifPRIME[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['PRIME'] += $tranches[$type['typeTransport']][$i]['PRIME']['tarif'];
                            }
                            $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTrancheREASS($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBlREASS = $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl']['REASS'] += $tranches[$type['typeTransport']][$i]['REASS']['nombreBl'];
                            $tarifREASS = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarifREASS != []) {
                                $tranches[$type['typeTransport']][$i]['REASS']['tarif'] = $nbrBlREASS * $tarifREASS[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif']['REASS'] += $tranches[$type['typeTransport']][$i]['REASS']['tarif'];
                            }
                        }
                    }
                }
            }
        } else {
            $colis['pochette'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "po");
            $colis['petit carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C1");
            $colis['moyen carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C2");
            $colis['grand carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C3");
            $totalColis = $colis['pochette'] + $colis['petit carton'] + $colis['moyen carton'] + $colis['grand carton'];
            $typeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findTypeTransport($idOpe, $thisdate);
            $totalArticles = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDateNoType($idOpe,$thisdate);
            $nbrTypeTransport = [];
            foreach ($typeTransport as $type) {
                $nbrTypeTransport[$type['typeTransport']] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransport($idOpe, $thisdate, $type['typeTransport']);
                $idTransporteur = $em->getRepository('TMDAppliBundle:EcommTransport')->findByCodeTransport($type['typeTransport']);
                $tranches[$type['typeTransport']] = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);
                $tranches[$type['typeTransport']]['totalTarif'] = 0;
                $tranches[$type['typeTransport']]['totalBl'] = 0;
                if (isset($tranches[$type['typeTransport']]) && count($tranches[$type['typeTransport']]) > 0) {
                    for ($i = 0; $i < count($tranches[$type['typeTransport']]) - 2; $i++) {
                        if ($i == 0) {
                            $tranches[$type['typeTransport']][$i]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoids($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBl = $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'] += $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);

                            if ($tarif != []) {
                                $tranches[$type['typeTransport']][$i]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'] += $tranches[$type['typeTransport']][$i]['tarif'];
                            }
                        } else {
                            $minusI = $i - 1;
                            $tranches[$type['typeTransport']][$i]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTranche($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBl = $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'] += $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarif != []) {
                                $tranches[$type['typeTransport']][$i]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'] += $tranches[$type['typeTransport']][$i]['tarif'];
                            }
                        }
                    }
                }
            }}


        setlocale(LC_TIME, "fr_FR.UTF-8", "fra");

        $dateFrench = strftime("%B %G", strtotime($thisdate));

        dump($tranches);
        dump($dateFrench);
        $html = $this->renderview('TMDProdBundle:Prod:pdf.html.twig', array(
            'typesTransport'=>$typeTransport,
            'nbreTypeTransport' => $nbrTypeTransport,
            'tranches' => $tranches,
            'client' => $client,
            'idclient' =>$idClient,
            'appli' => $ope,
            'month' => $dateFrench,
            'totalBl'=> count($listBl),
            'logo' => $logo,
            'colis' => $colis,
            'totalColis'=> $totalColis,
            'totalArticles' => $totalArticles
        ));

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return new Response($dompdf->stream($client->getNomclient()." - Préfacturation - ".$dateFrench, [
            'Attachment'=> true
        ]));


    }

    public function PrefacturationAction($idClient, $idOpe, $choix){


        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('TMDProdBundle:Client')->find($idClient);
        $ope = $em->getRepository('TMDProdBundle:EcommAppli')->find($idOpe);

        $logo = (base64_encode(stream_get_contents($ope->getAppliImage())));
        $date = date('Y-m');
        dump($date);
        $minusMonth = strtotime($date . "- 1 months");
        $lastmonth = date("Y-m", $minusMonth);
        dump($lastmonth);


        $tranches=[];
        if ($choix == 1) {
            $thisdate = $date;
        } else {
            $thisdate = $lastmonth;
        }
        $listBl = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByDateProdByAppli($idOpe, $thisdate, true);

        $typeCommande =[];
        foreach ( $listBl as $bl){
            if (!empty($bl['json'])) {
                $tabJson = json_decode($bl['json'], true);
                if (isset($tabJson['TYPE']) && !empty($tabJson['TYPE'])){
                    array_push($typeCommande,$tabJson['TYPE']);
                }
            }
        }
        $typeCommande = array_unique($typeCommande);
        $typeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findTypeTransport($idOpe, $thisdate);
        foreach ($typeTransport as $type){
            $idTransporteur = $em->getRepository('TMDAppliBundle:EcommTransport')->findByCodeTransport($type['typeTransport']);
            $tranches[$type['typeTransport']] = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);

        }
        if (count($typeCommande) > 1) {
            foreach ($typeCommande as $value) {
                $colis['Enveloppe PRESS'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, $value, "ENVPRS");
                $colis['pochette'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, $value, "po");
                $colis['petit carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, $value, "C1");
                $colis['moyen carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, $value, "C2");
                $colis['grand carton'][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDate($idOpe, $thisdate, $value, "C3");
                foreach ($colis as $k => $val){
                    if (isset($totalColis[$k]['total'])) {
                        $totalColis[$k]['total'] += $val[$value];
                    }else {
                        $totalColis[$k]['total'] = $val[$value];
                    }
                }
                $totalColis[$value] = $colis['Enveloppe PRESS'][$value] + $colis['pochette'][$value] + $colis['petit carton'][$value] + $colis['moyen carton'][$value] + $colis['grand carton'][$value];
                if (isset($totalColis['total'])) {
                    $totalColis['total'] += $totalColis[$value];
                } else {
                    $totalColis['total'] = $totalColis[$value];
                }
                $totalArticles[$value] = $em->getRepository('TMDProdBundle:EcommCmdep')->FindCountArticlesByOpeByDate($idOpe, $thisdate, $value);



                $nbrTypeTransport['total'][$value] = 0;
                foreach ($typeTransport as $type) {
                    $nbrTypeTransport[$type['typeTransport']][$value] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransportByType($idOpe, $thisdate, $type['typeTransport'], $value);
                    if (isset($nbrTypeTransport[$type['typeTransport']]['total'])) {
                        $nbrTypeTransport[$type['typeTransport']]['total'] += $nbrTypeTransport[$type['typeTransport']][$value];
                    } else {
                        $nbrTypeTransport[$type['typeTransport']]['total'] = $nbrTypeTransport[$type['typeTransport']][$value];
                    }
                    $nbrTypeTransport['total'][$value] += $nbrTypeTransport[$type['typeTransport']][$value];
                    $tranches[$type['typeTransport']]['totalTarif'][$value] = 0;
                    $tranches[$type['typeTransport']]['totalBl'][$value] = 0;

                    if (isset($tranches[$type['typeTransport']]) && count($tranches[$type['typeTransport']]) > 0) {
                        for ($i = 0; $i < count($tranches[$type['typeTransport']]) - 2; $i++) {

                            if ($i == 0) {
                                $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoidsbyType($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax'], $value);
                                if (isset($tranches[$type['typeTransport']][$i]['nombreBl'] )) {
                                    $tranches[$type['typeTransport']][$i]['nombreBl'] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                } else {
                                    $tranches[$type['typeTransport']][$i]['nombreBl'] = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                }
                                $nbrBl = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                                if ($tarif != []) {
                                    $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                    $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                                }

                            } else {
                                $minusI = $i - 1;
                                $tranches[$type['typeTransport']][$i][$value]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTrancheByType($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax'], $value);
                                if (isset($tranches[$type['typeTransport']][$i]['nombreBl'] )) {
                                    $tranches[$type['typeTransport']][$i]['nombreBl'] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                } else {
                                    $tranches[$type['typeTransport']][$i]['nombreBl'] = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                }
                                $nbrBl = $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                $tranches[$type['typeTransport']]['totalBl'][$value] += $tranches[$type['typeTransport']][$i][$value]['nombreBl'];
                                $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                                if ($tarif != []) {
                                    $tranches[$type['typeTransport']][$i][$value]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                    $tranches[$type['typeTransport']]['totalTarif'][$value] += $tranches[$type['typeTransport']][$i][$value]['tarif'];
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $colis['Enveloppe PRESS'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "ENVPRS");
            $colis['pochette'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "po");
            $colis['petit carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C1");
            $colis['moyen carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C2");
            $colis['grand carton'] = $em->getRepository('TMDProdBundle:EcommBl')->findColisByDateNoType($idOpe, $thisdate, "C3");
            $totalColis = $colis['Enveloppe PRESS'] + $colis['pochette'] + $colis['petit carton'] + $colis['moyen carton'] + $colis['grand carton'];
            $typeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findTypeTransport($idOpe, $thisdate);
            $totalArticles = $em->getRepository('TMDProdBundle:EcommCmdep')->FindArticlesByOpeByDateNoType($idOpe,$thisdate);
            $nbrTypeTransport = [];
            foreach ($typeTransport as $type) {
                $nbrTypeTransport[$type['typeTransport']] = $em->getRepository('TMDProdBundle:EcommBl')->findCountBlByTransport($idOpe, $thisdate, $type['typeTransport']);
                $idTransporteur = $em->getRepository('TMDAppliBundle:EcommTransport')->findByCodeTransport($type['typeTransport']);
                $tranches[$type['typeTransport']] = $em->getRepository('TMDCoreBundle:TransporteursTranche')->findByTransporteur($idTransporteur);
                $tranches[$type['typeTransport']]['totalTarif'] = 0;
                $tranches[$type['typeTransport']]['totalBl'] = 0;
                if (isset($tranches[$type['typeTransport']]) && count($tranches[$type['typeTransport']]) > 0) {
                    for ($i = 0; $i < count($tranches[$type['typeTransport']]) - 2; $i++) {
                        if ($i == 0) {
                            $tranches[$type['typeTransport']][$i]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByPoids($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBl = $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'] += $nbrBl;
                            $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);

                            if ($tarif != []) {
                                $tranches[$type['typeTransport']][$i]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'] += $tranches[$type['typeTransport']][$i]['tarif'];
                            }
                        } else {
                            $minusI = $i - 1;
                            $tranches[$type['typeTransport']][$i]['nombreBl'] = $em->getRepository('TMDProdBundle:EcommBL')->findcountBlByTranche($idOpe, $thisdate, $type['typeTransport'], $tranches[$type['typeTransport']][$minusI]['poidsMax'], $tranches[$type['typeTransport']][$i]['poidsMax']);
                            $nbrBl = $tranches[$type['typeTransport']][$i]['nombreBl'];
                            $tranches[$type['typeTransport']]['totalBl'] += $nbrBl;
                            $tarif = $em->getRepository('TMDCoreBundle:TransporteursTarif')->findTarif($idClient, $type['typeTransport'], $thisdate, $tranches[$type['typeTransport']][$i]['idTransportTranches']);
                            if ($tarif != []) {
                                $tranches[$type['typeTransport']][$i]['tarif'] = $nbrBl * $tarif[0]['tarif'];
                                $tranches[$type['typeTransport']]['totalTarif'] += $tranches[$type['typeTransport']][$i]['tarif'];
                            }
                        }
                    }
                }
            }}


        $listArticles = $this->getDoctrine()->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpeByDate($idOpe, $thisdate);

        setlocale(LC_TIME, "fr_FR.utf8", "fra");
        $dateFrench = ucwords(utf8_encode(strftime("%B %G", strtotime($thisdate))));
        dump($dateFrench);
        return $this->render('TMDProdBundle:Prod:prefacturation.html.twig', array(
            'listArticles' => $listArticles,
            'typeCommande'=> $typeCommande,
            'typesTransport' => $typeTransport,
            'nbreTypeTransport' => $nbrTypeTransport,
            'tranches' => $tranches,
            'client' => $client,
            'idclient'=> $idClient,
            'appli' => $ope,
            'month' => $dateFrench,
            'date' => $thisdate,
            'totalBl' => count($listBl),
            'logo' => $logo,
            'colis' => $colis,
            'totalColis' => $totalColis,
            'totalArticles'=> $totalArticles
        ));



    }



}
