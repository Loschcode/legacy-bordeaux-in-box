<?php namespace App\Http\Middleware;

use Closure;

class IsSerieReadyMiddleware {

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

    if (count($orders) > 0)
    {
      return redirect()->to('/easygo/index');
    }
    
    return $next($request);
  }

}
