<?php namespace App\Http\Controllers;

class AdminUsersController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Spot Controller
	|--------------------------------------------------------------------------
	|
	| Add / Edit / Delete / Activate / Desactivate spots
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
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.admin';

    /**
     * Get the listing page of the spots
     * @return void
     */
	public function getIndex()
	{

		$users = User::orderBy('created_at', 'desc')->get();
		View::share('users', $users);

		$this->layout->content = View::make('admin.users.index');

	}

    /**
     * Focus on a user
     * @return void
     */
	public function getFocus($id)
	{

		$user = User::find($id);
		View::share('user', $user);

		$roles_list = [

			'admin' => 'Administrateur',
			'user' => 'Utilisateur'

		];

		View::share('roles_list', $roles_list);

		$this->layout->content = View::make('admin.users.focus');

	}

	public function postEdit()
	{

		// New article rules
		$rules = [

			'user_id' => 'required|integer',
			'email' => 'required',
			'password' => '',
			'role' => 'required',

			'phone' => 'required',

			'first_name' => 'required',
			'last_name' => 'required', 

			'address' => 'required',
			'zip' => 'required',
			'city' => 'required'

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$user = User::find($fields['user_id']);

			if ($user !== NULL)
			{

				$user->email = $fields['email'];

				if (!empty($fields['password'])) {

					$user->password = Hash::make($fields['password']);
				}

				$user->role = $fields['role'];

				$user->phone = $fields['phone'];

				$user->first_name = $fields['first_name'];
				$user->last_name = $fields['last_name'];

				$user->zip = $fields['zip'];
				$user->city = $fields['city'];
				$user->address = $fields['address'];

				$user->save();

				// If the user got profiles we will edit the next deliveries
				if ($user->profiles()->first() != NULL) {

					$profiles = $user->profiles()->get();

					foreach ($profiles as $profile) {

						if ($profile->orders()->first() != NULL) {

							// Only for editable orders
							$profile_orders = $profile->orders()->where('locked', FALSE)->get();

							foreach ($profile_orders as $profile_order) {

								if ($profile_order->billing()->first() != NULL) {

									$billing = $profile_order->billing()->first();

									$billing->first_name = $user->first_name;
									$billing->last_name = $user->last_name;
									$billing->zip = $user->zip;
									$billing->city = $user->city;
									$billing->address = $user->address;

									// We save everything
									$billing->save();

								}

							}

						}

					}

				}

			}

			return Redirect::to('/admin/users')
			->withInput()
			->with('message', 'L\'utilisateur à bien été modifié');

		} else {

			Session::flash('error', 'Il y a des erreurs dans le formulaire');

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}

}