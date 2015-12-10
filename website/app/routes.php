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
 * Api
 */
Route::controller('api', 'ApiController');

/**
 * Traces
 */
Route::controller('traces', 'TracesController');

/**
 * Home
 */
Route::get('/', 'HomeController@getIndex');
Route::get('/legals', 'HomeController@getLegals');
Route::get('/cgv', 'HomeController@getCgv');
Route::get('/help', 'HomeController@getHelp');
Route::get('/spots', 'HomeController@getSpots');
Route::controller('home', 'HomeController');

/**
 * Admin
 */
Route::controller('admin/bip', 'AdminBipController');
Route::controller('admin/lightbox', 'AdminLightboxController');
Route::controller('admin/orders', 'AdminOrdersController');
Route::controller('admin/deliveries', 'AdminDeliveriesController');
Route::controller('admin/taxes', 'AdminTaxesController');
Route::controller('admin/products', 'AdminProductsController');
Route::controller('admin/payments', 'AdminPaymentsController');
Route::controller('admin/users', 'AdminUsersController');
Route::controller('admin/spots', 'AdminSpotsController');

Route::controller('admin/statistics', 'AdminStatisticsController');

Route::controller('admin/debug', 'AdminDebugController');

Route::controller('admin/boxes/questions/answers', 'AdminBoxesQuestionsAnswersController');
Route::controller('admin/boxes/questions', 'AdminBoxesQuestionsController');
Route::controller('admin/boxes', 'AdminBoxesController');

Route::controller('admin/profiles', 'AdminProfilesController');
Route::controller('admin/logs', 'AdminLogsController');
Route::controller('admin/email-manager', 'AdminEmailManagerController');
Route::controller('admin/content', 'AdminContentController');
Route::controller('admin', 'AdminDashboardController');


/**
 * Invoices (connected to Stripes)
 */
Route::controller('invoices', 'InvoicesController');

/**
 * Order
 */
Route::controller('order', 'OrderController');


/**
 * Blog
 */
Route::get('blog/article/{id}', function($id) { return Redirect::to('blog/'.$id.'-redirect'); });
Route::get('blog/{id}-{slug}', array('uses' => 'BlogController@checkSeoBlog'));
Route::controller('blog', 'BlogController');

/**
 * Illustrations
 */
Route::get('illustrations/index/{id}', function($id) { return Redirect::to('illustration/'.$id.'-redirect'); });
Route::get('illustration/{id}-{slug}', array('uses' => 'IllustrationsController@checkSeoIllustrations'));
Route::controller('illustrations', 'IllustrationsController');

/**
 * Contact
 */
Route::controller('contact', 'ContactController');


/**
 * Profile
 */
Route::controller('profile', 'ProfileController');

/**
 * User
 */
Route::controller('user', 'UserController');

/**
 * Reminds (forgot password module)
 */
Route::controller('user-password', 'RemindersController');


/**
 * EasyGo
 */
Route::controller('easygo', 'EasygoHomeController');
Route::get('easygo/index', array('as' => 'easygo'));


/**
 * Manual files
 */
Route::get('viesauvage', array(function() {

  return Redirect::to('public/uploads/others/playlist-collection-vie-sauvage.zip');

}));

/**
 * Simple API
 */
Route::get('api/orders/count', array('before' => 'isAdmin', function() {

  $current_serie = DeliverySerie::nextOpenSeries()->first();

  $count = $current_serie->orders()->notCanceledOrders()->count();
  return Response::Json(['count' => $count]);

}));
