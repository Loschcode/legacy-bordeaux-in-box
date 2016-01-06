<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Carbon\Carbon;

use Request, Validator, Auth, URL;

use App\Models\Box;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfileNote;
use App\Models\DeliverySpot;
use App\Models\OrderDestination;

class ProfilesController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Profiles Controller
	|--------------------------------------------------------------------------
	|
	| Check and edit profiles
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
		
		$profiles = UserProfile::orderBy('created_at', 'desc')->get();

		$config_graph_user_profile_status_progress = $this->user_profile_status_progress_graph_config();

	  return view('admin.profiles.index')->with(compact(
      'profiles',
      'config_graph_user_profile_status_progress'
    ));

	}

  /**
   * We update the priority of the profile
   */
  public function postUpdatePriority()
  {

    $rules = [

      'profile_id' => 'required|numeric',
      'priority' => 'required',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $profile = UserProfile::findOrFail($fields['profile_id']);
      $profile->priority = $fields['priority'];
      $profile->save();

      session()->flash('message', "La priorité de l'abonnement a été mise à jour");
      return redirect()->back();

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to(URL::previous())
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getResetProfilesPriorities()
  {

    $user_profiles = UserProfile::get();

    foreach ($user_profiles as $profile) {

      $profile->priority = 'medium';
      $profile->save();

    }

    session()->flash('message', "Les prioritiés des abonnements ont été réinitialisées");
    return redirect()->back();

  }

	/**
	 * We remove the profile
	 */
	public function getDelete($id)
	{

    $profile = UserProfile::findOrFail($id);
    $profile->delete();

    session()->flash('message', "L'abonnement a bien été supprimé");
    return redirect()->back();

	}

	/**
	 * We a edit a profile
	 */
	public function getEdit($id)
	{

		$profile = UserProfile::findOrFail($id);

    $box = $profile->box()->first();

    if ($box != NULL) {
      $questions = $box->questions()->get();
    } else {
      $questions = [];
    }

    $order_preference = $profile->order_preference()->first();
    $user = $profile->user()->first();

    $next_delivery_order = $profile->orders()->where('locked', FALSE)->whereNull('date_completed')->first();

    if ($next_delivery_order != NULL) {

      $order_destination = $next_delivery_order->destination()->first();
      $order_billing = $next_delivery_order->billing()->first();
      $order_delivery_spot = $next_delivery_order->delivery_spot()->first();

    } else {

      $order_destination = NULL;
      $order_billing = NULL;
      $order_delivery_spot = NULL;

    }

    $delivery_spots = DeliverySpot::where('active', TRUE)->orderBy('created_at', 'desc')->get();

    return view('admin.profiles.edit')->with(compact(
      'delivery_spots',
      'next_delivery_order',
      'order_destination',
      'order_delivery_spot',
      'order_billing',
      'box',
      'user',
      'questions',
      'order_preference',
      'profile'
      ));

	}

	/**
	 * Cancel stripe subscription and all the orders that aren't delivered yet
	 * @param  integer $profile_id 
	 * @return            
	 */
	public function getCancelSubscription($profile_id)
	{

		$profile = UserProfile::findOrFail($profile_id);
		$user = $profile->user()->first();

		$payment_profile = $profile->payment_profile()->first();

		$stripe_customer_id = $payment_profile->stripe_customer;
		$stripe_subscription_id = $payment_profile->stripe_subscription;

		$feedback = Payments::cancelSubscription($stripe_customer_id, $stripe_subscription_id);

		// If the feedback is FALSE
		// It may not have a subscription (within Stripe)
		// But we can cancel everything ANYWAY
		
		$orders_to_cancel = $profile->orders()->where('status', '=', 'scheduled')->get();

		foreach ($orders_to_cancel as $order) {

			$order->status = 'canceled';
			$order->save();

		}

		$profile->status = 'expired';
		$profile->save();

    // We send an email to get the customer back
    $profile->sendExpirationEmail(FALSE);

		if ($feedback == FALSE) {

			session()->flash('error', "Aucun abonnement n'a été trouvé sur les serveurs Stripe. L'annulation est locale uniquement.");
			session()->flash('message', "L'abonnement de l'utilisateur a été correctement annulé");

		} else {

			session()->flash('message', "L'abonnement de l'utilisateur a été correctement annulé");

		}

		return redirect()->to(URL::previous() . '#deliveries');

	}


	public function postAddNote()
	{

		$rules = [

			'user_profile_id' => 'required|numeric',

			'note' => 'required',

			];

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$profile = UserProfile::findOrFail($fields['user_profile_id']);
			
			$note = new UserProfileNote;
			$note->user_profile_id = $profile->id;
			$note->user_id = Auth::user()->id;
			$note->note = $fields['note'];

			$note->save();

			// Then we redirect
			session()->flash('message', "Votre note a été ajoutée");
			return redirect()->back();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->to(URL::previous())
			->withInput()
			->withErrors($validator);

		}


	}


  public function postEditSpot()
  {

    $rules = [

      'user_profile_id' => 'required|numeric',
      'selected_spot' => 'required|numeric',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $profile = UserProfile::find($fields['user_profile_id']);
      $next_orders = $profile->orders()->where('locked', FALSE)->get();

      $delivery_spot = DeliverySpot::find($fields['selected_spot']);
      if ($delivery_spot === NULL) return redirect()->to(URL::previous() . '#deliveries');

      foreach ($next_orders as $order) {

        $order->delivery_spot_id = $delivery_spot->id;
        $order->save();

      }

      // Then we redirect
      session()->flash('message', "Le point relais de l'utilisateur a été correctement mise à jour");
      return redirect()->to(URL::previous() . '#deliveries');

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to(URL::previous() . '#deliveries')
      ->withInput()
      ->withErrors($validator, 'delivery');

    }

  }

  /**
   * Generate order_destinations for each order that doesn't include one
   */
  public function getGenerateDeliveryAddress($profile_id)
  {

    $profile = UserProfile::find($profile_id);

    $next_orders = $profile->orders()->where('locked', FALSE)->get();

    foreach ($next_orders as $order) {

      $order_billing = $order->billing()->first();
      $order_destination = $order->destination()->first();

      if ($order_destination === NULL) {

        $order_destination = new OrderDestination;

        // We refresh the destination informations
        $order_destination->order_id = $order->id;
        $order_destination->first_name = $order_billing->first_name;
        $order_destination->last_name = $order_billing->last_name;
        $order_destination->city = $order_billing->city;
        $order_destination->zip = $order_billing->zip;
        $order_destination->address = $order_billing->address;

        $order_destination->save();

      }

    }

    // Then we redirect
    session()->flash('message', "L'adresse de livraison de l'utilisateur a été correctement générée");
    return redirect()->to(URL::previous() . '#deliveries');

  }


	public function postEditDelivery()
	{

		$rules = [

			'user_profile_id' => 'required|numeric',

			'destination_first_name' => 'required',
			'destination_last_name' => 'required',
			'destination_city' => 'required',
			'destination_zip' => 'required',
			'destination_address' => 'required',

			];

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$profile = UserProfile::find($fields['user_profile_id']);
			$next_orders = $profile->orders()->where('locked', FALSE)->get();

			foreach ($next_orders as $order) {

				$order_destination = $order->destination()->first();

				// We refresh the destination informations
				$order_destination->first_name = $fields['destination_first_name'];
				$order_destination->last_name = $fields['destination_last_name'];
				$order_destination->city = $fields['destination_city'];
				$order_destination->zip = $fields['destination_zip'];
				$order_destination->address = $fields['destination_address'];

				$order_destination->save();

			}

			// Then we redirect
			session()->flash('message', "L'adresse de livraison de l'utilisateur a été correctement mise à jour");
			return redirect()->to(URL::previous() . '#deliveries');

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->to(URL::previous() . '#deliveries')
			->withInput()
			->withErrors($validator, 'delivery');

		}

	}

  /**
   * Simple charge without subscription reset
   * @param  integer $profile_id
   * @return
   */
  public function getForcePay($profile_id)
  {

    $profile = UserProfile::find($profile_id);
    $user = $profile->user()->first();
    $stripe_customer = $profile->stripe_customer;

    $order_preference = $profile->order_preference()->first();
    $raw_amount = $order_preference->totalPricePerMonth();

    $callback = Payments::invoice($stripe_customer, $user, $profile, $raw_amount);

    if (is_array($callback)) {

      session()->flash('error', "Impossible de faire payer ce profil");
      return redirect()->to(URL::previous() . '#paiements');

    }

    session()->flash('message', "Le profile vient d'être chargé de $raw_amount euro");
    return redirect()->to(URL::previous() . '#paiements');

  }

  /**
   * Cancel the subscription and add a new subscription on the same plan
   * NOTE : Useful when you want to set a new date for a subscription invoice
   * @param  integer $profile_id
   * @return
   */
  public function getResetSubscriptionAndPay($profile_id)
  {

    $profile = UserProfile::find($profile_id);
    $user = $profile->user()->first();

    $stripe_customer = $profile->stripe_customer;

    $payment_profile = $profile->payment_profile()->orderBy('created_at', 'desc')->first();
    $stripe_subscription = $payment_profile->stripe_subscription;

    $plan_name = $payment_profile->stripe_plan;

    $order_preference = $profile->order_preference()->first();
    $plan_price = $order_preference->totalPricePerMonth();

    /**
     * We cancel the subscription first
     */
    $callback = Payments::cancelSubscription($stripe_customer, $stripe_subscription);
    if ($callback === FALSE) session()->flash('error', "Aucun abonnement n'a pu être annulé via Stripe");

    /**
     * We artificially create a new subscription with the same data
     */
    $callback = Payments::makeSubscription($stripe_customer, $user, $profile, $plan_name, $plan_price);
    
    if (is_array($callback)) {

      session()->flash('error', "Impossible de créer le nouvel abonnement");
      return redirect()->to(URL::previous() . '#paiements');

    }
    
    /**
     * We update the subscription in all the areas we need to
     */
    $payment_profile->stripe_subscription = $callback;
    $payment_profile->save();

    session()->flash('message', "L'abonnement a bien été réinitialisé");
    return redirect()->to(URL::previous() . '#paiements');

  }

	public function getAddDelivery($profile_id)
	{

		$profile = UserProfile::find($profile_id);
		$user = $profile->user()->first();

		generate_new_order($user, $profile);

		session()->flash('message', "Une livraison a été ajoutée pour cet utilisateur");
		return redirect()->to(URL::previous() . '#deliveries');

	}


	public function postEditQuestions()
	{

		$fields = Request::all();
		$rules = array();

		// If there's no box_id it means it's certainly a hack
		if (!isset($fields['box_id'])) return redirect()->to('/');

		// If we don't find the box, there's a bug somewhere (or a hack)
		$box = Box::find($fields['box_id']);
		if ($box === NULL) return redirect()->back();

		$profile = UserProfile::find($fields['user_profile_id']);
		if ($profile === NULL) return redirect()->back();

		// Let's generate the rules
		foreach ($box->questions()->get() as $question) {

			// Checkbox aren't mandatory
      // It's the ADMIN section so we don't specify much rules, not like the OrderController side 
			if ($question->type != 'checkbox') {

				$rules[$question->id.'-0'] = ''; // Nothing is required anymore -> //'required';

			}

		}

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

      refresh_answers_from_dynamic_questions_form($fields, $profile);

			session()->flash('message', "Les réponses de l'utilisateur ont été correctement mises à jour");
			return redirect()->to(URL::previous() . '#questions');

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->to(URL::previous() . '#questions')
			->withInput()
			->withErrors($validator);

		}

	}


  public function user_profile_status_progress_graph_config()
  {

    $graph_data = array();

    $grouped_profiles = UserProfile::select('id', 'status', 'status_updated_at')
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->status_updated_at)->format('Y/m/d');
  
    });


    foreach ($grouped_profiles as $profiles) {

        // Big stats about everything here
        $not_subscribed_counter = 0;
        $in_progress_counter = 0;
        $subscribed_counter = 0;
        $expired_counter = 0;

        // We will loop everything to make the statistics possible
        foreach ($profiles as $profile) {

          // If it's effective (no fail)
          if ($profile->status == 'not-subscribed') {

          	$not_subscribed_counter++;

          } elseif ($profile->status == 'in-progress') {

          	$in_progress_counter++;

          } elseif ($profile->status == 'subscribed') {

          	$subscribed_counter++;

          } elseif ($profile->status == 'expired') {

          	$expired_counter++;

          }

        }

        array_push($graph_data, [

        'date' => $profiles[0]->status_updated_at->format('Y-m-d'), 
        'not-subscribed' => $not_subscribed_counter,
        'in-progress' => $in_progress_counter,
        'subscribed' => $subscribed_counter,
        'expired' => $expired_counter

          ]);

    }

    $config_graph = [

          'id' => 'graph-user-profile-progress',
          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => ['not-subscribed', 'in-progress', 'subscribed', 'expired'],
          'labels' => ['Non abonné', 'En création', 'Abonné', 'Expiré'],

          "xLabels" => 'week',

          'lineColors' => convert_to_graph_colors(['grey', 'green', 'blue', 'red']),

        ];

    return $config_graph;

	}


}