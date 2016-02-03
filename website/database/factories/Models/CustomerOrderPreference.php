<?php
/*
|--------------------------------------------------------------------------
| CustomerOrderPreference Model Factories
|--------------------------------------------------------------------------
|
|
*/

/** 
 * Mock customer profile
 */
$factory->define(App\Models\CustomerOrderPreference::class, function(Faker\Generator $faker) {

  $customer_profile = factory(App\Models\CustomerProfile::class)->create();
  $delivery_spot = factory(App\Models\DeliverySpot::class)->create();

  return [

    'customer_profile_id' => $customer_profile->id,
    'delivery_spot_id' => $delivery_spot->id,
    'stripe_plan' => 'plan' . str_random(2),
    'frequency' => rand(0, 12),
    'unity_price' => rand(20,30),
    'gift' => rand(0,1),
    'delivery_fees' => rand(0,8),
    'take_away' => rand(0,1),

  ];
  
});