<?php namespace App\Http\Middleware;

use Closure;

use App\Models\Order;

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
      return redirect('/easygo/locked');
    }
    
    return $next($request);
  }

}
