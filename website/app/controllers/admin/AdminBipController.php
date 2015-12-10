<?php

class AdminBipController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Illustration Controller
  |--------------------------------------------------------------------------
  |
  | Add / Edit / Delete blog
  |
  */

  protected $layout = 'layouts.admin';

  /**
   * Filters
   */
  public function __construct()
  {
    $this->beforeMethod();
    $this->beforeFilter('isAdmin');
  }

  public function getIndex()
  {
    $this->layout->content = View::make('admin.bip.index');
  }

}