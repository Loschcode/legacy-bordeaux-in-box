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
Route::group(['namespace' => 'Sandbox', 'domain' => "sandbox.{domain}.{tld}"], function() {

  /**
   * Ui Area
   */
  Route::group(['namespace' => 'Ui', 'prefix' => 'ui'], function() {

    Route::get('/', 'PagesController@getHome');
    Route::controller('pages', 'PagesController');

  });


});