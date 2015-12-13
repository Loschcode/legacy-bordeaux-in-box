<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class DashboardController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Dashboard Controller
	|--------------------------------------------------------------------------
	|
	| The admin dashboard
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

    /**
     * Index dashboard
     * @return void
     */
	public function getIndex()
	{
    return view('admin.dashboard.index');
	}

}
