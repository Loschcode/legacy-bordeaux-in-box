<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

  /**
   * The application's global HTTP middleware stack.
   *
   * @var array
   */
  protected $middleware = [
    'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
    'Illuminate\Cookie\Middleware\EncryptCookies',
    'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
    'Illuminate\Session\Middleware\StartSession',
    'Illuminate\View\Middleware\ShareErrorsFromSession',
    'App\Http\Middleware\VerifyCsrfToken',
  ];

  /**
   * The application's route middleware.
   *
   * @var array
   */
  protected $routeMiddleware = [
    'auth' => 'App\Http\Middleware\Authenticate',
    'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
    'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',

    // App
    'belowSerieCounter' => 'App\Http\Middleware\BelowSerieCounterMiddleware',
    'hasOrderBuilding' => 'App\Http\Middleware\HasOrderBuildingMiddleware',
    'isAdmin' => 'App\Http\Middleware\IsAdminMiddleware',
    'isConnected' => 'App\Http\Middleware\IsConnectedMiddleware',
    'isNotConnected' => 'App\Http\Middleware\IsNotConnectedMiddleware',
    'isNotRegional' => 'App\Http\Middleware\IsNotRegionalMiddleware',
    'isNotRegionalOrTakeAway' => 'App\Http\Middleware\IsNotRegionalMiddleware',
    'isNotSerieReady' => 'App\Http\Middleware\IsNotSerieReadyMiddleware',
    'isSerieReady' => 'App\Http\Middleware\IsSerieReadyMiddleware',
    'skipUnpaidOrdersWithFailCard' => 'App\Http\Middleware\SkipUnpaidOrdersWithFailCardMiddleware',
    'stillUnpaidOrdersWithFailCard' => 'App\Http\Middleware\StillUnpaidOrdersWithFailCardMiddleware',
  ];

}
