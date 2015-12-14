<?php namespace App\Http\Middleware;

use Closure;
use Auth;

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
    if (Auth::guest()) return redirect()->to('user/login');

    if (Auth::user()->order_building()->first() === NULL) return redirect()->to('/');
    
    return $next($request);
  }

}
