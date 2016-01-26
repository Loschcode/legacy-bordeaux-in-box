<?php

/**
 * Shortcut for diffHumans() from Date plugin (translation integrated)
 */
Html::macro('diffHumans', function($date, $diff=0) {

  if ($diff != 0) {

    $date_object = date_create($date);
    $date_object = date_modify($date_object, '-'.$diff.' day');
    $date = date_format($date_object,'Y-m-d');

  }

  return ucfirst(Date::createFromTimeStamp(strtotime($date))->diffForHumans());

});

/**
 * Format english date to french
 */
Html::macro('dateFrench', function($date, $month_text = false) {

  if ($month_text) {
    return Date::parse($date)->format('d F Y');
  }

  return Date::parse($date)->format('d-m-Y');

});
