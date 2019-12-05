<?php

namespace TMD\ZplBundle\WebService;


use BeSimple\SoapClient\SoapClient;
use Exception;
use SimpleXMLElement;
use TMD\AppliBundle\Controller\MTOM_ResponseReader;

class Colissimo

{
    public function send($productCode,$totalAmount,$montantAssurance, $bl, $dateF, $contentAll, $env)
    {
        define("SERVER_NAME", 'https://ws.colissimo.fr'); //TODO : Change server name

        if ($env != 'dev') {
            define("LABEL_FOLDER", '/tmdsws801/WS/');
            define("LABEL_FOLDER_CN", './Colissimo/Cn23/');

        } else {
            define("LABEL_FOLDER", '\\\\Tmdsws801\sg\sg_savoye\\');
            define("LABEL_FOLDER_CN", '.\Colissimo\Cn23\\');

        }

//        define("LABEL_FOLDER", './labels/'); //TODO : Change OutPut Folder: this is where the label will be saved

//Build the input request : adapt parameters according to your parcel info and options

        $destPays = $bl->getBl()->getNumligne()->getDestPays();
        if ($destPays == ''){
            $destPays = 'FR';
        }
        $destPays2 = $bl->getBl()->getNumligne()->getExpId()->getCodepays();
        if ($destPays2 == ''){
            $destPays2 = 'FR';
        }
        if ($productCode !== "CORE" and $productCode !== "CORI") {
            $requestParameter = array(

                'contractNumber' => '923108', //TODO : Change contractNumber
                'password' => 'ert4896L', //TODO : Change password
                'outputFormat' => array(
                    'y' => "10",
                    'outputPrintingType' => 'ZPL_10x15_203dpi'
                ),
                'letter' => array(
                    'service' => array(
                        'productCode' => $productCode,
                        'depositDate' => date_format($dateF, 'Y-m-d'),//TODO : Change depositDate (must be at least equal to current date)
                        'totalAmount' => $totalAmount,              // CN23
                        'orderNumber' => $bl->getBl()->getNumligne()->getExpRef(),
                        'commercialName' => 'Tessi'
                    ),
                    'parcel' => array(
                        'insuranceValue' => intval($montantAssurance),
                        'weight' => round(number_format(($bl->getBl()->getNumligne()->getPoids()) / 1000, 2, '.', ''), 1),
                        'nonMachinable' => '0',
                        'instructions' => $bl->getBl()->getNumligne()->getInstrLivrais1() . " " . $bl->getBl()->getNumligne()->getInstrLivrais2(),
                        'ftd' => '0'
                    ),
                    'customsDeclarations' => array(
                        'includeCustomsDeclarations' => '1',
                        'contents' => array(),
                    ),
                    'sender' => array(
                        'senderParcelRef' => $bl->getBl()->getNumligne()->getExpRef()." / ".$bl->getBl()->getNumligne()->getRefclient(),
                        'address' => array(
                            'companyName' => $bl->getBl()->getNumligne()->getExpId()->getNomclient(),
                            'lastName' => '',
                            'firstName' => '',
                            'line0' => '',
                            'line1' => '',
                            'line2' => $bl->getBl()->getNumligne()->getExpId()->getRue(),
                            'line3' => '',
                            'countryCode' => $destPays2,
                            'city' => $bl->getBl()->getNumligne()->getExpId()->getVille(),
                            'zipCode' => $bl->getBl()->getNumligne()->getExpId()->getCp(),
                            'phoneNumber' => $bl->getBl()->getNumligne()->getExpId()->getTel(),
                        )
                    ),
                    'addressee' => array(
                        'addresseeParcelRef' => $bl->getBl()->getNumligne()->getExpRef1(),
                        'codeBarForReference' => '0',
                        'address' => array(
                            'companyName' => $bl->getBl()->getNumligne()->getDestinataire(),
                            'lastName' => $bl->getBl()->getNumligne()->getDestAd2(),
                            'firstName' => '',
                            'line0' => $bl->getBl()->getNumligne()->getDestAd3(),
                            'line1' => $bl->getBl()->getNumligne()->getDestAd4(),
                            'line2' => $bl->getBl()->getNumligne()->getDestRue(),
                            'line3' => $bl->getBl()->getNumligne()->getDestAd5(),
                            'countryCode' => $destPays,
                            'city' => $bl->getBl()->getNumligne()->getDestVille(),
                            'zipCode' => $bl->getBl()->getNumligne()->getDestCp(),
                            'phoneNumber' => $bl->getBl()->getNumligne()->getDestTel(),
                            'mobileNumber' => '',
                            'email' => $bl->getBl()->getNumligne()->getDestMail()
                        )
                    )
                )
            );
        }
        else{

            $requestParameter = array(
                'contractNumber' => '923108', //TODO : Change contractNumber
                'password' => 'ert4896L', //TODO : Change password
                'outputFormat' => array(
                    'y' => "10",
                    'outputPrintingType' => 'ZPL_10x15_203dpi'
                ),
                'letter' => array(
                    'service' => array(
                        'productCode' => $productCode,
                        'depositDate' => date_format($dateF, 'Y-m-d'),//TODO : Change depositDate (must be at least equal to current date)
                        'totalAmount' => $totalAmount,              // CN23
                        'orderNumber' => $bl->getBl()->getNumligne()->getExpRef(),
                        'commercialName' => 'Tessi'
                    ),
                    'parcel' => array(
                        'insuranceValue' => intval($montantAssurance),
                        'weight' => round(number_format(($bl->getBl()->getNumligne()->getPoids()) / 1000, 2, '.', ''), 1),
                        'nonMachinable' => '0',
                        'instructions' => $bl->getBl()->getNumligne()->getInstrLivrais1() . " " . $bl->getBl()->getNumligne()->getInstrLivrais2(),
                        'ftd' => '0'
                    ),
                    'customsDeclarations' => array(
                        'includeCustomsDeclarations' => '1',
                        'contents' => array(),
                    ),
                    'sender' => array(
                        'senderParcelRef' => $bl->getBl()->getNumligne()->getExpRef1(),
                        'address' => array(
                            'companyName' => $bl->getBl()->getNumligne()->getDestinataire(),
                            'lastName' => '',
                            'firstName' => '',
                            'line0' => $bl->getBl()->getNumligne()->getDestAd3(),
                            'line1' => $bl->getBl()->getNumligne()->getDestAd4(),
                            'line2' => $bl->getBl()->getNumligne()->getDestRue(),
                            'line3' => $bl->getBl()->getNumligne()->getDestAd5(),
                            'countryCode' => $destPays,
                            'city' => $bl->getBl()->getNumligne()->getDestVille(),
                            'zipCode' => $bl->getBl()->getNumligne()->getDestCp(),
                            'phoneNumber' => $bl->getBl()->getNumligne()->getDestTel(),
                        )
                    ),
                    'addressee' => array(
                        'addresseeParcelRef' => $bl->getBl()->getNumligne()->getExpRef(),
                        'codeBarForReference' => '0',
                        'address' => array(
                            'companyName' => $bl->getBl()->getNumligne()->getExpId()->getNomclient(),
                            'lastName' => $bl->getBl()->getNumligne()->getDestAd2(),
                            'firstName' => '',
                            'line0' => '',
                            'line1' => '',
                            'line2' => $bl->getBl()->getNumligne()->getExpId()->getRue(),
                            'line3' => '',
                            'countryCode' => $bl->getBl()->getNumligne()->getExpId()->getCodepays(),
                            'city' => $bl->getBl()->getNumligne()->getExpId()->getVille(),
                            'zipCode' => $bl->getBl()->getNumligne()->getExpId()->getCp(),
                            'phoneNumber' => $bl->getBl()->getNumligne()->getExpId()->getTel(),
                            'mobileNumber' => '',
                            'email' => $bl->getBl()->getNumligne()->getExpId()->getEmail()
                        )
                    )
                )
            );
        }




        function array_to_xml($soapRequest, $soapRequestXml)
        {
            foreach ($soapRequest as $key => $value) {
                if (is_array($value)) {
                    if (!is_numeric($key)) {
                        $subnode = $soapRequestXml->addChild("$key");
                        array_to_xml($value, $subnode);
                    } else {
                        $subnode = $soapRequestXml->addChild("item$key");
                        array_to_xml($value, $subnode);
                    }
                } else {
                    $soapRequestXml->addChild("$key", htmlspecialchars("$value"));
                }
            }
        }


        //+ Generate SOAPRequest
        $xml = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" />');
        $xml->addChild("soapenv:Header");
        $children = $xml->addChild("soapenv:Body");
        $children = $children->addChild("sls:generateLabel", null, 'http://sls.ws.coliposte.fr');
        $children = $children->addChild("generateLabelRequest", null, "");
        array_to_xml($requestParameter, $children);
        $XmlArray = new \SimpleXMLElement($xml->asXML());
        $contents = $XmlArray->children("soapenv", true)->Body->children("sls", true)->generateLabel->children()->generateLabelRequest->letter->customsDeclarations->contents;
        foreach ($contentAll as $cont){
            $article = $contents->addChild('article');
            $article->addChild('description', $cont['libarticle']);
            $article->addChild('quantity', $cont['quantite']);
            $article->addChild('weight', number_format(($cont['poidsunitaire'])/1000, 2,'.',''));
            if ( $cont['prixht'] == '' OR $cont['prixht'] == null){
                $article->addChild('value', 10);
            }else {
                $article->addChild('value', intval($cont['prixht']));
            }
        }
        $category = $contents->addChild('category');
        $category->addChild('value', '2');

        $requestSoap = $XmlArray->asXML();
        //- Generate SOAPRequest

        //+ Call Web Service
        $checkOptions = array(
            'debug' => false,
            'cache_type' => null,
            'exceptions' => true,
            'user_agent' => 'BeSimpleSoap',
        );

        $resp = new SoapClient('https://ws.colissimo.fr/sls-ws/SlsServiceWS?wsdl', array('trace' => 1));

        try{
            $response = $resp->__doRequest($requestSoap, SERVER_NAME . '/sls-ws/SlsServiceWS', '', SOAP_1_1, 0);
        }catch (Exception $e){
            return array('zpl' =>'', 'erreur' => $e, 'CN23' =>'', 'nTRACK' => '');
        }

        $parseResponse = new MTOM_ResponseReader($response);
        $resultat_tmp = $parseResponse->soapResponse;
        $soap_result = $resultat_tmp["data"];
        $error_code = explode("<id>", $soap_result);
        $error_code = explode("</id>", $error_code[1]);
//- Parse Web Service Response

//+ Error handling and label saving
        if ($error_code[0] == "0") {
            //+ Write result to file <parcel number>.extension in defined folder (ex: ./labels/6A12091920617.zpl)
            $resultat_tmp = $parseResponse->soapResponse;
            $soap_result = $resultat_tmp["data"];
            $resultat_tmp = $parseResponse->attachments;

            $label_content = $resultat_tmp[0];
            $my_datas = $label_content["data"];
            //Save the label
            $my_extension_tmp = $requestParameter["outputFormat"]["outputPrintingType"];
            $my_extension = strtolower(substr($my_extension_tmp, 0, 3));
            $pieces = explode("<parcelNumber>", $soap_result);
            $pieces = explode("</parcelNumber>", $pieces[1]);
            $parcelNumber = $pieces[0]; //Extract the parcel number
            $my_file_name = LABEL_FOLDER . $bl->getBl()->getNumligne()->getExpRef() . "." . $my_extension;
            $my_file = fopen($my_file_name, 'w');

            if (fputs($my_file, $my_datas)) { //Save the label in defined folder
                fclose($my_file);
//                    echo "fichier ".$my_file_name." ok <br>";
            } else {
                echo "erreur ecriture etiquette <br>";
            }

            if (sizeof($resultat_tmp) >1){
                $CN23_content = $resultat_tmp[1];
                $my_datasCN23 = $CN23_content['data'];
                $my_file_name=LABEL_FOLDER_CN.$bl->getBl()->getNumligne()->getExpRef() ."_CN23.pdf";
                $my_file = fopen($my_file_name, 'w');
                if (fputs($my_file, $my_datasCN23)) { //Save the label in defined folder
                    fclose($my_file);
//                    echo "fichier ".$my_file_name." ok <br>";
                } else {
                    echo "erreur ecriture CN23 <br>";
                }
                return array('zpl' =>$my_datas, 'CN23' =>$my_datasCN23,'erreur' => '', 'nTRACK' => $parcelNumber);
            }


            return array('zpl' =>$my_datas,'CN23' =>'', 'erreur' => '', 'nTRACK' => $parcelNumber);
        } else { //Display errors if exist
            $error_message = explode("<messageContent>", $soap_result);
            $error_message = explode("</messageContent>", $error_message[1]);
//            echo 'error code : ' . $error_code[0] . "\n";
//            echo 'error message : ' . $error_message[0] . "\n";
            return array('zpl' =>'', 'CN23' =>'','erreur' => $error_code[0]." ".$error_message[0], 'nTRACK' => '');
        }
    }
}