<?php namespace App\Http\Middleware;

use Closure, Auth;

class IsAdmin {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guard('administrator')->guest()) {

      session()->put('after-login-admin-redirection', Request::url());
      return redirect()->action('MasterBox\Connect\AdministratorController@getLogin');

    } else {

      return $next($request);

    }
  }

}
