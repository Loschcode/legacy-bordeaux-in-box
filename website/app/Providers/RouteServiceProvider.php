<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		//
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{

		$router->group(['namespace' => $this->namespace], function($router)
		{

      /**
       * Base routing
       */
      if (file_exists(app_path("Http/Routes/routes.php")))
         require app_path("Http/Routes/routes.php");

      /**
       * Environment dedependants routing
       */
      $env = app()->environment();
      $routes = config('routes');

      if ($env === 'development') {

        foreach ($routes['development'] as $development_routes) {

          if (file_exists(app_path("Http/Routes/$development_routes/routes.php")))
            require app_path("Http/Routes/$development_routes/routes.php");

        }
      
      }
		
      /**
       * Universal routing
       */
      foreach ($routes['*'] as $universal_routes) {
      
        if (file_exists(app_path("Http/Routes/$universal_routes/routes.php")))
          require app_path("Http/Routes/$universal_routes/routes.php");

      }

		});

	}

}
