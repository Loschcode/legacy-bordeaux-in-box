<?php

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitude_from Latitude of start point in [deg decimal]
 * @param float $longitude_from Longitude of start point in [deg decimal]
 * @param float $latitude_to Latitude of target point in [deg decimal]
 * @param float $longitude_to Longitude of target point in [deg decimal]
 * @param float $earth_radius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earth_radius)
 */
function calculate_distance($latitude_from, $longitude_from, $latitude_to, $longitude_to, $earth_radius=6371000) {
  
  // convert from degrees to radians
  $latFrom = deg2rad($latitude_from);
  $lonFrom = deg2rad($longitude_from);
  $latTo = deg2rad($latitude_to);
  $lonTo = deg2rad($longitude_to);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  
  return $angle * $earth_radius;

}


/**
 * Get the percent of unfinished buildings on a given series
 * @param  object $serie 
 * @return integer
 */
function get_percent_unfinished_buildings($serie) {

    
    $raw_percent = $serie->customer_order_buildings()->count() / ($serie->orders()->notCanceledOrders()->count() + $serie->customer_order_buildings()->count());
    $percent = $raw_percent * 100;
    return round($percent);

}

function convert_usd_to_cents($amount) {

  $raw_amount = str_replace('.', '', $amount);
  $converted_amount *= 100;

  return $converted_amount;

}

/**
 * Fetch randomly an array and take one entry
 * @param  array  $arr
 * @param  integer $num number of entries to select
 * @return mixed
 */
function array_random($arr, $num = 1) {

    shuffle($arr);
    
    $r = array();
    
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }

    return $num == 1 ? $r[0] : $r;

}