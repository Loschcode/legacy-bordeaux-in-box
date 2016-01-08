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

    // TODO: Put that in a single condition
    if ($next_serie->getCounter() !== FALSE) 
    {
      if ($next_serie->getCounter() <= 0) 
      {
        return redirect()->to('/');
      }
    }

    return $next($request);
  }

}
