<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

  /**
   * Overwrite any vendor / package configuration.
   *
   * This service provider is intended to provide a convenient location for you
   * to overwrite any "vendor" or package configuration that you may want to
   * modify before the application handles the incoming request / command.
   *
   * @return void
   */
  public function boot()
  {
      foreach (glob(app_path().'/Validations/*.php') as $filename){
          
          require_once($filename);

      }
  }

  public function register()
  {
    
  }

}
