<?php namespace App\Http\Middleware;

use Closure, Auth;

class IsAdmin {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guard('customer')->guest()) {

      return redirect()->to('/');

    } else {

      if (Auth::guard('customer')->get()->role !== 'admin') 
      {
        return redirect()->to('/');
      }

      return $next($request);

    }
  }

}
