<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsConnected {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guard('customer')->guest()) {

      // We register the URL where the user tried to go before
      session()->put('after-login-redirection', Request::url());
      return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

    }
    
    return $next($request);
  }

}
