<?php

/**
 * Mock blog article
 */
$factory->define(App\Models\BlogArticle::class, function(Faker\Generator $faker) {

  $title = $faker->sentence;

  return [
    'customer_id' => factory(App\Models\Customer::class, 'customer-subscribed')->create()->id,
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