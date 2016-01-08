<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class BaseController extends Controller {

	/**
	 * All we need to use before the method will be call
	 * @return void
	 */
	protected function beforeMethod()
	{
		ini_set('memory_limit', '-1');k
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
	}

}
