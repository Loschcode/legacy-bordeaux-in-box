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
 * *****************
 * Company Section *
 * *****************
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

/**
 * *******************
 * MasterBox Section *
 * *******************
 */
Route::group(['namespace' => 'MasterBox', 'domain' => "www.".config('app.domain')], function() {

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
     * Order
     */
    Route::controller('order', 'OrderController');

    /**
     * Profile
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

  /** TO CHANGE OF AREA WITHIN AUTH **/

  /**
   * User
   */
  Route::controller('user', 'UserController');

  /**
   * Reminds (forgot password module)
   */
  Route::controller('user-password', 'RemindersController');


/**
 * Manual files
 */
Route::get('viesauvage', array(function() {

  return redirect()->to('public/uploads/others/playlist-collection-vie-sauvage.zip');

}));