<?php

/*
|--------------------------------------------------------------------------
| Sandbox Routes
|--------------------------------------------------------------------------
*/

/**
 * *****************
 * Sandbox Section *
 * *****************
 */
Route::group(['namespace' => 'Sandbox', 'prefix' => 'sandbox', 'middleware' => ['web']], function() {

  /**
   * Ui Area
   */
  Route::group(['namespace' => 'Ui', 'prefix' => 'ui'], function() {

    Route::get('/', 'PagesController@getHome');
    Route::controller('pages', 'PagesController');

  });


});