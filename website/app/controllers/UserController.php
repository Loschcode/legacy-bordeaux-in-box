<?php

class UserController extends \BaseController {

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
    	
        $this->beforeFilter('isNotConnected', array('except' => 'getLogout'));

    }

	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.master';

    /**
     * Subscribe page
     * @return [type] [description]
     */
    public function getSubscribe()
    {
		$this->layout->content = View::make('user.subscribe');
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

		$fields = Input::all();
		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

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
    		$data = array(

    			'first_name' => $user->first_name,
    			);

    	// Specific to user, we don't use the classical system
			mailing_send_user_only($user, 'Bienvenue sur Bordeaux in Box !', 'emails.user.welcome', $data, NULL);

			Session::flash('message', "Ton inscription a bien été confirmée !");

			// Auto-connection : on
			Auth::login($user);
			return Redirect::to(Session::get('after-login-redirection'));

		} else {

			
			// We return the same page with the error and saving the input datas
			return Redirect::back()
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
		$this->layout->content = View::make('user.login');
	}

	/**
	 * Logout event
	 * @return redirect event
	 */
	public function getLogout()
	{

	  Auth::logout();
	  Session::flush();
	  
	  return Redirect::to("user/login");

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

		$validator = Validator::make(Input::all(), $rules);

		// The form validation was good
		if ($validator->passes()) {

			$authAttempt = $this->getLoginCredentials();

			// We try our credentials
			if (Auth::attempt($authAttempt)) {

				// If there's an after login redirection
				if (Session::get('after-login-redirection')) {

					return Redirect::to(Session::get('after-login-redirection'));

				}

				// Otherwise, if the user is admin
				if (Auth::user()->role === 'admin') {

					return Redirect::to('/admin');

				}

				// In case the user is already building an order
				if (Auth::user()->order_building()->first() != NULL) {

					return Redirect::to('/order');

				}

				// If the user has clicked on the correct button
				if (Session::get('isOrdering')) {

					if (Session::get('isGift')) {

						return Redirect::to('/order/gift');

					} else {

						return Redirect::to('/order/classic');

					}

				}

				return Redirect::back();

			}

			return Redirect::back()->withErrors([
				"email" => ["Identifiants invalides."]
				]);

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
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
		"email" => Input::get("email"),
		"password" => Input::get("password"),
		];

	}


}
