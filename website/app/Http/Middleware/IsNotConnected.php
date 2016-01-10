<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsNotConnected {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    
    if (Auth::customer()->check()) {

      return redirect()->action('MasterBox\Guest\HomeController@getIndex');

    }

    return $next($request);
  }

}
