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
    if (Auth::customer()->guest()) {

      return redirect()->to('/');

    } else {

      if (Auth::customer()->get()->role !== 'admin') 
      {
        return redirect()->to('/');
      }

      return $next($request);

    }
  }

}