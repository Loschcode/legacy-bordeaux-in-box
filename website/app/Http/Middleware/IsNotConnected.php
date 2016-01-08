<?php namespace App\Http\Middleware;

use Closure, Auth, Request;

class IsNotConnected {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    
    if (Auth::check() && Request::segment(2) !== 'logout') {

      return redirect()->to('/');

    }

    return $next($request);
  }

}
