<?php namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

use App\Models\CustomerConnect;

class HasTrack {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {

    /**
     * If we have a `track` get data, we can try to login with it
     * Then we redirect directly either it fails or not
     */
    if (Request::get('track')) {

      CustomerConnect::tryToLogin(Request::get('track'));
      
      $next($request);
      return redirect()->to(Request::url());

    }

    return $next($request);
  }

}
