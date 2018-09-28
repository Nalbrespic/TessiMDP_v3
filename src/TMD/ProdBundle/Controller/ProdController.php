<?php

namespace TMD\ProdBundle\Controller;

use DateTime;
use PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use function PHPSTORM_META\type;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TMD\ProdBundle\Entity\EcommAppli;
use TMD\ProdBundle\Entity\InputData;
use TMD\ProdBundle\Form\EcommAppliType;
use TMD\ProdBundle\Form\InputDataType;

class ProdController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

//        $bl = $em->getRepository('TMDProdBundle:EcommTracking')->findLimit();


        return $this->render('TMDProdBundle:Prod:index.html.twig', array(
//            'bl' => $bl
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
            $allTrByFile = $emanager->getRepository('TMDProdBundle:EcommTracking')->findBy(array('idfile' => $id));


            foreach ($allTrByFile as $tr){
                $tr->setDateDepot($date);
            }

            $emanager->flush();




            return new JsonResponse(array( date_format($date, 'd-m-Y')));
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


            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByCmd($cmd,$idClient, $idope );



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





            return new JsonResponse($tabBlComplet);
        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }

    public function syntheseCokeAction(Request $request, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $artWoPSG = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokeFlagZero($date);
        $artPSG = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokePSG($date);
        $artCollector = $em->getRepository('TMDProdBundle:EcommBl')->syntheseMoisArtCokeCollector($date);
//        var_dump($artWoPSG);
//        var_dump($artCollector);

        $cmdArt = array();
        foreach ($artWoPSG as $k=>$v){
            if (!$v['flagart'] ) {
                if ( !preg_match('/nyle Emy L TR Ninety O/', $v['libelle'])
                    and !preg_match('/offret Plan de Pari/', $v['libelle'])
                        and !preg_match('/offret Caroussel Pa/', $v['libelle'])
                            and !preg_match('/Récla/', $v['libelle'])
                                and !preg_match('/Recla/', $v['libelle'])) {


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
                        elseif (preg_match('/ffret 6 cans Collector C/', $v['libelle'])){
                            $cmdArt[$v['numbl']] = intval($v[1])*6;
                        }
                        else{
                            $cmdArt[$v['numbl']] =  intval($v[1]);
                        }

                    }
                }
            }

//            $tag = false;
//            foreach ($artPSG as $ke=>$va){
//                if ($v['numbl'] == $va['numbl']){
//                    $cmdArt[$va['numbl']] =  (intval($v[1]) + intval($va[1])*8 );
//                    $tag = true;
//                }
//            }
//            foreach ($artCollector as $ke2=>$va2){
//                if ($v['numbl'] == $va2['numbl']){
//                    $cmdArt[$va2['numbl']] =  ( intval($va2[1])*6 );
//                    $tag = true;
//                }
//            }
//            if (!$tag){
//                $cmdArt[$v['numbl']] = intval($v[1]);
//            }

    }
//        var_dump($cmdArt);
//    var_dump(array_count_values($cmdArt));



        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("TMD")
            ->setLastModifiedBy("TMDj")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");




        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue( 'A1', 'Nbre de commande');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue( 'B1', 'quantite de bouteilles');

        ;

        $indice = 2;
        foreach (array_count_values($cmdArt) as $h=>$b) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $indice, $b)
                ->setCellValue('B' . $indice, $h);
            $indice ++;
        }


        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('A')->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')->setWidth(15);


        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
