<?php

function generate_available_series_form($starter=TRUE) {

  $series = App\Models\DeliverySerie::withOrdersOnly()->orderBy('delivery', 'desc')->get();
  
  if ($starter)
    $form = [0 => '-'];
  else
    $form = [];

  foreach ($series as $serie) {

    $data = $serie->delivery;
    $label = $serie->id;

    $form[$label] = $data;

  }

  return $form;

}

function generate_delivery_fees($starter=TRUE) {

  $delivery_setting = App\Models\DeliverySetting::first();

  if ($starter)
    $form = [-1 => '-'];
  else
    $form = [];

  $form['take_away'] = 'Frais de point relais partenaire ('.euros(0).')';
  $form['regional_delivery_fees'] = 'Frais de livraison régionale ('.euros($delivery_setting->regional_delivery_fees).')';
  $form['national_delivery_fees'] = 'Frais de livraison nationale ('.euros($delivery_setting->national_delivery_fees).')';


  return $form;

}

function generate_delivery_prices($gift=FALSE, $starter=TRUE) {

  $delivery_prices = App\Models\DeliveryPrice::where('gift', '=', $gift)->orderBy('id', 'desc')->get();

  if ($starter)
    $form = [0 => '-'];
  else
    $form = [];

  foreach ($delivery_prices as $delivery_price) {

    $data = $delivery_price->title . ' (unité : '.euros($delivery_price->unity_price).' / fréquence : '.$delivery_price->frequency.')';
    $label = $delivery_price->id;

    $form[$label] = $data;

  }

  return $form;

}

function generate_children_sex() {

  $final = ["0" => 'Fille ou garçon ?'];
  $final += Config::get('bdxnbx.children_sex_fields');

  return $final;

}

function generate_note_type_form() {

  return [

    "general" => "Général",
    "remark" => "Remarque",
    "finances" => "Finances",
    "bug" => "Bugs",

  ];

}

function generate_priority_form() {

  return [

    "low" => "Basse",
    "medium" => "Normale",
    "high" => "Elevée",
    
    ];

}

function generate_unity_form() {

  return [

    "1" => "1 unité",
    "2" => "2 unité",
    "3" => "3 unité",
    "4" => "4 unité",
    "5" => "5 unité",
    "6" => "6 unité",
    "7" => "7 unité",
    "8" => "8 unité",
    "9" => "9 unité",
    "10" => "10 unité",
    
    ];

}

function generate_percent_form() {

  return [

    "0" => "0%",
    "5" => "5%",
    "10" => "10%",
    "20" => "20%",
    "30" => "30%",
    "40" => "40%",
    "50" => "50%",
    "60" => "60%",
    "70" => "70%",
    "80" => "80%",
    "90" => "90%",
    "100" => "100%",

    ];

}

function generate_month_form() {

  return [

    "0" => '-',
    "01" => 'en Janvier',
    "02" => 'en Février',
    "03" => 'en Mars',
    "04" => 'en Avril',
    "05" => 'en Mai',
    "06" => 'en Juin',
    "07" => 'en Juillet',
    "08" => 'en Août',
    "09" => 'en Septembre',
    "10" => 'en Octobre',
    "11" => 'en Novembre',
    "12" => 'en Décembre'

    ];
}
