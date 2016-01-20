<?php

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Company', 'domain' => "company.".config('app.domain'), 'middleware' => ['web']], function() {

  /**
   * Company Admin Area
   */
  Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {

    // , 'middleware' => 'isAdminMiddleware'
    Route::controller('finances', 'FinancesController');
    Route::controller('', 'DashboardController');

  });

  /**
   * Company Guest Area
   */
  Route::group(['namespace' => 'Guest', 'prefix' => ''], function() {
    
    Route::controller('billing', 'BillingController');

  });

});