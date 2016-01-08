<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

  /**
   * The application's global HTTP middleware stack.
   *
   * @var array
   */
  protected $middleware = [

    'App\Http\Middleware\CheckForMaintenanceMode',

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
    'below.serie.counter' => 'App\Http\Middleware\BelowSerieCounter',
    'has.order.building' => 'App\Http\Middleware\HasOrderBuilding',
    'is.admin' => 'App\Http\Middleware\IsAdmin',
    'is.connected' => 'App\Http\Middleware\IsConnected',
    'is.not.connected' => 'App\Http\Middleware\IsNotConnected',
    'is.not.regional' => 'App\Http\Middleware\IsNotRegional',
    'is.not.regional.or.take.away' => 'App\Http\Middleware\IsNotRegional',
    'is.not.serie.ready' => 'App\Http\Middleware\IsNotSerieReady',
    'is.serie.ready' => 'App\Http\Middleware\IsSerieReady',
    'skip.unpaid.orders.with.fail.card' => 'App\Http\Middleware\SkipUnpaidOrdersWithFailCard',
    'still.unpaid.orders.with.fail.card' => 'App\Http\Middleware\StillUnpaidOrdersWithFailCard',
  ];

}