//        $phpExcelObject = $this->createContrevenantXSLObject($request);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=SyntheseNbBouteilles.xlsx');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }


    public function exportTrackingExcelAction(Request $request, $idFile, $prod)
    {
//        $idFile="3698,3683,3636,3628,3597,3572,3563,3545,3528,3510,3500,3495,3467,3437,3426,3425,3415,3407,3387,3381,3354,3353,3352,3350,3346,3338,3331,3317,3307,3299,3292,3281,3266,3256,3251,3237,3228,3216,3202,3189,3169,3158,3156,3141,3131,3127,3125,3119,3110,3097,3096,3071,3069,3064,3062,3047,3006";

//        $idFile="3698,3683,3636,3628";
        $idFiles = explode(",", $idFile);

        $em = $this->getDoctrine()->getManager();
        $emCP = $this->getDoctrine()->getManager('colisprive');
        $emDpd = $this->getDoctrine()->getManager('dpd');

//        $indiceProdorNot = $request->get('prod');

        $allBlByOpekey=array();

        if (sizeof($idFiles) >8) {

            $size = sizeof($idFiles);
            $index = 0;
            while ($size > 0) {
                $idFileb = array_splice($idFiles, 0, 8);

                $index = $index + 8;
                $size = $size - 8;


                if ($prod == 0) {
                    $allBlByOpeb = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlNonProdByFile($idFileb);
//                    var_dump($allBlByOpeb);
                    array_push($allBlByOpekey, $allBlByOpeb);
                } else {

                    $allBlByOpeb = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByFile($idFileb);
                    array_push($allBlByOpekey, $allBlByOpeb);
//                    var_dump($allBlByOpeb);

                }
            }
//var_dump($allBlByOpekey);
            $allBlByOpe = array();
            foreach ($allBlByOpekey as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    array_push($allBlByOpe,  $v1);
                }


            }

//            var_dump($allBlByOpe);
        }
        else{
            if ($prod == 0){
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlNonProdByFile($idFiles);
            }
            else{
                $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByFile($idFiles);
//                var_dump($allBlByOpe);
            }
        }


//var_dump($allBlByOpe);


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
//dump($allBlByOpe);
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
        $allBlByOpegggg = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlNonProdByFile($idFiles);

