<?php

namespace TMD\ZplBundle\WebService;


use BeSimple\SoapClient\SoapClient;
use Exception;
use SimpleXMLElement;
use TMD\AppliBundle\Controller\MTOM_ResponseReader;

class ColissimoBordereau

{
    public function sendFormulaire($env, $bls, $code)
    {
        define("SERVER_NAME", 'https://ws.colissimo.fr'); //TODO : Change server name

        if ($env != 'dev') {
            define("LABEL_FOLDER", './Colissimo/Formulaire/');
        }
        else {
            define("LABEL_FOLDER", '.\Colissimo\Formulaire\\');
        }




$requestParameter = array(

            'contractNumber' => '923108', //TODO : Change contractNumber
            'password' => 'ert4896L', //TODO : Change password
            'generateBordereauParcelNumberLists' => array(
                    'parcelsNumbers' => "9D44545454554544",
            ),

        );

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
        $xml = new \SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sls="http://sls.ws.coliposte.fr" />');
        $xml->addChild("soapenv:Header");
        $children = $xml->addChild("soapenv:Body");
        $children = $children->addChild("sls:generateBordereauByParcelsNumbers", null, "http://sls.ws.coliposte.fr");
//        $children = $children->addChild("generateBordereauByParcelsNumbersRequest", null, "");
        array_to_xml($requestParameter, $children);


        $requestSoap = $xml->asXML();
        //- Generate SOAPRequest
        //+ Call Web Service


        $resp = new SoapClient('https://ws.colissimo.fr/sls-ws/SlsServiceWS?wsdl', array('trace' => 1));



        $requestSoap2 = '<?xml version="1.0"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sls="http://sls.ws.coliposte.fr">';
        $requestSoap2 = $requestSoap2.'<soapenv:Header/><soapenv:Body><sls:generateBordereauByParcelsNumbers><contractNumber>923108</contractNumber><password>ert4896L</password><generateBordereauParcelNumberList>';
        foreach ($bls as $v){
            $requestSoap2 = $requestSoap2.'<parcelsNumbers>'.$v.'</parcelsNumbers>';
        }
        $requestSoap2 = $requestSoap2.'</generateBordereauParcelNumberList></sls:generateBordereauByParcelsNumbers></soapenv:Body></soapenv:Envelope>';
        try{
            $response = $resp->__doRequest($requestSoap2, SERVER_NAME . '/sls-ws/SlsServiceWS', '', SOAP_1_1, 0);
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
//            $soap_result = $resultat_tmp["data"];
            $resultat_tmp = $parseResponse->attachments;

            $label_content = $resultat_tmp[0];
            $my_datas = $label_content["data"];

            if ($code === '0'){
                $date = date("Y-m-d");
                $my_file_name = LABEL_FOLDER . "colissimo_".$date.".pdf";
            }else {
                $my_file_name = LABEL_FOLDER . "colissimo_".$bls[0].".pdf";
            }

            $my_file = fopen($my_file_name, 'w');

            if (fputs($my_file, $my_datas)) { //Save the label in defined folder
                fclose($my_file);
//                    echo "fichier ".$my_file_name." ok <br>";
            } else {
                echo "erreur ecriture etiquette <br>";
            }

//            if (sizeof($resultat_tmp) >1){
//                $CN23_content = $resultat_tmp[1];
//                $my_datasCN23 = $CN23_content['data'];
//                $my_file_name = LABEL_FOLDER."_CN23.pdf";
//                $my_file = fopen($my_file_name, 'w');
//                if (fputs($my_file, $my_datasCN23)) { //Save the label in defined folder
//                    fclose($my_file);
////                    echo "fichier ".$my_file_name." ok <br>";
//                } else {
//                    echo "erreur ecriture CN23 <br>";
//                }
//                return array('zpl' =>$my_datas, 'CN23' =>$my_datasCN23,'erreur' => '', 'nTRACK' => $parcelNumber);
//            }
            $message = explode("<messageContent>", $soap_result);
            $message = explode("</messageContent>", $message[1]);

            return array('message' =>$message[0], 'error' => '');
        } else { //Display errors if exist
            $error_message = explode("<messageContent>", $soap_result);
            $error_message = explode("</messageContent>", $error_message[1]);
            return array('message' => '' , 'error' => $error_code[0]." ".$error_message[0]);
        }
    }
}