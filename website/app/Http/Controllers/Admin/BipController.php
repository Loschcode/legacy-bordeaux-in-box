<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class BipController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Illustration Controller
  |--------------------------------------------------------------------------
  |
  | Add / Edit / Delete blog
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {
    $this->beforeMethod();
    $this->middleware('isAdmin');
  }

  public function getIndex()
  {
    return view('admin.bip.index');
  }

}