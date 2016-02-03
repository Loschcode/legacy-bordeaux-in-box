<?php

/**
 * Mock delivery serie
 */
$factory->define(App\Models\DeliverySerie::class, function(Faker\Generator $faker) {

  $date = date('Y-m-d', strtotime("+7 day"));

  return [
    'delivery' => $date,
    'closed' => $date,
    'goal' => rand(30, 900)
  ];

});