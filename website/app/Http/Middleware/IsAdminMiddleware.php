<?php namespace App\Http\Middleware;

use Closure, Auth;

class IsAdminMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::guest()) {

      return redirect()->to('/');

    } else {

      if (Auth::user()->role !== 'admin') 
      {
        return redirect()->to('/');
      }

      return $next($request);

    }
  }

}
