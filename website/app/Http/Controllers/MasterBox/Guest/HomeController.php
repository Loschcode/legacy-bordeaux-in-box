<?php namespace App\Http\Controllers\MasterBox\Guest;

use App\Http\Controllers\MasterBox\BaseController;

use Auth;

use App\Models\DeliverySerie;
use App\Models\BlogArticle;
use App\Models\DeliverySpot;
use App\Models\Page;
use App\Models\ImageArticle;
use App\Models\Customer;

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
    
    /*$navette_pickup = \App\Libraries\NavettePickUp::findSpotsFromCoordinates();
    dd($navette_pickup);*/

    //$coord = \App\Libraries\GoogleGeocoding::getCoordinates('18 allée pierre corneille', 'Gujan-mestras', '33470');
    //dd($coord);

    $next_series = DeliverySerie::nextOpenSeries();

    // Blog articles
    $articles = BlogArticle::orderBy('id', 'DESC')->limit(16)->get();

    // Illustrations
    $image_articles = ImageArticle::orderBy('id', 'desc')->get();

    return view('masterbox.guest.home.index')->with(compact(
      'next_series', 
      'articles',
      'image_articles'
    ));


  }

  /**
   * Legals Page
   */
  public function getLegals()
  {  
    $legal = Page::where('slug', 'legals')->first();
    return view('masterbox.guest.home.legal')->with(compact(
      'legal'
    ));
  }

  /**
   * Cgv Page
   */
  public function getCgv()
  {  
    $cgv = Page::where('slug', 'cgv')->first();
    return view('masterbox.guest.home.cgv')->with(compact(
      'cgv'
    ));
  }

  /**
   * Help page
   */
  public function getHelp()
  {  
    $help = Page::where('slug', 'help')->first();
    return view('masterbox.guest.home.help')->with(compact(
      'help'
    ));
  }

  /**
   * Spots page
   */
  
  public function getSpots()
  {
    $delivery_spots = DeliverySpot::onlyActive()->get();
    return view('masterbox.guest.home.spots')->with(compact(
      'delivery_spots'
    ));
  }

  /**
   * Page to show the box of a special month / year
   * Right now we just display one box (february 2016)
   */
  public function getBox($month, $year)
  {
    // It's a tmp condition (we really don't care for now)
    if ($month != 'march' OR $year != '2016') {
      abort(404);
    }

    return view('masterbox.guest.home.box');
  }

  /**
   * Concept page
   */
  public function getConcept()
  {

    return view('masterbox.guest.home.concept');

  }


}
