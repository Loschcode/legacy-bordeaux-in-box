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
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.master';

    /**
     * Home page
     */
	public function getIndex()
	{

		$next_series = DeliverySerie::nextOpenSeries();

		// Blog articles
		$articles = BlogArticle::orderBy('id', 'DESC')->limit(12)->get();

		return view('home.index')->with(compact('next_series', 'articles'));
	}

	public function getLegals()
	{	
		$legal = Page::where('slug', 'legals')->first();
		$this->layout->content = view()->make('home.legal')->with('legal', $legal);
	}

	public function getCgv()
	{	
		$cgv = Page::where('slug', 'cgv')->first();
		$this->layout->content = view()->make('home.cgv')->with('cgv', $cgv);
	}

	public function getHelp()
	{	
		$help = Page::where('slug', 'help')->first();
		$this->layout->content = view()->make('home.help')->with('help', $help);
	}

	public function getSpots()
	{

		$delivery_spots = DeliverySpot::get();
		$this->layout->content = view()->make('home.spots')->with('delivery_spots', $delivery_spots);

	}

}
