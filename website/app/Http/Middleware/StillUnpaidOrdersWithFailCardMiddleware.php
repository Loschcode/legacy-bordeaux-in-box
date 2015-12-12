<?php namespace App\Http\Middleware;

use Closure;

class StillUnpaidOrdersWithFailCardMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $unpaid = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->where('already_paid', 0)->get();

    $counter = 0;

    foreach($unpaid as $order)
    {
        $payments = $order->payments()->count();

        if ($payments > 0)
        {
          $counter++;
        }
    }

    if ($counter > 0)
    {
      return Redirect::to('/easygo/unpaid-orders');
    }
    
    return $next($request);
  }

}
