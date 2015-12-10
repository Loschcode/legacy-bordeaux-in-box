<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{

	if (App::environment('production'))
	{

		if( ! Request::secure()) {

	    	return Redirect::secure(Request::path());

	    }
	}

});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Administrator Filters
|--------------------------------------------------------------------------
*/

Route::filter('isAdmin', function()
{
	if (Auth::guest()) {

		return Redirect::to('/');

	} else {

		if (Auth::user()->role !== 'admin') return Redirect::to('/');

	}

});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
*/

Route::filter('isConnected', function()
{
	if (Auth::guest()) {

		// We register the URL where the user tried to go before
		Session::put('after-login-redirection', Request::url());
		return Redirect::to('user/login');

	}

});

// If the user is effectively building orders with all the steps
Route::filter('hasOrderBuilding', function()
{
	if (Auth::guest()) return Redirect::to('user/login');

	if (Auth::user()->order_building()->first() === NULL) return Redirect::to('/');

});


// If the counter of the serie hasn't reached the max
Route::filter('belowSerieCounter', function()
{

	$next_serie = DeliverySerie::nextOpenSeries()->first();

	if ($next_serie->getCounter() !== FALSE) {

		if ($next_serie->getCounter() <= 0) {

			return Redirect::to('/');

		}

	}

});


// If the user destination address isn't regional or if the user didn't choose take away option
Route::filter('isNotRegionalOrTakeAway', function()
{
	// If it's not regional, we can't access this part
	if (!Auth::user()->order_building()->first()->isRegionalAddress()) return Redirect::to('/order');

	// If we didn't choose take away, it's the same we redirect
	if (!Auth::user()->order_building()->first()->order_preference()->first()->take_away) return Redirect::to('/order');

});

// If the user destination address isn't regional
Route::filter('isNotRegional', function()
{
	// If it's not regional, we can't access this part
	if (!Auth::user()->order_building()->first()->isRegionalAddress()) return Redirect::to('/order');

});

Route::filter('isNotSerieReady', function()
{
	$orders = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->get();

	if (count($orders) == 0)
	{
		return Redirect::to('/easygo/locked');
	}
});

Route::filter('isSerieReady', function()
{
	$orders = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->get();

	if (count($orders) > 0)
	{
		return Redirect::to('/easygo/index');
	}

});


Route::filter('stillUnpaidOrdersWithFailCard', function()
{
	$unpaid = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->where('already_paid', 0)->get();

	$counter = 0;

	foreach($unpaid as $order)
	{
			$payments = $order->payments()->count();

			if ($payments > 0)
			{
				$counter++;
			}
	}

	if ($counter > 0)
	{
		return Redirect::to('/easygo/unpaid-orders');
	}

});

Route::filter('skipUnpaidOrdersWithFailCard', function()
{
	$unpaid = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->where('already_paid', 0)->get();

	$counter = 0;

	foreach($unpaid as $order)
	{
			$payments = $order->payments()->count();

			if ($payments > 0)
			{
				$counter++;
			}
	}

	if ($counter == 0)
	{
		return Redirect::to('/easygo/index');
	}

});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
*/

Route::filter('isNotConnected', function()
{

	if (Auth::check() && Request::segment(2) !== 'logout') {

		return Redirect::to('/');

	}

});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
