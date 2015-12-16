<?php namespace App\Http\Middleware;

use Closure, Auth;

class IsNotRegionalMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // If it's not regional, we can't access this part
    if (!Auth::user()->order_building()->first()->isRegionalAddress()) return redirect()->to('/order');

    return $next($request);
  }

}
