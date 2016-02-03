<?php
/*
|--------------------------------------------------------------------------
| CustomerProfile Model Factories
|--------------------------------------------------------------------------
|
|
*/

/** 
 * Mock customer profile
 */
$factory->define(App\Models\CustomerProfile::class, function(Faker\Generator $faker) {

  $customer = factory(App\Models\Customer::class)->create();

  return [
    'customer_id' => $customer->id,
    'stripe_customer' => 'cus_' . str_random(10),
    'contract_id' => str_random(8),
    'status' => 'in-progress',
    'priority' => 'medium'
  ];
  
});