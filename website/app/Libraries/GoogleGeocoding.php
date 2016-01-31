<?php

namespace App\Libraries;

use Formatter, Response, Config;

/**
 * GoogleGeocoding
 * by Laurent Schaffner
 */

class GoogleGeocoding {

    public static function getCoordinates($address, $city, $zip)
    {

      $full_address = urlencode("$address, $city $zip");
    
      /**
       * If it didn't work
       */
      if (!$result = self::get_datas($full_address))
        return ['success' => FALSE, 'error' => 'Impossible to retrieve datas'];

      /**
       * If it worked we save it and return it
       * @var [type]
       */
      $location = $result->geometry->location;

      return [

              'success' => TRUE,
              'latitude' => $location->lat,
              'longitude' => $location->lng,

              ];

    }

    public static function get_datas($full_address)
    {

      $key = Config::get('services.google.geocoding.key');

      $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$full_address&key=$key";
      $input = @file_get_contents($url);
      $datas = json_decode($input);

      if (isset($datas->results[0])) return $datas->results[0];
      else return FALSE;

    }

}