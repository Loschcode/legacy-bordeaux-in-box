<?php

class ProfileController extends \BaseController {

	/*
	|--------------------------------------------------------------------------
	| Profile Controller
	|--------------------------------------------------------------------------
	|
	| All about the user profile
	|
	*/

    /**
     * Filters
     */
    public function __construct()
    {
        $this->beforeFilter('isConnected');
    }

	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.master';

    /**
     * Index page
     */
    public function getIndex()
    {

    	$user = Auth::user();
    	$profiles = $user->profiles()->orderBy('created_at', 'desc')->get();

    	// We get the destination (the last editable order destination)
    	$unlocked_orders = Order::where('user_id', $user->id)->where('locked', FALSE)->get();

    	$destination = NULL;
    	$spot = NULL;

    	// We will look through all the unlocked orders
    	// To try to get the destination and spot if it exists
    	foreach ($unlocked_orders as $unlocked_order) {

    		if ($destination == NULL) $destination = $unlocked_order->destination()->first();
    		if ($spot == NULL) $spot = $unlocked_order->delivery_spot()->first();

    	}

    	if ($spot == NULL) $delivery_spots = [];
    	else $delivery_spots = DeliverySpot::where('active', TRUE)->orWhere('id', $spot->id)->get();
		  
      View::share('delivery_spots', $delivery_spots);
    	View::share('user', $user);
    	View::share('profiles', $profiles);
    	View::share('destination', $destination);
    	View::share('spot', $spot);

		$this->layout->content = View::make('profile.index');
    }

    // Check a bill
    public function getBill($bill_id)
    {

    	$user = Auth::user();
    	$payment = Payment::where('bill_id', $bill_id)->first();

    	if ($payment != NULL) {

    		// If it's not the user bill, we redirect him
    		if ($payment->user()->first()->id != $user->id) {

    			return Redirect::to('/profile');

    		}

    		return generate_pdf_bill($payment);

    	}

		return Redirect::to('/profile');

    }


    // Check and download a bill
    public function getDownloadBill($bill_id)
    {

    	$user = Auth::user();
    	$payment = Payment::where('bill_id', $bill_id)->first();

    	if ($payment != NULL) {

    		// If it's not the user bill, we redirect him
    		if ($payment->user()->first()->id != $user->id) {

    			return Redirect::to('/profile');

    		}

    		return generate_pdf_bill($payment, TRUE);

    	}

		return Redirect::to('/profile');

    }

    // Get the orders details from one profile
    public function getOrders($id)
    {

    	$user = Auth::user();

    	// Small protection to be sure it's the correct user
    	if ($user->profiles()->where('id', '=', $id)->first() == NULL) {
    		return Redirect::to('/profile');
    	}

    	$profile = UserProfile::find($id);
    	$orders = $profile->orders()->get();
    	$payments = $profile->payments()->get();
      $payment_profile = $profile->payment_profile()->first();


    	// Protection if there's no box (normaly it shouldn't happend but we never know)
    	if ($profile->box()->first() == NULL) {
    		return Redirect::to('/profile');
    	}
    	
    	View::share('user', $user);
    	View::share('profile', $profile);
      View::share('payment_profile', $payment_profile);

    	View::share('orders', $orders);
    	View::share('payments', $payments);

		$this->layout->content = View::make('profile.orders');


    }


  /**
   * Change bank card
   * @return redirect process
   */
  public function postChangeCard()
  {

    // New article rules
    $rules = [

      'old_password' => 'required|match_password', // Check the old password
      'profile_id' => 'required|integer',
      //'stripeToken' => 'required',

      ];

    $fields = Input::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $user = Auth::user();
      $profile = UserProfile::find($fields['profile_id']);

      if ($profile !== NULL) {

        /**
         * We prepare the user to get some important informations
         */
        $payment_profile = $profile->payment_profile()->first();

        /**
         * We prepare our variables
         */
        $stripe_token = $fields['stripeToken'];
        $stripe_customer = $payment_profile->stripe_customer;
        $stripe_card = $payment_profile->stripe_card;

        /**
         * First we will remove our card
         */
        $card_removed = Payments::removeCard($stripe_customer, $stripe_card); // Sometimes it won't work
        $new_stripe_card = Payments::addCard($stripe_customer, $stripe_token);

        // If something bad happened
        if (is_array($new_stripe_card)) {

          Session::flash('error', $new_stripe_card[0]);
          return Redirect::back();

        }

        $new_stripe_card_last4 = Payments::getLast4FromCard($stripe_customer, $new_stripe_card);

        /**
         * Now we got the new card we will change the informations everywhere within the profile
         */
        
        // User Payment Profile
        $payment_profile->stripe_card = $new_stripe_card;
        $payment_profile->last4 = $new_stripe_card_last4;

        $payment_profile->save();

        Session::flash('message', "Votre carte a bien été mise à jour");
        return Redirect::back();

      }

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }

  }

	/**
	 * Subscribe to the website
	 * @return redirect process
	 */
	public function postEdit()
	{

		// New article rules
		$rules = [

			'old_password' => 'required|match_password', // Check the old password (check on internet.)
			'new_password' => '',

			'phone' => 'required',

			'email' => 'required|email|unique:users,email,'. Auth::user()->id,

			'first_name' => 'required',
			'last_name' => 'required', 

			'address' => '',
			'zip' => 'required',
			'city' => 'required',

			'chosen_spot' => 'integer', // In case it's a spot

			'destination_first_name' => '', // In case it's a delivery
			'destination_last_name' => '', 

			'destination_address' => '',
			'destination_zip' => '',
			'destination_city' => '',


			];

		$fields = Input::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$user = Auth::user();

			if ($user !== NULL)
			{

				$user->email = $fields['email'];

				$user->first_name = $fields['first_name'];
				$user->last_name = $fields['last_name'];

				$user->phone = $fields['phone'];

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

								// Not a take away, it means we will update the destination
								if (($profile_order->take_away == FALSE) && (isset($fields['destination_first_name']))) {



									$destination = $profile_order->destination()->first();
									$destination->first_name = $fields['destination_first_name'];
									$destination->last_name = $fields['destination_last_name'];
									$destination->zip = $fields['destination_zip'];
									$destination->city = $fields['destination_city'];
									$destination->address = $fields['destination_address'];

									// We save everything
									$destination->save();

								// Take away, it means we will update the spot choice
								} elseif ($profile_order->take_away == TRUE) {

									$spot = DeliverySpot::find($fields['chosen_spot']);

									if ($spot != NULL) {

										// We save the new spot
										$profile_order->delivery_spot()->associate($spot);
										$profile_order->save();

									}

								}

							}

						}

					}

				}

			}

      Session::put('message', 'Vos informations ont bien été mises à jour');
      
			return Redirect::back()
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}



	}

}
