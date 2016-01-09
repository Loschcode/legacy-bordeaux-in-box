<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator;
use App\Models\User;


class UsersController extends BaseController {

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
    }
    

    /**
     * Get the listing page of the spots
     * @return void
     */
	public function getIndex()
	{

		$users = User::orderBy('created_at', 'desc')->get();

		return view('master-box.admin.users.index')->with(compact(
      'users'
    ));

	}

    /**
     * Focus on a user
     * @return void
     */
	public function getFocus($id)
	{

		$user = User::findOrFail($id);

		$roles_list = [

			'admin' => 'Administrateur',
			'user' => 'Utilisateur'

		];

		return view('master-box.admin.users.focus')->with(compact(
      'roles_list',
      'user'
    ));

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


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$user = User::findOrFail($fields['user_id']);


      $user->email = $fields['email'];

      if ( ! empty($fields['password'])) {

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

			return redirect()->to('/admin/users')
			->withInput()
			->with('message', 'L\'utilisateur à bien été modifié');

		} else {

			session()->flash('error', 'Il y a des erreurs dans le formulaire');

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}



	}

}