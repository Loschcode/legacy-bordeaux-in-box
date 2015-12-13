<?php

/**
 * Get the percent of unfinished buildings on a given series
 * @param  object $serie 
 * @return integer
 */
function get_percent_unfinished_buildings($serie) {

    
    $raw_percent = $serie->user_order_buildings()->count() / ($serie->orders()->notCanceledOrders()->count() + $serie->user_order_buildings()->count());
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