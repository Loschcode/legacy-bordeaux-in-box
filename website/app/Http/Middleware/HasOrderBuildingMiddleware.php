<?php namespace App\Http\Middleware;

use Closure;

class HasOrderBuildingMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guest()) return Redirect::to('user/login');

    if (Auth::user()->order_building()->first() === NULL) return Redirect::to('/');
    
    return $next($request);
  }

}
