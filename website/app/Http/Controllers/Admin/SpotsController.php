<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class SpotsController extends BaseController {

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

		$active_spots = DeliverySpot::where('active', TRUE)->orderBy('created_at', 'desc')->get();
		$unactive_spots = DeliverySpot::where('active', FALSE)->orderBy('created_at', 'desc')->get();
		
		$spots_list = $this->generate_active_spots_list();
		View::share('spots_list', $spots_list);

		View::share('active_spots', $active_spots);
		View::share('unactive_spots', $unactive_spots);

		$this->layout->content = View::make('admin.spots.index');

	}

	/**
	 * Transfer subscriptions from a spot to another
	 * @return void
	 */
	public function postTransferSpotSubscriptions()
	{

		// New article rules
		$rules = [

			'old_spot' => 'required|integer|not_in:0',
			'new_spot' => 'required|integer|not_in:0',

			];

		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$old_spot = DeliverySpot::find($fields['old_spot']);
			$new_spot = DeliverySpot::find($fields['new_spot']);

			if (($old_spot === NULL) || ($new_spot === NULL)) {

				return Redirect::back()
				->withInput()
				->withErrors(['Les points relais ne sont pas valide']);

			}

			$orders = $old_spot->orders()->ActiveOrders()->get();

			/**
			 * Now we will transfer and send an email to each user
			 */
			$profiles = $old_spot->orders()->ActiveOrders()->getUserProfiles()->get(); // Be careful with this bullshit

			$already_delivered = [];

			foreach ($profiles as $profile) {

				// We need to reload the profile because it's a modified model because of user_profiles()
				$profile = UserProfile::find($profile->id);

				// One more conditio just in case
				if ($profile->status === 'subscribed') {

		      $next_orders = $profile->orders()->whereNull('date_sent')->get();

		      /**
		       * We change for all the next orders
		       */
		      foreach ($next_orders as $order) {

		      	if ($order->delivery_spot_id !== $new_spot->id) {

			        $order->delivery_spot_id = $new_spot->id;
			        $order->save();

		      	}

		      }

		      $box = $profile->box()->first();
		      $user = $profile->user()->first();

			    /**
			     * Then we send an email
			     */
					$data = [

						'first_name' => $user->first_name,

						'old_spot_name' => $old_spot->name,

						'new_spot_name' => $new_spot->name,
						'new_spot_name_and_infos' => $new_spot->emailReadableSpot(),
						'new_spot_schedule' => nl2br($new_spot->schedule), // the schedule might be on a couple of lines

						'box_title' => $box->title

					];

					$email = $user->email;

					/**
					 * To avoid bullshit multi-sent for no fucking reason.
					 */
					if (!isset($already_delivered[$email])) {
					
						// Finally we send the email
						mailing_send($profile, "Changement de point relais", 'emails.spots.transfer', $data, NULL);
						$already_delivered[$email] = TRUE;

					}

				}

			}

			Session::flash('message', "Les commandes du point relais ont correctement été transférés");
			return Redirect::back();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}


	/**
	 * We remove the spot
	 */
	public function getDelete($id)
	{

		$spot = DeliverySpot::find($id);

		if ($spot !== NULL)
		{

			$spot->delete();

			Session::flash('message', "Le point relais a été correctement supprimé");
			return Redirect::back();


		}

	}

	/**
	 * We desactivate the spot
	 */
	public function getDesactivate($id)
	{

		$spot = DeliverySpot::find($id);

		if ($spot !== NULL)
		{

			$spot->active = FALSE;
			$spot->save();

			Session::flash('message', "Le point relais a été désactivé");
			return Redirect::to('admin/spots#offline');


		}

	}


	/**
	 * We desactivate the spot
	 */
	public function getActivate($id)
	{

		$spot = DeliverySpot::find($id);

		if ($spot !== NULL)
		{

			$spot->active = TRUE;
			$spot->save();

			Session::flash('message', "Le point relais a été activé");
			return Redirect::back();


		}

	}


	/**
	 * We a edit an spot
	 */
	public function getEdit($id)
	{

		$spot = DeliverySpot::find($id);

		if ($spot !== NULL)
		{

			View::share('spot', $spot);
			$this->layout->content = View::make('admin.spots.edit');

		}


	}

	public function postEdit()
	{

		// New article rules
		$rules = [

			'delivery_spot_id' => 'required|integer',
			'name' => 'required',
			'city' => 'required',
			'address' => 'required',
			'schedule' => 'required',
			'zip' => 'required',

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$delivery_spot = DeliverySpot::find($fields['delivery_spot_id']);

			if ($delivery_spot !== NULL)
			{

				$delivery_spot->name = $fields['name'];
				$delivery_spot->zip = $fields['zip'];
				$delivery_spot->city = $fields['city'];
				$delivery_spot->address = $fields['address'];
				$delivery_spot->schedule = $fields['schedule'];

				$delivery_spot->save();

			}

			return Redirect::to('/admin/spots')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}

    /**
     * Add a new spot
     * @return void
     */
	public function getNew()
	{

		$this->layout->content = View::make('admin.spots.new');

	}

    /**
     * Add a new spot (datas)
     * @return void
     */
	public function postNew()
	{


		// New article rules
		$rules = [

			'name' => 'required',
			'city' => 'required',
			'address' => 'required',
			'schedule' => 'required',
			'zip' => 'required',

			];


		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$delivery_spot = new DeliverySpot;

			$delivery_spot->name = $fields['name'];
			$delivery_spot->zip = $fields['zip'];
			$delivery_spot->city = $fields['city'];
			$delivery_spot->address = $fields['address'];
			$delivery_spot->schedule = $fields['schedule'];
			$delivery_spot->active = TRUE;

			$delivery_spot->save();

			return Redirect::to('/admin/spots')
			->with('message', 'Le point relais à été ajouté avec succès')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}


	}

  private function generate_active_spots_list()
  {

    $spots_list = [0 => '-'];

    $spots = DeliverySpot::where('active', TRUE)->orderBy('created_at', 'desc')->get();
    foreach ($spots as $spot) {

      $spot_id = $spot->id;
      $spots_list[$spot_id] = $spot->name;

    }

    return $spots_list;

  }

}