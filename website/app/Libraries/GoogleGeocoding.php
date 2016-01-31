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

      $key = Config::get('services.google.geocoding.key');

      $full_address = urlencode("$address, $city $zip");

      $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$full_address&key=$key";
      $input = @file_get_contents($url);
      $datas = json_decode($input);

      dd($url);

      if (isset($datas->result)) return $datas;
      else return FALSE;

    }

}