//        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
//
//        $phpExcelObject->getProperties()->setCreator("TMD")
//            ->setLastModifiedBy("TMDj")
//            ->setTitle("Office 2005 XLSX Test Document")
//            ->setSubject("Office 2005 XLSX Test Document")
//            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
//            ->setKeywords("office 2005 openxml php")
//            ->setCategory("Test result file");
//
//
////        $tit = array(
////            'A'   => 'Date fichier',
////            'B' => 'n° BL'
////        );
////        $i='1';
////        foreach ($tit as $k=>$v){
////            $phpExcelObject->setActiveSheetIndex(0)->setCellValue($k.$i,$v);
////            $i++;
////        }
//
//
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'A1', 'Date fichier');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'B1', 'n° BL');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'C1', 'Ref Client');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'D1', 'exp_ref');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'E1', 'n° Cmde Client');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'F1', 'Date Cmd');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'G1', 'Destinataire');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'H1', 'Rue');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'I1', 'Adresse 2');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'J1', 'Adresse 3');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'K1', 'Adresse 4');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'L1', 'Adresse 5');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'M1', 'Adresse 6');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'N1', 'CP');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'O1', 'Ville');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'P1', 'Pays');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'Q1', 'Instr_Livraison 1');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'R1', 'Instr_Livraison 2');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'S1', 'Date Dépôt');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'T1', 'N° Tracking');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'U1', 'Date Production');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'V1', 'Mode Expedition');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'W1', 'Type');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'X1', 'Poids (gr)');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'Y1', 'Qté');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'Z1', 'Date statut');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'AA1', 'Statut livraison');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'AB1', 'Nb pages');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'AC1', 'NbLignesCmde');
//        $phpExcelObject->setActiveSheetIndex(0)
//            ->setCellValue( 'AD1', 'Nom fichier');
//        ;
//
//        $dateZero = (new DateTime('-0001-11-30 00:00:00'));
//        $indice = 2;
//        foreach ($tabBlComplet as $ligne) {
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->setCellValue('A' . $indice, date_format($ligne['dateFile'], "d-m-Y h:m"))
//                ->setCellValue('B' . $indice, $ligne['numbl'])
//                ->setCellValue('C' . $indice, $ligne['refclient'])
//                ->setCellValue('D' . $indice, $ligne['expRef'])
//                ->setCellValue('E' . $indice, $ligne[0]['numCmdeClient'])
//                ->setCellValue('F' . $indice, date_format($ligne['dateCmde'], "d-m-Y"))
//                ->setCellValue('G' . $indice, $ligne['destinataire'])
//                ->setCellValue('H' . $indice, $ligne['destRue'])
//                ->setCellValue('I' . $indice, $ligne['destAd2'])
//                ->setCellValue('J' . $indice, $ligne['destAd3'])
//                ->setCellValue('K' . $indice, $ligne['destAd4'])
//                ->setCellValue('L' . $indice, $ligne['destAd5'])
//                ->setCellValue('M' . $indice, $ligne['destAd6'])
//                ->setCellValue('N' . $indice, $ligne['destCp'])
//                ->setCellValue('O' . $indice, $ligne['destVille'])
//                ->setCellValue('P' . $indice, $ligne['destPays'])
//                ->setCellValue('Q' . $indice, $ligne['instrLivrais1'])
//                ->setCellValue('R' . $indice, $ligne['instrLivrais2']);
//            if ($ligne['dateDepot'] == $dateZero) {
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('S' . $indice, "Non renseigné");
//            } else {
//                $date = date_format($ligne['dateDepot'], "d-m-Y h:m");
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('S' . $indice, $date);
//            }
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->setCellValue('T' . $indice, $ligne[0]['nColis']);
//            if ($ligne[0]['dateProduction'] == $dateZero) {
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('U' . $indice, "Non renseigné");
//            } else {
//                $date = date_format($ligne['dateProduction'], "d-m-Y h:m");
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('U' . $indice, $date);
//            }
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->setCellValue('V' . $indice, $ligne[0]['modexp'])
//                ->setCellValue('W' . $indice, $ligne['type'])
//                ->setCellValue('X' . $indice, ltrim($ligne['poidsReel'], "0"))
//                ->setCellValue('Y' . $indice, $ligne['quantite']);
//            if ($ligne['statut']['dateStatut'] == null) {
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('Z' . $indice, "");
//            } elseif ($ligne['statut']['dateStatut'] == $dateZero) {
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('Z' . $indice, "");
//            } else {
//                $date = date_format($ligne['statut']['dateStatut'], "d-m-Y ");
//                $phpExcelObject->setActiveSheetIndex(0)
//                    ->setCellValue('Z' . $indice, $date);
//            }
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->setCellValue('AA' . $indice, $ligne['statut']['libelle'])
//                ->setCellValue('AB' . $indice, $ligne['nbpages'])
//                ->setCellValue('AC' . $indice, $ligne['nbCmd'])
//                ->setCellValue('AD' . $indice, $ligne['filename']);
//            $indice++;
//        }
//
//        $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('A')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('B')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('C')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('D')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('E')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('F')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('G')->setWidth(25);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('H')->setWidth(40);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('I')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('J')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('K')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('L')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('M')->setWidth(12);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('N')->setWidth(6);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('O')->setWidth(20);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('P')->setWidth(5);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('Q')->setWidth(20);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('R')->setWidth(23);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('S')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('T')->setWidth(23);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('U')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('V')->setWidth(17);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('W')->setWidth(17);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('X')->setWidth(10);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('Y')->setWidth(8);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('Z')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('AA')->setWidth(20);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('AB')->setWidth(9);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('AC')->setWidth(15);
//            $phpExcelObject->setActiveSheetIndex(0)
//                ->getColumnDimension('AD')->setWidth(40);
//
//        if ( sizeof($idFiles) > 1) {
//            $nomExport = str_replace(' ','',$tabBlComplet[0]['appliname']);
//
//        }else{
//            $nomExport = preg_replace ('/\s+/',"_",($tabBlComplet[0]['filename']));
//        }
//        $phpExcelObject->getActiveSheet()->setTitle('Simple');
//        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
//        $phpExcelObject->setActiveSheetIndex(0);
////        $phpExcelObject = $this->createContrevenantXSLObject($request);
//        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
//        $response = $this->get('phpexcel')->createStreamedResponse($writer);
//        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
//        $response->headers->set('Content-Disposition', 'attachment;filename='.$nomExport.'.xlsx');
//        $response->headers->set('Pragma', 'public');
//        $response->headers->set('Cache-Control', 'maxage=1');
//var_dump($tabBlComplet);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Rob Gravelle')
            ->setLastModifiedBy('Rob Gravelle')
            ->setTitle('A Simple Excel Spreadsheet')
            ->setSubject('PhpSpreadsheet')
            ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
            ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
            ->setCategory('Test file');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="simple.xlsx"');
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache');

        $tabBlComplet2 = array(['Date fichier',
                                'n° BL',
                                'Ref Client',
                                'exp_ref',
                                'n° Cmde Client',
                                'Date Cmd',
                                'Destinataire',
                                'Rue',
                                'Adresse 2',
                                'Adresse 3',
                                'Adresse 4',
                                'Adresse 5',
                                'Adresse 6',
                                'CP',
                                'Ville',
                                'Pays',
                                'Instr_Livraison 1',
                                'Instr_Livraison 2',
                                'Date Dépôt',
                                'N° Tracking',
                                'Date Production',
                                'Mode Expedition',
                                'Type',
                                'Poids (gr)',
                                'Qté',
                                'Date statut',
                                'Statut livraison',
                                'Nb pages',
                                'NbLignesCmde',
                                'Nom fichier'
                            ]);
        
        foreach ($tabBlComplet as $k=>$v){
            array_push($tabBlComplet2,[date_format($v['dateFile'], "d-m-Y h:m"),
                                        $v['numbl'],
                                        $v['refclient'],
                                        $v['expRef'],
                                        $v[0]['numCmdeClient'],
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
                                        $v['instrLivrais1'],
                                        $v['instrLivrais2'],

                                        date_format($v['dateDepot'], "d-m-Y h:m"),
                                        $v[0]['nColis'],

                                        date_format($v['dateProduction'], "d-m-Y h:m"),
                                        $v[0]['modexp'],
                                        $v['type'],
                                        ltrim($v['poidsReel'], "0"),
                                        $v['quantite'],

                                        date_format($v['statut']['dateStatut'], "d-m-Y "),
                                        $v['statut']['libelle'],
                                        $v['nbpages'],
                                        $v['nbCmd'],
                                        $v['filename']
                                    ]);
        }
