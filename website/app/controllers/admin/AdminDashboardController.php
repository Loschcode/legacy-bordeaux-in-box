<?php

class AdminDashboardController extends BaseController {

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
        $this->beforeFilter('isAdmin');

    }

	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.admin';

    /**
     * Index dashboard
     * @return void
     */
	public function getIndex()
	{
		$this->layout->content = View::make('admin.dashboard.index');
	}

}
