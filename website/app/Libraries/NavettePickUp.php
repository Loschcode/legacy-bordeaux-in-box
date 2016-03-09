<?php

namespace App\Libraries;

/**
 * Navette PickUp
 * by Laurent Schaffner
 */

class NavettePickUp {

    /*$navette_pickup = \App\Libraries\NavettePickUp::findSpotsFromCoordinates(48.856614, 2.3522219000000177);
    dd($navette_pickup);*/

    /*protected static $web_site_id = "BDTEST13";
    protected static $web_site_key = "PrivateK";*/

    protected static $web_url = "http://navettefrontservice-uat.pickup-services.com/Navette.svc?wsdl"; 
    
    public static function findSpotsFromCoordinates($latitude=44.842532, $longitude=-0.580650) //$radius="20")
    {

      // Soap Client
      $client = new \SoapClient(self::$web_url, array('trace' => true)); // GetPudo

      // We prepare the request
      $pudo_request = new \StdClass();
      $pudo_request->lat= $latitude;
      $pudo_request->lng = $longitude;
      $pudo_request->SearchPudoType = "Arrival"; // Departure

      $parameters = new \StdClass();
      $parameters->Request = $pudo_request;

      // We get the results
      $raw_results = $client->GetPudo($parameters);

      // We collect the spots results
      $pudo_results = $raw_results->GetPudoResult;

      // If there's a problem (parameters might not be good)
      if (!isset($pudo_results->Pudos->Pudo))
        return FALSE;

      dd($pudo_results);

      foreach ($pudo_results->Pudos->Pudo as $spots) {

        dd($spots);

      }

      dd($pudo_results);

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