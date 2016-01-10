<?php namespace App\Http\Controllers\Sandbox\Ui;

use App\Http\Controllers\Sandbox\BaseController;

class PagesController extends BaseController {

  public function getHome()
  {

    $colors = ['blue', 'orange', 'red', 'pink', 'yellow', 'purple', 'green', 'grey'];
    return view('sandbox.ui.pages.home')->with(compact('colors'));
  }

}