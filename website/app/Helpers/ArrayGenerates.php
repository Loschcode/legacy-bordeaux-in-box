<?php

function generate_children_sex() {

  $final = ["0" => 'Fille ou garçon ?'];
  $final += Config::get('bdxnbx.children_sex_fields');

  return $final;

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