//        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
//        $spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(6);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(9);
        $spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(40);


//        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Hello World !');
        $spreadsheet->getActiveSheet()
            ->fromArray(
                $tabBlComplet2,  // The data to set
                ['30-11-1999',NULL],        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//                we want to set these values (default is A1)
            );
//        $spreadsheet->setActiveSheetIndex(0);
//        $spreadsheet->getActiveSheet()
//            ->getCell('B8')
//            ->setValue('Some value');

//        $spreadsheet->getActiveSheet()->setCellValue('A3', new DateTime());

        $writer =  IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;


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

            $allBlByOpeByDateProd = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByDateProdByAppli($idAppli, $date);

            $numBLs = array();
            foreach ($allBlByOpeByDateProd as $key=>$file)
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
            foreach ($allBlByOpeByDateProd as $key=>$item){
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






            return new JsonResponse($tabBlComplet);

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

        $em = $this
            ->getDoctrine()
            ->getManager();

        $clients = $em
            ->getRepository('TMDProdBundle:EcommAppli')
            ->findClientWithOperation();




        if ($idClient != 0)
        {
            $OperationsByClient = $em
                ->getRepository('TMDProdBundle:EcommAppli')
                ->findAppliByClient($idClient)
            ;

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
            $nbTrackingByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbTrackingByFile($idOpe);


            $filesPaginator = array();
            foreach ($fileByOpe as $f=>$k){
                $filesPaginator[$f] = $k['idfile'];
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




            $ArticlesByIdFile = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFile($filesPaginator);
            $ArticlesSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpe($files);
            $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommBl')->findArticlesByOpeByDateNonProd($files);
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
            }
            foreach($ArticlesSyntheseNonProd as $article)
            {
                $syntheseArticles['aProd'][$article['flagart']][$article['libelle']]= $article['quantite'];
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


            $quantiteBlaProduire = 0;
            foreach ($filesBl as $k=>$v)
            {
                if (isset($filesBlProd[$k]) && isset($v['nbBl'])){
                    $quantiteBlaProduire += $v['nbBl']-$filesBlProd[$k]['totProd'];
                }
                else{
                    if (isset($v['nbBl'])) {
                        $quantiteBlaProduire += $v['nbBl'];
                    }
                }
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
                'nbTrByFile'                => $trackings,
                'nbBlbyfile'                => $filesBl,
                'nbBlByFileProd'            => $filesBlProd,
                'quantiteBlaProduire'       => $quantiteBlaProduire,
                'dateMaxProdByApplication'  => $dateMaxProdByApplication,
                'dateMinProdByApplication'  => $dateMinProdByApplication,
                'TypeTransport'             => $modeTransport,
                'syntheseTransport'         => $transportCount,
                'syntheseArticle'           => $syntheseArticles


            ));
        }

        return $this->render('TMDProdBundle:Prod:suiviProd.html.twig', array(
            'clients'        => $clients,
            'OperationsByClient'  => $OperationsByClient,
            'idClient'      => $idClient,
        ));

    }

    public function viewSuiviCourantAction($idClient, $idOpe, $page, $nbFichierPage)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $em = $this->getDoctrine()->getManager();

        if ($idOpe == 0 ) {

            $operationsCournates = $em->getRepository('TMDProdBundle:EcommBl')->findOperationsCourantes();

            foreach ( $operationsCournates as $u=>$v)
            {
                if ( $v['appliImage'] != null) {
                    $operationsCournates[$u]['appliImage'] = (base64_encode(stream_get_contents($v['appliImage'])));
                }
            }
        }
        else{
            $operationsCournates = null;
        }

        if ($idOpe !=0 )
        {

            $operationCournate = $em->getRepository('TMDProdBundle:EcommAppli')->findBy(array('idappli' => $idOpe));
            foreach ( $operationCournate as $u) {
                if ($u->getAppliImage() != null) {
                    $operationCournate['appliImage'] = (base64_encode(stream_get_contents($operationCournate[0]->getAppliImage())));
                }
            }
            $nbParPage = $nbFichierPage;
            $fileByOpe = $em->getRepository('TMDProdBundle:EcommTracking')->findFileByOpe($idOpe, $page, $nbParPage);
            $fileTotByOp = $em->getRepository('TMDProdBundle:EcommTracking')->findFileTotByOpe($idOpe);
            $nbTrackingByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbTrackingByFile($idOpe);

            $filesPaginator = array();
            foreach ($fileByOpe as $f=>$k){
                $filesPaginator[$f] = $k['idfile'];
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




            $ArticlesByIdFile = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFile($filesPaginator);
            $ArticlesSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpe($files);
            $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommBl')->findArticlesByOpeByDateNonProd($files);
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
            foreach($ArticlesSyntheseNonProd as $article)
            {
                $syntheseArticles['aProd'][$article['flagart']][$article['libelle']]= $article['quantite'];
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


            $quantiteBlaProduire = 0;
            foreach ($filesBl as $k=>$v)
            {
                if (isset($filesBlProd[$k]) && isset($v['nbBl'])){
                    $quantiteBlaProduire += $v['nbBl']-$filesBlProd[$k]['totProd'];
                }
                else{
                    if (isset($v['nbBl'])) {
                        $quantiteBlaProduire += $v['nbBl'];
                    }
                }
            }

            //pagination
            $nbPages = ceil(count($nbTrackingByFile) / $nbParPage);
            if ($page > $nbPages) {
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
            }

            return $this->render('TMDProdBundle:Prod:SuiviProdCourant.html.twig', array(
                'idOperation'               => $idOpe,
                'idClient'                  => $idClient,
                'operation'                => $operationCournate,
                'nbPages'                   => $nbPages,
                'page'                      => $page,
                'files'                     => $fileByOpe,
                'filesTot'                  => $fileTotByOp,
                'nbTrByFile'                => $trackings,
                'nbBlbyfile'                => $filesBl,
                'nbBlByFileProd'            => $filesBlProd,
                'nbFichiersPage'            => $nbFichierPage,
                'quantiteBlaProduire'       => $quantiteBlaProduire,
                'dateMaxProdByApplication'  =>$dateMaxProdByApplication,
                'dateMinProdByApplication'  =>$dateMinProdByApplication,
                'TypeTransport'             => $modeTransport,
                'syntheseTransport'         => $transportCount,
                'syntheseArticle'           => $syntheseArticles
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

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlNonProdByFile($id);
            $articles = $em->getRepository('TMDProdBundle:EcommBl')->findArticlesByFileArrayNonProd($id);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommBl')->findArticlesPersoByFileArrayNonProd($id);

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


    public function donneTrackingsByFileAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $id = $request->get('annonce_idbl');
            $em = $this->getDoctrine()->getManager();
            $emCP = $this->getDoctrine()->getManager('colisprive');
            $emDpd = $this->getDoctrine()->getManager('dpd');

            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommBl')->findAllBlByFile($id);
            $articles = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFileArray($id);
            $articlesPersos = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesPersoByFileArray($id);

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
                                                //            $dateMaxProdByApplication = (new DateTime('1980-01-01 '))->format('Y-m-d h:i');

            $ArticlesByBl = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByBlforSynthese($bls);
                                            //            $ArticlesSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByOpe($files);
                                            //            $ArticlesSyntheseNonProd = $em->getRepository('TMDProdBundle:EcommBl')->findArticlesByOpeByDateNonProd($files);
//            $ArticlesReclaSynthese = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesReclaByFile($files);

//            $nbRetentionByFile = $em->getRepository('TMDProdBundle:EcommTracking')->findNbRetentionByFile($files);


//            $nbRetentionByFileS = array();
//            foreach($nbRetentionByFile as $k)
//            {
//                $nbRetentionByFileS[$k['idfile']] = $k;
//            }

//            $ArticlesReclaSyntheseTab = array();
//            foreach($ArticlesReclaSynthese as $k)
//            {
//                $ArticlesReclaSyntheseTab[$k['idfile']] = $k;
//            }
            $syntheseArticles=[];
            foreach($ArticlesByBl as $article)
            {
                $syntheseArticles[$article['flagart']][$article['libelle']]= $article['quantite'];
            }
                                            //            foreach($ArticlesSyntheseNonProd as $article)
                                            //            {
                                            //                $syntheseArticles['aProd'][$article['flagart']][$article['libelle']]= $article['quantite'];
                                            //            }


                                            //            $articles=[];
                                            //            foreach ($ArticlesByIdFile as $key=>$ar)
                                            //            {
                                            //                $articles[$ar['idfile']][$ar['flagart']][$ar['libelle']] =  ($ar['quantite']);
                                            //
//                                                        }
//
//            $modeTransport = $em->getRepository('TMDProdBundle:EcommBl')->findDistinctTransportByBls($bls);
//
//                foreach ($modeTransport as $transport)
//                {
//                    $filesBl[$transport['typeTransport']]=0;
//                }
                                            //                if ( $bl['maxDate'] > $dateMaxProdByApplication)
                                            //                {
                                            //                    $dateMaxProdByApplication = $bl['maxDate'];

                                            //                if ( array_key_exists($bl['idfile'], $ArticlesReclaSyntheseTab)) {
                                            //                    $filesBl[$bl['idfile']]['articlesReclamation'] = $ArticlesReclaSyntheseTab[$bl['idfile']]['q'];
                                            //                }


            $countTransportbyFile = $em->getRepository('TMDProdBundle:EcommBl')->findNbTransportByBls($bls);
            foreach ($countTransportbyFile  as $bl)
            {
                $filesBl[$bl['modexp']] = $bl[1];
            }

            // donne un tableau qui fait la synthese du transport: liste des transports utilisé avec le total des BL pour chacun
//            $transportCount=[];
//            foreach ($filesBl as $file)
//            {
//                foreach ($file['transport'] as $key=>$val) {
//                    if (isset($transportCount[$key])) {
//                        $transportCount[$key] = $transportCount[$key] + $val;
//                    } else {
//                        $transportCount[$key] = $val;
//                    }
//                }
//            }



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


    public function articleByBlAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $numBl = $request->get('numBl');
            $em = $this->getDoctrine()->getManager();


            $allBlByOpe = $em->getRepository('TMDProdBundle:EcommCmdep')->findArticlesByFileBl($numBl);













            return new JsonResponse($allBlByOpe);


        };
        return new Response("erreur: ce n'est pas du Json", 400);
    }

}
