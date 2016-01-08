<?php namespace App\Http\Controllers\Sandbox;

use App\Http\Controllers as Controllers;

class BaseController extends Controllers\BaseController {

  /**
   * All we need to use before the method will be call
   * @return void
   */
  protected function beforeMethod()
  {
  }

  /**
   * Setup the layout used by the controller.
   *
   * @return void
   */
  protected function setupLayout()
  {
  }

}
