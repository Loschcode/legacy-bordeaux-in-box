<?php
/*
|--------------------------------------------------------------------------
| DeliverySpot Model Factories
|--------------------------------------------------------------------------
|
|
*/

/** 
 * Mock delivery spot
 */
$factory->define(App\Models\DeliverySpot::class, function(Faker\Generator $faker) {

  $coordinate = factory(App\Models\Coordinate::class)->create();

  return [

    'coordinate_id' => $coordinate->id,
    'name' => 'Point Relais #' . rand(0,1000),
    'schedule' => $faker->paragraph(45),
    'active' => rand(0,1),

  ];
  
});

/** 
 * Mock delivery spot
 */
$factory->defineAs(App\Models\DeliverySpot::class, 'unactive', function(Faker\Generator $faker) {

  $coordinate = factory(App\Models\Coordinate::class)->create();

  return [

    'coordinate_id' => $coordinate->id,
    'name' => 'Point Relais #' . rand(0,1000),
    'schedule' => $faker->paragraph(45),
    'active' => FALSE,

  ];
  
});

/** 
 * Mock delivery spot
 */
$factory->defineAs(App\Models\DeliverySpot::class, 'active', function(Faker\Generator $faker) {

  $coordinate = factory(App\Models\Coordinate::class)->create();

  return [

    'coordinate_id' => $coordinate->id,
    'name' => 'Point Relais #' . rand(0,1000),
    'schedule' => $faker->paragraph(45),
    'active' => TRUE,

  ];
  
});