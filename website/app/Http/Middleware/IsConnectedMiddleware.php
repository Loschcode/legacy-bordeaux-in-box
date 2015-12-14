<?php namespace App\Http\Middleware;

use Closure;

class IsConnectedMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guest()) {

      // We register the URL where the user tried to go before
      session()->put('after-login-redirection', Request::url());
      return Redirect::to('user/login');

    }
    
    return $next($request);
  }

}
