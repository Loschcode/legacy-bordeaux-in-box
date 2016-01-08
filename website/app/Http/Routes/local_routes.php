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
   * Sandbox Guest Area
   */
  Route::controller('hello', 'HelloController');

  /*Route::group(['namespace' => 'Guest', 'prefix' => ''], function() {

  });*/

});