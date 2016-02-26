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
 * Some patterns before to start
 */
Route::pattern('id', '[0-9]+');
Route::pattern('name', '[a-Z]+');
Route::pattern('slug', '[0-9A-Za-z\-]+');

/**
 * Manual files
 */
Route::get('viesauvage', array(function() {

  return redirect()->to('public/uploads/others/playlist-collection-vie-sauvage.zip');

}));

Route::post('traces/emails', array(function() {

  return redirect()->to('/');

}));