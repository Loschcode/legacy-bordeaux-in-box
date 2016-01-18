<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Box;

class BoxController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Box Controller
	|--------------------------------------------------------------------------
	|
	*/

    /**
     * Filters
     */
    public function __construct()
    {
    	
       	$this->beforeMethod();

    }
    
    /**
     * Get the listing page of the blog
     * @return void
     */
	public function getIndex()
	{

		return view('masterbox.admin.box.index');

	}


}