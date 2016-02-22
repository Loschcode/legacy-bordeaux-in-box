<?php namespace App\Http\Controllers\MasterBox\Connect;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator, Redirect, Hash, Auth, OAuth, Input;

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
    $this->middleware('is.not.customer', array('except' => 'getLogout'));
  }

  public function getIndex()
  {

    /**
     * We either Login the user or go to the homepage
     * Protected already via middleware
     */
    if (Auth::guard('customer')->guest()) {

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
      $customer->password = Hash::make($customer->password);

      $customer->save();

      // We finally send an email to confirm the account
      $data = [
        'first_name' => $customer->first_name,
      ];

      // Specific to customer, we don't use the classical system
      mailing_send_customer_only($customer, 'Bienvenue sur Bordeaux in Box !', 'shared.emails.connect.welcome', $data, NULL);

      session()->flash('message', "Ton inscription a bien été confirmée !");

      // Auto-connection : on
      Auth::guard('customer')->login($customer);

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
    Auth::guard('customer')->logout();
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
    if ($validator->passes()) {

      $authAttempt = $this->getLoginCredentials();

      // We try our credentials
      if (Auth::guard('customer')->attempt($authAttempt)) {

        // If there's an after login redirection
        if (session()->get('after-login-redirection')) 
          return redirect(session()->get('after-login-redirection'));

        return redirect()->back();

      }

      return redirect()->back()->withErrors([
        "email" => ["Identifiants invalides."]
        ]);

    } else {

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


  public function getLoginWithTwitter()
  {

    $token = Request::input('oauth_token');
    $verify = Request::input('oauth_verifier');

    // get twitter service
    $twitter = OAuth::consumer('Twitter');

    // Sign-in engaged
    if (!empty($token) && !empty($verify)) {

        // This was a callback request from Twitter, get the token
        $token = $twitter->requestAccessToken($token, $verify);

        // Send a request with it
        $result = json_decode($twitter->request('account/verify_credentials.json'), TRUE);

        // We get some datas
        $twitter_id = $result['id'];
        $twitter_email = Str::slug($result['name']) . '-' . $result['id'] . '@twitter.com'; // Twitter doesn't provide any email, fuckers
        $twitter_first_name = $result['name'];
        $twitter_last_name = "";

        // We create the account or try to get it
        $callback = $this->autologin_or_generate('twitter', $twitter_id, $twitter_email, $twitter_first_name, $twitter_last_name);
      
        if ($callback['success']) {

          // If there's an after login redirection
          if (session()->get('after-login-redirection')) 
            return redirect(session()->get('after-login-redirection'));

          return redirect()->back();

        } else {

          session()->flash('error', $callback['error']);
          return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

        }

    } else {

        $reqToken = $twitter->requestRequestToken();
        $url = $twitter->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));
        return Redirect::to((string)$url);
    }

  }

  public function getLoginWithGoogle()
  {

    // Google service class
    $google = OAuth::consumer('Google');

    // Code returned from Google
    $code = Request::input('code');

    // Sign-in engaged
    if (!empty($code)) {

      // This was a callback request from google, get the token
      $token = $google->requestAccessToken($code);

      // We recover some infos
      $result = json_decode( $google->request('https://www.googleapis.com/oauth2/v1/userinfo'), TRUE);

      // We get some datas
      $google_id = $result['id'];
      $google_email = $result['email'];
      $google_first_name = "";
      $google_last_name = "";

      // We recover the first_name and last_name if we can
      if (isset($result['name'])) {

        $complete_name = explode(" ", $result['name']);

        if (isset($complete_name[0]))
          $google_first_name = $complete_name[0];

        if (isset($complete_name[1]))
          $google_last_name = $complete_name[1];

      }

      // We create the account or try to get it
      $callback = $this->autologin_or_generate('google', $google_id, $google_email, $google_first_name, $google_last_name);
      
      if ($callback['success']) {

        // If there's an after login redirection
        if (session()->get('after-login-redirection')) 
          return redirect(session()->get('after-login-redirection'));

        return redirect()->back();

      } else {

        session()->flash('error', $callback['error']);
        return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

      }

    // Sign-in not engaged
    } else {

      // Ask for an authorization
      $url = $google->getAuthorizationUri();
      return redirect()->to((string)$url);
    }


  }

  public function getLoginWithFacebook()
  {

    // Facebook service class
    $facebook = OAuth::consumer('Facebook');

    // Code returned from Facebook
    $code = Request::input('code');

    // Sign-in engaged
    if (!empty($code)) {

      // This was a callback request from facebook, get the token
      $token = $facebook->requestAccessToken($code);

      // We recover some infos
      $result = json_decode($facebook->request('/me?fields=name,email,first_name,last_name'), TRUE);

      // We get some datas
      $facebook_id = $result['id'];

      if (isset($result['email'])) {

        $facebook_email = time() . uniqid() . 'facebook.com';
        $facebook_email_not_found = TRUE;

      } else {

        $facebook_email = $result['email'];
        $facebook_email_not_found = FALSE;

      }

      $facebook_first_name = $result['first_name'];
      $facebook_last_name = $result['last_name'];

      // We create the account or try to get it
      $callback = $this->autologin_or_generate('facebook', $facebook_id, $facebook_email, $facebook_first_name, $facebook_last_name);
      
      if ($callback['success']) {

        if ($facebook_email_not_found) {

          warning_tech_admin('masterbox.emails.admin.no_facebook_email_found', 'Email Facebook jamais retourné', $callback['customer']);

        }

        // If there's an after login redirection
        if (session()->get('after-login-redirection')) 
          return redirect(session()->get('after-login-redirection'));

        return redirect()->back();

      } else {

        session()->flash('error', $callback['error']);
        return redirect()->action('MasterBox\Connect\CustomerController@getLogin');

      }

    // Sign-in not engaged
    } else {

      // Ask for an authorization
      $url = $facebook->getAuthorizationUri();
      return redirect()->to((string)$url);
    }


  }

  private function autologin_or_generate($provider, $provider_id, $email, $first_name, $last_name)
  {

      $customer = Customer::where('provider', $provider)->where('provider_id', $provider_id)->first();

      // If the account doesn't exist we will create it
      if ($customer === NULL) {

        /**
         * We make sure this address email isn't already used
         */
        $customer = Customer::where('email', '=', $email)->first();

        if ($customer !== NULL)
          return ['success' => FALSE, 'error' => "L'email $email est déjà utilisé par un de nos comptes enregistrés."];

        $customer = new Customer;
        $customer->email = $email;
        $customer->password = '';
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;

        $customer->provider = $provider;
        $customer->provider_id = $provider_id;

        $customer->save();

      }

      Auth::guard('customer')->login($customer);
      return ['success' => TRUE, 'customer' => $customer];

  }


}