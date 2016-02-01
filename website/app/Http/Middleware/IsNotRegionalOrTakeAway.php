<?php namespace App\Http\Middleware;

use Closure;

class IsNotRegionalOrTakeAway {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // If it's not regional, we can't access this part
    if (!Auth::guard('customer')->user()->order_building()->getCurrent()->isRegionalAddress()) return redirect()->action('MasterBox\Customer\PurchaseController@getIndex');

    // If we didn't choose take away, it's the same we redirect
    if (!Auth::guard('customer')->user()->order_building()->getCurrent()->order_preference()->first()->take_away) return redirect()->action('MasterBox\Customer\PurchaseController@getIndex');

    return $next($request);
  }

}
