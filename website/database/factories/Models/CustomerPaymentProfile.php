<?php
/*
|--------------------------------------------------------------------------
| CustomerPaymentProfile Model Factories
|--------------------------------------------------------------------------
|
|
*/

/** 
 * Mock customer profile
 */
$factory->define(App\Models\CustomerProfile::class, function(Faker\Generator $faker) {

  $customer_profile = factory(App\Models\CustomerProfile::class)->create();

  return [

    'customer_profile_id' => $customer_profile->id,
    'stripe_token' => 'tk_' . str_random(8),
    'stripe_card' => 'card_' . str_random(8),
    'stripe_customer' => $customer_profile->stripe_customer,
    'stripe_plan' => 'plan' . str_random(2),
    'stripe_subscription' => 'sub_' . $str_random(8),
    'last4' => rand(1000, 4000)

  ];
  
});