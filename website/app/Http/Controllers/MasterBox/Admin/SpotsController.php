<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator;

use App\Models\DeliverySpot;
use App\Models\UserProfile;

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
     * Get the listing page of the spots
     * @return void
     */
	public function getIndex()
	{

		$active_spots = DeliverySpot::where('active', TRUE)->orderBy('created_at', 'desc')->get();
		$unactive_spots = DeliverySpot::where('active', FALSE)->orderBy('created_at', 'desc')->get();
		
		$spots_list = $this->generate_active_spots_list();

		return view('admin.spots.index')->with(compact(
      'spots_list',
      'active_spots',
      'unactive_spots'
    ));

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

		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$old_spot = DeliverySpot::find($fields['old_spot']);
			$new_spot = DeliverySpot::find($fields['new_spot']);

			if (($old_spot === NULL) || ($new_spot === NULL)) {

				return redirect()->back()
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

			session()->flash('message', "Les commandes du point relais ont correctement été transférés");
			return redirect()->back();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
			->withInput()
			->withErrors($validator);

		}



	}


	/**
	 * We remove the spot
	 */
	public function getDelete($id)
	{

		$spot = DeliverySpot::findOrFail($id);

		$spot->delete();

		session()->flash('message', "Le point relais a été correctement supprimé");
		return redirect()->back();

	}

	/**
	 * We desactivate the spot
	 */
	public function getDesactivate($id)
	{
		$spot = DeliverySpot::findOrFail($id);

		$spot->active = FALSE;
		$spot->save();

		session()->flash('message', "Le point relais a été désactivé");
		return redirect()->to('admin/spots#offline');
	}


	/**
	 * We desactivate the spot
	 */
	public function getActivate($id)
	{

		$spot = DeliverySpot::findOrFail($id);

		$spot->active = TRUE;
		$spot->save();

		session()->flash('message', "Le point relais a été activé");
		return redirect()->back();

	}


	/**
	 * We a edit an spot
	 */
	public function getEdit($id)
	{

		$spot = DeliverySpot::findOrFail($id);

		return view('admin.spots.edit')->with(compact(
      'spot'
    ));
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


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$delivery_spot = DeliverySpot::findOrFail($fields['delivery_spot_id']);


			$delivery_spot->name = $fields['name'];
			$delivery_spot->zip = $fields['zip'];
			$delivery_spot->city = $fields['city'];
			$delivery_spot->address = $fields['address'];
			$delivery_spot->schedule = $fields['schedule'];

			$delivery_spot->save();

			return redirect()->to('/admin/spots')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
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

		return view('admin.spots.new');

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


		$fields = Request::all();

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

			return redirect()->to('/admin/spots')
			->with('message', 'Le point relais à été ajouté avec succès')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
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