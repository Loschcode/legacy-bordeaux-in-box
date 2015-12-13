<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use App\Models\DeliverySerie;
use App\Models\BlogArticle;
use App\Models\DeliverySpot;
use App\Models\Page;

class HomeController extends BaseController {

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

    $next_series = DeliverySerie::nextOpenSeries();

    // Blog articles
    $articles = BlogArticle::orderBy('id', 'DESC')->limit(12)->get();

    return view('home.index')->with(compact(
      'next_series', 
      'articles'
    ));

  }

  /**
   * Legals Page
   */
  public function getLegals()
  {  
    $legal = Page::where('slug', 'legals')->first();
    return view('home.legal')->with(compact(
      'legal'
    ));
  }

  /**
   * Cgv Page
   */
  public function getCgv()
  {  
    $cgv = Page::where('slug', 'cgv')->first();
    return view('home.cgv')->with(compact(
      'cgv'
    ));
  }

  /**
   * Help page
   */
  public function getHelp()
  {  
    $help = Page::where('slug', 'help')->first();
    return view('home.help')->with(compact(
      'help'
    ));
  }

  /**
   * Spots page
   */
  public function getSpots()
  {
    $delivery_spots = DeliverySpot::get();
    return view('home.spots')->with(compact(
      'delivery_spots'
    ));
  }

}
