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

/**
 * Mock blog article
 */
$factory->define(App\Models\BlogArticle::class, function(Faker\Generator $faker) {

  $title = $faker->sentence;

  return [
    'customer_id' => factory(App\Models\Customer::class, 'subscribed-customer')->create()->id,
    'title' => $title,
    'slug' => str_slug($title),
    'content' => $faker->paragraph(45),
    'url' => str_slug($title),
    'thumbnail' => json_encode([
      'folder' => 'blog',
      'filename' => 'fake.png'
    ])
  ];
});

/**
 * Mock delivery serie
 */
$factory->define(App\Models\DeliverySerie::class, function(Faker\Generator $faker) {

  $date = date('Y-m-d', strtotime("+7 day"));

  return [
    'delivery' => $date,
    'closed' => $date,
    'goal' => rand(30, 900)
  ];

});