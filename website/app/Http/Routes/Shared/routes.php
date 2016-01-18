<?php

/*
|--------------------------------------------------------------------------
| MasterBox Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Shared', 'domain' => "shared.".config('app.domain')], function() {


  /**
   *  MasterBox Service Area
   */
  Route::group(['namespace' => 'Service', 'prefix' => 'service'], function() {

    /**
     * Traces
     */
    Route::controller('traces', 'TracesController');

    /**
     * Invoices (connected to Stripes)
     */
    Route::controller('invoices', 'InvoicesController');

  });

});
