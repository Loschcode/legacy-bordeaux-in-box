<?php namespace App\Http\Controllers\MasterBox\Connect;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator, Redirect, Hash, Auth;

use App\Models\Administrator;

class AdministratorController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Administrator Controller
  |--------------------------------------------------------------------------
  |
  | All about the administrator registering, login, signout
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {
    $this->middleware('is.not.connected.as.administrator', array('except' => 'getLogout'));
  }

  public function getIndex()
  {

    /**
     * We either Login the user or go to the homepage
     * Protected already via middleware
     */
    if (Auth::guard('administrator')->guest()) {

      return redirect()->action('MasterBox\Connect\AdministratorController@getLogin');

    }

  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getLogin()
  {
    return view('masterbox.connect.administrator.login');
  }

  /**
   * Logout event
   * @return redirect event
   */
  public function getLogout()
  {
    Auth::guard('administrator')->logout();
    session()->flush(); // should be commented to let the other session live
    
    return redirect()->action('MasterBox\Guest\HomeController@getIndex');
  }

  /**
   * Login action
   * @return void
   */
  public function postLogin()
  {
    // Project rules
    $rules = [

      'email' => 'required|email',
      'password' => 'required|min:4'

      ];

    $validator = Validator::make(Request::all(), $rules);

    // The form validation was good
    if ($validator->passes()) 
    {
      $authAttempt = $this->getLoginCredentials();

      // We try our credentials
      if (Auth::guard('administrator')->attempt($authAttempt)) 
      {

        // If there's an after login redirection
        if (session()->get('after-login-admin-redirection')) 
        {
          return redirect(session()->get('after-login-admin-redirection'));
        }

        return redirect()->back();

      }

      return redirect()->back()->withErrors([
        "email" => ["Identifiants invalides."]
        ]);

    } 
    else 
    {
      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);
    }

  }

  /**
   * Get the credentials within an array
   */
  protected function getLoginCredentials()
  {
    return [
      "email" => Request::get("email"),
      "password" => Request::get("password"),
    ];
  }


}