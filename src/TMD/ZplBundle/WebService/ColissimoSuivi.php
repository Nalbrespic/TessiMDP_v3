<?php

namespace TMD\ZplBundle\WebService;


use BeSimple\SoapClient\SoapClient;
use Exception;
use TMD\AppliBundle\Controller\MTOM_ResponseReader;

class ColissimoSuivi
{
    public function sendSuivi($bls)
    {
        define("SERVER_NAME", 'https://ws.colissimo.fr'); //TODO : Change server name


        $resp = new SoapClient('https://www.coliposte.fr/tracking-chargeur-cxf/TrackingServiceWS?wsdl', array('trace' => 1));



        $requestSoap = '<?xml version="1.0"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"   xmlns:char="http://chargeur.tracking.geopost.com/">';
        $requestSoap = $requestSoap.'<soapenv:Header/><soapenv:Body><char:track><accountNumber>923108</accountNumber><password>ert4896L</password><skybillNumber>'.$bls.'</skybillNumber><apiKey></apiKey></char:track></soapenv:Body></soapenv:Envelope>';
//        foreach ($bls as $v){
//            $requestSoap = $requestSoap.'<skybillNumber>'.$v.'</skybillNumber>';
//        }
//        $requestSoap = $requestSoap.'<skybillNumber>'.$bls.'</skybillNumber>';
//        $requestSoap = $requestSoap.'</char:track></soapenv:Body></soapenv:Envelope>';

        try{
            $response = $resp->__doRequest($requestSoap, SERVER_NAME . '/sls-ws/SlsServiceWS', '', SOAP_1_1, 0);
        }catch (Exception $e){
            return array('zpl' =>'', 'erreur' => $e, 'CN23' =>'');
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

//            $label_content = $resultat_tmp[0];
//            $my_datas = $label_content["data"];
//            $date = date("Y-m-d");
//            $my_file_name = LABEL_FOLDER . "colissimo_".$date.".pdf";
//            $my_file = fopen($my_file_name, 'w');

//            if (fputs($my_file, $my_datas)) { //Save the label in defined folder
//                fclose($my_file);
//            } else {
//                echo "erreur ecriture etiquette <br>";
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