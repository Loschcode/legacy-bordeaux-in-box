<?php

/** 
 * Mock customer profile
 */
$factory->define(App\Models\CustomerProfile::class, function(Faker\Generator $faker) {

  return [
    'customer_id' => factory(App\Models\Customer::class, 'customer-subscribed')->create()->id,
    'stripe_customer' => 'cus_' . str_random(10),
    'contract_id' => str_random(8),
    'status' => 'in-progress',
    'priority' => 'medium'
  ];
  
});