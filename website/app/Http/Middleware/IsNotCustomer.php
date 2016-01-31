<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsNotCustomer {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    
    if (Auth::guard('customer')->check()) {

      return redirect()->action('MasterBox\Guest\HomeController@getIndex');

    }

    return $next($request);
  }

}
