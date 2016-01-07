<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class CheckMaintenance implements Middleware {
    
    protected $request;
    protected $app;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    public function handle($request, Closure $next)
    {

        if ($this->app->isDownForMaintenance()) {

            Response::view('standalone.maintenance', [], 503);

        }

        return $next($request);
    }

}