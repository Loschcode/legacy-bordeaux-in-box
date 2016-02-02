<?php namespace App\Http\Middleware;

use Closure, Auth;

class IsNotTakeAway {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // If we didn't choose take away, it's the same we redirect
    if (!Auth::guard('customer')->user()->order_buildings()->getCurrent()->first()->order_preference()->first()->take_away)
      return redirect()->action('MasterBox\Customer\PurchaseController@getIndex');

    return $next($request);
  }

}
