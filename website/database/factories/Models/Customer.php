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
$factory->defineAs(App\Models\Customer::class, 'customer-subscribed', function(Faker\Generator $faker) {
  return [
      'email' => $faker->email,
      'password' => bcrypt(str_random(10)),
      'remember_token' => str_random(10),
      'coordinate_id' => App\Models\Coordinate::getMatchingOrGenerate('1 Rue Sainte Catherine', '33000', 'Bordeaux')->id,
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'phone' => $faker->phoneNumber
  ];
});

/**
 * Mock customer
 */
$factory->defineAs(App\Models\Customer::class, 'customer-confirmed-email', function(Faker\Generator $faker) {

  return [
    'email' => $faker->email,
    'password' => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
    'coordinate_id' => App\Models\Coordinate::getMatchingOrGenerate($faker->address, $faker->postcode, $faker->city)->id,
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
    'phone' => $faker->phoneNumber,
    'emails_fully_authorized' => date('Y-m-d H:i:s', strtotime("+7 day"))
  ];

});
