<?php
/*
|--------------------------------------------------------------------------
| Customer Model Factories
|--------------------------------------------------------------------------
|
|
*/

/**
 * Mock basic customer
 */
$factory->define(App\Models\Customer::class, function(Faker\Generator $faker) {

  return [
      'email' => $faker->email,
      'password' => bcrypt(str_random(10)),
      'remember_token' => str_random(10),
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'phone' => $faker->phoneNumber
  ];

});

/**
 * Mock customer
 */
$factory->defineAs(App\Models\Customer::class, 'customer-confirmed-email', function(Faker\Generator $faker) {

    $customer = $factory->raw(App\Models\Customer::class);
    
    return array_merge($customer, [

      'emails_fully_authorized' => date('Y-m-d H:i:s', strtotime("+7 day"))

    ]);

});
