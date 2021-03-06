<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\HttpsProtocol::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [

        'web' => [

          \Illuminate\Cookie\Middleware\EncryptCookies::class,
          \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
          \Illuminate\Session\Middleware\StartSession::class,
          \Illuminate\View\Middleware\ShareErrorsFromSession::class,
          \App\Http\Middleware\VerifyCsrfToken::class,
          
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
      
      'auth' => \App\Http\Middleware\Authenticate::class,
      'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
      'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
      'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

      // App
      'has.track' => \App\Http\Middleware\HasTrack::class,
      'below.serie.counter' => \App\Http\Middleware\BelowSerieCounter::class,
      'has.unpaid.order.building' => \App\Http\Middleware\HasUnpaidOrderBuilding::class,
      'has.paid.order.building' => \App\Http\Middleware\HasPaidOrderBuilding::class,
      'is.admin' => \App\Http\Middleware\IsAdmin::class,
      'is.customer' => \App\Http\Middleware\IsCustomer::class,

      'is.not.customer' => \App\Http\Middleware\IsNotCustomer::class,
      'is.not.admin' => \App\Http\Middleware\IsNotAdmin::class,

      'is.not.regional' => \App\Http\Middleware\IsNotRegional::class,
      'is.not.take.away' => \App\Http\Middleware\IsNotTakeAway::class,
      'is.not.serie.ready' => \App\Http\Middleware\IsNotSerieReady::class,
      'is.serie.ready' => \App\Http\Middleware\IsSerieReady::class,
      'skip.unpaid.orders.with.fail.card' => \App\Http\Middleware\SkipUnpaidOrdersWithFailCard::class,
      'still.unpaid.orders.with.fail.card' => \App\Http\Middleware\StillUnpaidOrdersWithFailCard::class,

    ];
}
