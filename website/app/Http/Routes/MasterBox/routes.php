<?php

/*
|--------------------------------------------------------------------------
| MasterBox Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'MasterBox', 'domain' => "www.".config('app.domain')], function() {

  /**
   *  Connect Service Area
   */
  Route::group(['namespace' => 'Connect', 'prefix' => 'connect'], function() {

    /**
     * Customer
     */
    Route::controller('customer', 'CustomerController');
    Route::controller('customer-password', 'CustomerRemindersController');

  });

  /**
   *  MasterBox Service Area
   */
  Route::group(['namespace' => 'Service', 'prefix' => 'service'], function() {

    /**
     * Api
     */
    Route::controller('api', 'ApiController');

    /**
     * Traces
     */
    Route::controller('traces', 'TracesController');


    /**
     * Invoices (connected to Stripes)
     */
    Route::controller('invoices', 'InvoicesController');

  });

  /**
   * MasterBox Admin Area
   */
  Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'is.admin'], function() {

    Route::controller('lightbox', 'LightboxController');
    Route::controller('orders', 'OrdersController');
    Route::controller('deliveries', 'DeliveriesController');
    Route::controller('taxes', 'TaxesController');
    Route::controller('products', 'ProductsController');
    Route::controller('payments', 'PaymentsController');
    Route::controller('users', 'UsersController');
    Route::controller('spots', 'SpotsController');

    Route::controller('statistics', 'StatisticsController');

    Route::controller('debug', 'DebugController');

    Route::controller('boxes/questions/answers', 'BoxesQuestionsAnswersController');
    Route::controller('boxes/questions', 'BoxesQuestionsController');
    Route::controller('boxes', 'BoxesController');

    Route::controller('profiles', 'ProfilesController');
    Route::controller('logs', 'LogsController');
    Route::controller('email-manager', 'EmailManagerController');
    Route::controller('content', 'ContentController');

    Route::controller('', 'DashboardController');

  });

  /**
   * MasterBox Customer Area
   */
  Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {

    /**
     * Purchase a box
     */
    Route::controller('purchase', 'PurchaseController');

    /**
     * Check your profile
     */
    Route::controller('profile', 'ProfileController');

  });

  /**
   * MasterBox Guest Area
   */
  Route::group(['namespace' => 'Guest', 'prefix' => ''], function() {

    /**
     * Blog
     */
    Route::get('blog/article/{id}', function($id) { return redirect()->to('blog/'.$id.'-redirect'); });
    Route::get('blog/{id}-{slug}', array('uses' => 'BlogController@checkSeoBlog'));
    Route::controller('blog', 'BlogController');

    /**
     * Illustrations
     */
    Route::get('illustrations/index/{id}', function($id) { return redirect()->to('illustration/'.$id.'-redirect'); });
    Route::get('illustration/{id}-{slug}', array('uses' => 'IllustrationsController@checkSeoIllustrations'));
    Route::controller('illustrations', 'IllustrationsController');

    /**
     * Contact
     */
    Route::controller('contact', 'ContactController');

    /**
     * Home
     */
    Route::get('/legals', 'HomeController@getLegals');
    Route::get('/cgv', 'HomeController@getCgv');
    Route::get('/help', 'HomeController@getHelp');
    Route::get('/spots', 'HomeController@getSpots');

    Route::get('', 'HomeController@getIndex');

  });

  /**
   * EasyGo
   */
  Route::controller('easygo', 'Easygo\HomeController');
  Route::get('easygo/index', array('as' => 'easygo'));

});
