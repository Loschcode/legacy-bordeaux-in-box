<?php namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Company\BaseController;

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
    return view('company.admin.dashboard.index');
  }

}
