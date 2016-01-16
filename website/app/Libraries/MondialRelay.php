<?php

namespace App\Libraries;

/**
 * Downloaders
 * by Laurent Schaffner
 */

class MondialRelay {

    /*$mondial_relay = \App\Libraries\MondialRelay::findSpotsFromZipCode("33470");
    dd($mondial_relay['result']);*/

    protected static $web_site_id = "BDTEST13";
    protected static $web_site_key = "PrivateK";
    protected static $web_url = "http://api.mondialrelay.com/Web_Services.asmx?WSDL";
    protected static $web_url_short = "'http://api.mondialrelay.com/'";
    protected static $soap_encoding = "utf-8";
    protected static $code_country = "FR";

    public static function findSpotsFromZipCode($zip_code, $radius="20")
    {

      // API Request
      $web_service_request = 'WSI3_PointRelais_Recherche';

      // We generate the NUSOAP client
      $client = new \nusoap_client(self::$web_url, true);

      // We prepare the request
      $params = [

        'Enseigne' => self::$web_site_id,
        'Pays' => self::$code_country,

        //'NumPointRelais' => "",
        //'Ville' => "",
        'CP' => $zip_code,
        //'Latitude' => "", 'Longitude' => "",
        
        'Taille' => "",
        'Poids' => "",
        'Action' => "",
        'DelaiEnvoi' => "0",

        //'RayonRecherche' => $radius,

        //'TypeActivite' => "",
        //'NACE' => "", 
        
      ];

      // We generate the security key
      $code = implode("", $params);
      $code .= self::$web_site_key;
      $params["Security"] = strtoupper(md5($code));

      // We make the request itself
      $result = $client->call(

        $web_service_request,
        $params,
        self::$web_url_short,
        self::$web_url_short.$web_service_request

      );

      // We stop if there were any error during the process itself
      if ($client->fault) {

        return ['success' => FALSE, 'error' => $result];

      }

      $error = $client->getError();

      if ($error) {

        return ['success' => FALSE, 'error' => $error];

      }

      return ['success' => TRUE, 'result' => $result, 'request' => $client->request, 'response' => $client->response, 'debug' => $client->getDebug()];

  }

}