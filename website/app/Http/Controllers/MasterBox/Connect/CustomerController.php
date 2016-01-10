<?php namespace App\Http\Controllers\MasterBox\Connect;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator, Redirect, Hash, Auth;

use App\Models\Customer;

class CustomerController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Customer Controller
  |--------------------------------------------------------------------------
  |
  | All about the customer registering, login, signout
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {
    $this->middleware('is.not.connected', array('except' => 'getLogout'));
  }

  public function getIndex()
  {

    /**
     * We either Login the user or go to the homepage
     * Protected already via middleware
     */
    if (Auth::customer()->guest()) {

      return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

    }

  }

  /**
   * Subscribe page
   * @return [type] [description]
   */
  public function getSubscribe()
  {
    return view('masterbox.connect.customer.subscribe');
  }

  /**
   * Subscribe to the website
   * @return redirect process
   */
  public function postSubscribe()
  {

    // New customer rules
    $rules = [

      'first_name' => 'required',
      'last_name' => 'required',
      'email' => 'required|email|unique:customers,email',
      'password' => 'required|min:5|confirmed',

      'phone' => 'required',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) 
    {
      $customer = new Customer;
      $customer->email = $fields['email'];
      $customer->password = $fields['password'];
      $customer->first_name = $fields['first_name'];
      $customer->last_name = $fields['last_name'];
      $customer->phone = $fields['phone'];

      // We add/change some specific fields
      $customer->role = 'customer';
      $customer->password = Hash::make($customer->password);

      $customer->save();

      // We finally send an email to confirm the account
      $data = [
        'first_name' => $customer->first_name,
      ];

      // Specific to customer, we don't use the classical system
      mailing_send_customer_only($customer, 'Bienvenue sur Bordeaux in Box !', 'auth.emails.customer.welcome', $data, NULL);

      session()->flash('message', "Ton inscription a bien été confirmée !");

      // Auto-connection : on
      Auth::customer()->login($customer);
      
      if (session()->get('after-login-redirection')) {

        return redirect(session()->get('after-login-redirection'));

      } else {

        return redirect()->action('MasterBox\Customer\PurchaseController@getIndex');

      }

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getLogin()
  {
    return view('masterbox.connect.customer.login');
  }

  /**
   * Logout event
   * @return redirect event
   */
  public function getLogout()
  {
    Auth::customer()->logout();
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
      if (Auth::customer()->attempt($authAttempt)) 
      {
        // If there's an after login redirection
        if (session()->get('after-login-redirection')) 
        {
          return redirect(session()->get('after-login-redirection'));
        }

        // In case the customer is already building an order
        if (Auth::customer()->get()->order_building()->first() != NULL) 
        {
          return redirect()->action('MasterBox\Customer\PurchaseController@getIndex');
        }

        // If the customer has clicked on the correct button
        if (session()->get('isOrdering')) 
        {
          if (session()->get('isGift')) 
          {
            return redirect()->action('MasterBox\Customer\PurchaseController@getGift');
          } 
          else 
          {
            return redirect()->action('MasterBox\Customer\PurchaseController@getClassic');
          }
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