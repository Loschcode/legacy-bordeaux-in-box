<?php namespace App\Http\Middleware;

use Closure;

class IsAdminMiddleware {

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

      return Redirect::to('/');

    } else {

      if (Auth::user()->role !== 'admin') 
      {
        return Redirect::to('/');
      }

      return $next($request);

    }
  }

}
