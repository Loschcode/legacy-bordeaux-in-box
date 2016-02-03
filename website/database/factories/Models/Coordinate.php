<?php
/*
|--------------------------------------------------------------------------
| Coordinate Model Factories
|--------------------------------------------------------------------------
|
|
*/

/**
 * Mock coordinate
 */
$factory->define(App\Models\Coordinate::class, function(Faker\Generator $faker) {
  
  return [

    'place_id' => 'place_' . str_random(8),
    'address' => $faker->address,
    'zip' => rand(10000,99999),
    'city' => 'Ville #' . rand(0,1000),
    'country' => 'France',
    'latitude' => rand(0,100),
    'longitude' => rand(0,100),
    'formatted_address' => '',
    'raw' => '',

  ];

});