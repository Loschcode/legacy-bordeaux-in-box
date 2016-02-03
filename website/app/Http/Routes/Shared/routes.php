<?php

/*
|--------------------------------------------------------------------------
| Shared Routes
|--------------------------------------------------------------------------
*/

//Route::group(['namespace' => 'Shared', 'domain' => "shared.".config('app.domain')], function() {
Route::group(['namespace' => 'Shared', 'prefix' => "shared"], function() {

  /**
   * Shared Service Area
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

    /**
     * Image (Resize on the fly)
     */
    Route::controller('images', 'ImagesController');

  });

});
