<?php namespace App\Http\Middleware;

use Closure;
use Auth;

class HasUnpaidOrderBuilding {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {


    if (Auth::guard('customer')->user()->order_building()->getCurrent() === NULL)
      return redirect()->action('MasterBox\Guest\HomeController@getIndex');

    return $next($request);
  }

}
