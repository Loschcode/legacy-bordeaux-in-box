<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsNotConnectedAsAdministrator {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    
    if (Auth::guard('administrator')->check()) {

      return redirect()->action('MasterBox\Guest\HomeController@getIndex');

    }

    return $next($request);
  }

}
