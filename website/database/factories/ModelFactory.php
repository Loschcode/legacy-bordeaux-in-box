<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * Mock subscribed customer
 */
$factory->defineAs(App\Models\Customer::class, 'subscribed-customer', function(Faker\Generator $faker) {
  return [
      'email' => $faker->email,
      'password' => bcrypt(str_random(10)),
      'remember_token' => str_random(10),
      'role' => 'customer',
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'phone' => $faker->phoneNumber
  ];
});

