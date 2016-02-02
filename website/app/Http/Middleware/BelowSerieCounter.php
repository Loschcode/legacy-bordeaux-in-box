<?php namespace App\Http\Middleware;

use Closure;

use App\Models\DeliverySerie;

class BelowSerieCounter {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {

    $next_serie = DeliverySerie::nextOpenSeries()->first();

    /**
     * We the counter is done
     */
    if (($next_serie->getCounter() !== FALSE) && ($next_serie->getCounter() <= 0))
      return redirect()->action('MasterBox\Guest\HomeController@getIndex');

    return $next($request);
    
  }

}
