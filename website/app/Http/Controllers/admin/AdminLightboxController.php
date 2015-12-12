<?php namespace App\Http\Controllers;

class AdminLightboxController extends BaseController {

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
      $this->middleware('isAdmin');
  }

  public function getIndex()
  {
    $this->layout->content = View::make('admin.lightbox.index');
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
    return View::make('admin.lightbox.superheroes')->with(compact('heroes'));
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
   return View::make('admin.lightbox.more_superheroes')->with(compact('heroes')); 
  }
  

}