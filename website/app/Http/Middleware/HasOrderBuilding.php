<?php namespace App\Http\Middleware;

use Closure;
use Auth;

class HasOrderBuilding {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guard('customer')->guest()) return redirect()->to('user/login');

    if (Auth::guard('customer')->user()->order_building()->first() === NULL) return redirect()->to('/');
    
    return $next($request);
  }

}
