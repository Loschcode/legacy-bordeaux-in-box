<?php
/*
|--------------------------------------------------------------------------
| BlogArticle Model Factories
|--------------------------------------------------------------------------
|
|
*/

/**
 * Mock blog article
 */
$factory->define(App\Models\BlogArticle::class, function(Faker\Generator $faker) {

  $title = $faker->sentence;
  $administrator = factory(App\Models\Administrator::class)->create();

  return [
    'administrator_id' => $administrator->id,
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