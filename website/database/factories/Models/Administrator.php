<?php
/*
|--------------------------------------------------------------------------
| Administrator Model Factories
|--------------------------------------------------------------------------
|
|
*/

/**
 * Mock basic administrator
 */
$factory->define(App\Models\Administrator::class, function(Faker\Generator $faker) {

  return [
      'email' => $faker->email,
      'password' => bcrypt(str_random(10)),
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'phone' => $faker->phoneNumber
  ];

});
