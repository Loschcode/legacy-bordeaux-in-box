<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsCustomer {

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

      session()->put('after-login-redirection', Request::url());
      return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

    }
    
    return $next($request);
  }

}
