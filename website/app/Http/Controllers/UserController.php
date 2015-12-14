<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use Request, Validator, Redirect, Hash, Auth;

use App\Models\User;

class UserController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | User Controller
  |--------------------------------------------------------------------------
  |
  | All about the user registering, login, signout
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {
    $this->middleware('isNotConnected', array('except' => 'getLogout'));
  }

  /**
   * Subscribe page
   * @return [type] [description]
   */
  public function getSubscribe()
  {
    return view('user.subscribe');
  }

  /**
   * Subscribe to the website
   * @return redirect process
   */
  public function postSubscribe()
  {

    // New user rules
    $rules = [

      'first_name' => 'required',
      'last_name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:5|confirmed',

      'phone' => 'required',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) 
    {
      $user = new User;
      $user->email = $fields['email'];
      $user->password = $fields['password'];
      $user->first_name = $fields['first_name'];
      $user->last_name = $fields['last_name'];
      $user->phone = $fields['phone'];

      // We add/change some specific fields
      $user->role = 'user';
      $user->password = Hash::make($user->password);

      $user->save();

      // We finally send an email to confirm the account
      $data = [
        'first_name' => $user->first_name,
      ];

      // Specific to user, we don't use the classical system
      mailing_send_user_only($user, 'Bienvenue sur Bordeaux in Box !', 'emails.user.welcome', $data, NULL);

      session()->flash('message', "Ton inscription a bien été confirmée !");

      // Auto-connection : on
      Auth::login($user);
      
      if (session()->get('after-login-redirection')) {

        return redirect(session()->get('after-login-redirection'));

      } else {

        return redirect('/order');

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
    return view('user.login');
  }

  /**
   * Logout event
   * @return redirect event
   */
  public function getLogout()
  {
    Auth::logout();
    session()->flush();
    
    return redirect('user/login');
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
      if (Auth::attempt($authAttempt)) 
      {
        // If there's an after login redirection
        if (session()->get('after-login-redirection')) 
        {
          return redirect(session()->get('after-login-redirection'));
        }

        // Otherwise, if the user is admin
        if (Auth::user()->role === 'admin') 
        {
          return redirect('/admin');
        }

        // In case the user is already building an order
        if (Auth::user()->order_building()->first() != NULL) 
        {
          return redirect('/order');
        }

        // If the user has clicked on the correct button
        if (session()->get('isOrdering')) 
        {
          if (session()->get('isGift')) 
          {
            return redirect()->to('/order/gift');
          } 
          else 
          {
            return redirect()->to('/order/classic');
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