<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class CheckForMaintenanceMode {
    
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

            return response()->view('standalone.maintenance', [], 503);

        }

        return $next($request);
    }

}