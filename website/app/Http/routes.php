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
Route::controller('admin/bip', 'Admin\BipController');
Route::controller('admin/lightbox', 'Admin\LightboxController');
Route::controller('admin/orders', 'Admin\OrdersController');
Route::controller('admin/deliveries', 'Admin\DeliveriesController');
Route::controller('admin/taxes', 'Admin\TaxesController');
Route::controller('admin/products', 'Admin\ProductsController');
Route::controller('admin/payments', 'Admin\PaymentsController');
Route::controller('admin/users', 'Admin\UsersController');
Route::controller('admin/spots', 'Admin\SpotsController');

Route::controller('admin/statistics', 'Admin\StatisticsController');

Route::controller('admin/debug', 'Admin\DebugController');

Route::controller('admin/boxes/questions/answers', 'Admin\BoxesQuestionsAnswersController');
Route::controller('admin/boxes/questions', 'Admin\BoxesQuestionsController');
Route::controller('admin/boxes', 'Admin\BoxesController');

Route::controller('admin/profiles', 'Admin\ProfilesController');
Route::controller('admin/logs', 'Admin\LogsController');
Route::controller('admin/email-manager', 'Admin\EmailManagerController');
Route::controller('admin/content', 'Admin\ContentController');
Route::controller('admin', 'Admin\DashboardController');


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
Route::controller('easygo', 'Easygo\HomeController');
Route::get('easygo/index', array('as' => 'easygo'));


/**
 * Manual files
 */
Route::get('viesauvage', array(function() {

  return redirect()->to('public/uploads/others/playlist-collection-vie-sauvage.zip');

}));

/**
 * Simple API
 */
Route::get('api/orders/count', array('before' => 'isAdmin', function() {

  $current_serie = DeliverySerie::nextOpenSeries()->first();

  $count = $current_serie->orders()->notCanceledOrders()->count();
  return Response::Json(['count' => $count]);

}));
