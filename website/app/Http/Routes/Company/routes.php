<?php

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Company', 'domain' => "company.".config('app.domain')], function() {

  /**
   * Company Admin Area
   */
  Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {

    // , 'middleware' => 'isAdminMiddleware'
    Route::controller('finances', 'FinancesController');

  });

  /**
   * Company Guest Area
   */
  Route::group(['namespace' => 'Guest', 'prefix' => ''], function() {

  });

});