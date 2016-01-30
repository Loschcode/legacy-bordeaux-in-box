<?php

/*
|--------------------------------------------------------------------------
| MasterBox Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'MasterBox', 'domain' => "www.".config('app.domain'), 'middleware' => ['web']], function() {

  /**
   *  Connect Service Area
   */
  Route::group(['namespace' => 'Connect', 'prefix' => 'connect'], function() {

    /**
     * Customer
     */
    Route::controller('customer', 'CustomerController');
    Route::controller('password-reminders', 'PasswordRemindersController');

  });

  /**
   *  MasterBox Service Area
   */
  Route::group(['namespace' => 'Service', 'prefix' => 'service'], function() {

    /**
     * Api
     */
    Route::controller('api', 'ApiController');
    
  });

  /**
   * MasterBox Admin Area
   */
  Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'is.admin'], function() {

    Route::controller('lightbox', 'LightboxController');
    Route::controller('orders', 'OrdersController');
    Route::controller('deliveries', 'DeliveriesController');
    Route::controller('payments', 'PaymentsController');
    Route::controller('customers', 'CustomersController');
    Route::controller('spots', 'SpotsController');

    Route::controller('statistics', 'StatisticsController');

    Route::controller('debug', 'DebugController');

    Route::controller('box/questions/answers', 'BoxQuestionsAnswersController');
    Route::controller('box/questions', 'BoxQuestionsController');
    Route::controller('box', 'BoxController');

    Route::controller('profiles', 'ProfilesController');
    Route::controller('logs', 'LogsController');
    Route::controller('email-manager', 'EmailManagerController');
    Route::controller('content', 'ContentController');

    Route::controller('easy-go', 'EasyGoController');

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
    Route::get('blog', 'BlogController@getIndex');
    Route::get('blog/{slug}', 'BlogController@getArticle');

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

});
