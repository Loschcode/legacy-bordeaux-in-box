<?php namespace App\Http\Controllers\Sandbox\Ui;

use App\Http\Controllers\Sandbox\BaseController;

class PagesController extends BaseController {

  public function getHome()
  {
    return view('sandbox.ui.pages.home');
  }

}