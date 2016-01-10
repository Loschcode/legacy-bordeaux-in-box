<?php namespace App\Http\Controllers\Documentation\Ui;

use App\Http\Controllers\Documentation\BaseController;

class PagesController extends BaseController {

  public function getHome()
  {
    return view('documentation.ui.pages.home');
  }

}