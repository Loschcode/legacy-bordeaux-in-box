<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
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