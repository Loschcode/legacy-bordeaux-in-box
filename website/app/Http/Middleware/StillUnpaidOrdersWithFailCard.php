<?php namespace App\Http\Middleware;

use Closure;

use App\Models\Order;

class StillUnpaidOrdersWithFailCard {

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
      return redirect()->action('MasterBox\Admin\EasyGoController@getUnpaid');
    }
    
    return $next($request);
  }

}
