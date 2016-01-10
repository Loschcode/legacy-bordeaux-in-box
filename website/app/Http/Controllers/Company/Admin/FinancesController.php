<?php namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Company\BaseController;

class FinancesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | Home page system
  |
  */

  /**
   * Home page
   */
  public function getIndex()
  {

    dd(action('MasterBox\Connect\CustomerController@getLogin'));

  }

}