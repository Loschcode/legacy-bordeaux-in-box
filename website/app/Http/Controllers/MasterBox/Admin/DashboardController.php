<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

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

    }

    /**
     * Index dashboard
     * @return void
     */
	public function getIndex()
	{
    return view('master-box.admin.dashboard.index');
	}

}
