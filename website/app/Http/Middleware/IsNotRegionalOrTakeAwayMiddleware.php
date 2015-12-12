<?php namespace App\Http\Middleware;

use Closure;

class IsNotRegionalOrTakeAwayMiddleware {

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
    if (!Auth::user()->order_building()->first()->isRegionalAddress()) return Redirect::to('/order');

    // If we didn't choose take away, it's the same we redirect
    if (!Auth::user()->order_building()->first()->order_preference()->first()->take_away) return Redirect::to('/order');

    return $next($request);
  }

}
