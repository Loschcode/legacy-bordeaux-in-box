<?php namespace App\Http\Middleware;

use Closure;

class IsNotSerieReadyMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $orders = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->get();

    if (count($orders) == 0)
    {
      return Redirect::to('/easygo/locked');
    }
    
    return $next($request);
  }

}
