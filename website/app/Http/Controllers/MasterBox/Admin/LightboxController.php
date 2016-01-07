<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

class LightboxController extends BaseController {

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
    $this->layout->content = view()->make('admin.lightbox.index');
  }

  public function getSuperheroes()
  {
    $this->layout = null;
    $heroes = [

      'batman',
      'superman',
      'ironman',
      'birdman',
      'spiderman'

    ];
    return view()->make('admin.lightbox.superheroes')->with(compact('heroes'));
  }

  public function getMoreSuperheroes()
  {
   $this->layout = null;
   $heroes = [

     'batman',
     'superman',
     'ironman',
     'birdman',
     'spiderman',
     'pigman',
     'fuckman',
     'catwoman'

   ];

   return view('master-box.admin.lightbox.more_superheroes')->with(compact(
    'heroes'
    )); 

  }
  

}