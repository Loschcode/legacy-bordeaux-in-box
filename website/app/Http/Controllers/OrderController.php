<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Session, Auth, Request, Redirect, URL;

use App\Models\Box;
use App\Models\DeliverySerie;

class OrderController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | Home page system
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {
    $this->middleware('isConnected', array('except' => ['getClassic', 'getGift']));
    $this->middleware('hasOrderBuilding', array('except' => ['getClassic', 'getGift']));
    $this->middleware('belowSerieCounter', array('except' => ['postPayment']));
    $this->middleware('isNotRegionalOrTakeAway', array('only' => ['getChooseSpot', 'postChooseSpot']));
  }

  /**
   * Automatic redirect to the current step
   */
  public function getIndex()
  {
    $redirect = $this->guessStepFromUser();
    return redirect($redirect);
  }

  /**
   * Order classic way (general access, can be unknown user)
   */
  public function getClassic()
  {

    Session::put('isOrdering', TRUE);
    Session::put('isGift', FALSE);
    
    if (Auth::guest()) 
    {
      Session::put('after-login-redirection', Request::url());
      return redirect('user/subscribe');
    }

    $redirect = $this->guessStepFromUser();

    return redirect($redirect);
  }

  /**
   * Order gift way (general access, can be unknown user)
   */
  public function getGift()
  {
    Session::put('isOrdering', TRUE);
    Session::put('isGift', TRUE);

    if (Auth::guest()) 
    {
      Session::put('after-login-redirection', Request::url());
      return redirect('user/subscribe');
    }

    $redirect = $this->guessStepFromUser();

    return redirect($redirect);

  }

  /**
   * Choose box page
   */
  public function getChooseBox()
  {

    $boxes = Box::where('active', TRUE)->get();

    $user = Auth::user();

    $order_building = $user->order_building()->first();
    $order_preference = $order_building->order_preference()->first();

    return view('order.choose_box')->with(compact(
      'boxes', 
      'order_preference'
    ));

  }

  /**
   * The user chose a box
   */
  public function postChooseBox()
  {

    $rules = [

      'box_choice' => 'required|integer',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) 
    {
      $box = Box::find($fields['box_choice']);
      if ($box === NULL) return Redirect::back();

      $user = Auth::user();

      // We update the current step
      $order_building = $user->order_building()->first();
      $order_building->step = 'box-form';
      $order_building->save();

      // We update the profile
      $user_profile = $order_building->profile()->first();

      $user_profile->user()->associate($user);
      $user_profile->box()->associate($box);
      $user_profile->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }


  }

  /**
   * Form depending on the box page
   */
  public function getBoxForm()
  {

    $user = Auth::user();

    $order_building = $user->order_building()->first();
    $profile = $order_building->profile()->first();

    $box = $profile->box()->first();
    if ($box === NULL) 
    {
      return Redirect::back();
    }

    $questions = $box->questions()->orderBy('position', 'asc')->get();
    $order_preference = $order_building->order_preference()->first();

    // Back case
    //$answers = $profile->answers();
    //view()->share('answers', $answers);

    return view('order.box_form')->with(compact('profile', 'box', 'questions', 'order_preference'));

  }

  /**
   * We add the answer to the profile of the user
   */
  public function postBoxForm()
  {

    // Set a flag to know if we already passed by the validation
    Session::flash('flag-box-form', true);

    $user = Auth::user();

    $order_building = $user->order_building()->first();
    $profile = $order_building->profile()->first();

    // We auto trim everything
    //Input::merge(array_map('trim', Input::all()));

    $fields = Request::all();
    $rules = [];

    // If there's no box_id it means it's certainly a hack
    if (!isset($fields['box_id'])) return Redirect::to('/');

    // If we don't find the box, there's a bug somewhere (or a hack)
    $box = Box::find($fields['box_id']);
    if ($box === NULL) return Redirect::back();

    // Let's generate the rules
    foreach ($box->questions()->orderBy('position', 'asc')->get() as $question) 
    {

      // Checkbox aren't mandatory
      if ($question->type != 'checkbox') 
      {
        if ($question->type == 'date') 
        {
          $rules[$question->id.'-0'] = ['required', 'regex:#^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$#'];
        } 
        elseif ($question->type == 'member_email') 
        {
          $rules[$question->id.'-0'] = ['email', 'exists:users,email', 'not_in:'.$user->email];
        } 
        elseif ($question->type == 'children_details') 
        {
          $rules[$question->id.'-0'] = ['array'];
        } 
        else 
        {
          $rules[$question->id.'-0'] = ['required'];
        }

      }

    }

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) 
    {
      refresh_answers_from_dynamic_questions_form($fields, $profile);

      // Let's go to the next step
      $order_building->step = 'choose-frequency';
      $order_building->save();

      // We change the profile status (the guy filled the form)
      $profile->status = 'in-progress';
      $profile->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } 
    else 
    {
      $messages = $validator->messages()->toArray();

      // We get the key
      foreach ($messages as $tag => $value) 
      {
        $return_tag = $tag;
        break;
      }

      // We return the same page with the error and saving the input datas
      return redirect(URL::previous() . '#' . $return_tag)
      ->withInput()
      ->withErrors($validator);

    }
  }

  /**
   * Choose frequency page
   */
  public function getChooseFrequency()
  {

    $next_series = DeliverySerie::nextOpenSeries();

    $user = Auth::user();

    $order_building = $user->order_building()->first();
    $order_preference = $order_building->order_preference()->first();

    $delivery_prices = DeliveryPrice::where('gift', $order_preference->gift)->orderBy('unity_price', 'asc')->get();

    return view('order.choose_frequency')->with(compact('next_series', 'delivery_prices', 'order_preference'));

  }

  /**
   * Frequency was chosen
   */
  public function postChooseFrequency()
  {

    $rules = [

      'delivery_price' => 'required|integer',

      ];

    $fields = Input::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $delivery_price = DeliveryPrice::find($fields['delivery_price']);

      if ($delivery_price === NULL) return Redirect::back();

      $user = Auth::user();
      $order_building = $user->order_building()->first();
      
      $order_preference = $order_building->order_preference()->first();

      // We duplicate the delivery price (because it could change with the time)
      $order_preference->frequency = $delivery_price->frequency;
      $order_preference->unity_price = $delivery_price->unity_price;
      $order_preference->save();

      // Let's go to the next step
      $order_building->step = 'billing-address';
      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return Redirect::to($redirect);

      //return Redirect::back();

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getBillingAddress()
  {

    $user = Auth::user();

    $order_building = $user->order_building()->first();
    $order_preference = $order_building->order_preference()->first();

    return view('order.billing_address')->with(compact('user', 'order_building', 'order_preference'));

  }

  public function postBillingAddress()
  {

    Session::put('flag-billing-address', true);

    $rules = [

      'billing_first_name' => 'required',
      'billing_last_name' => 'required',
      'billing_city' => 'required',
      'billing_zip' => 'required',
      'billing_address' => 'required',

      'destination_first_name' => 'required',
      'destination_last_name' => 'required',
      'destination_city' => 'required',
      'destination_zip' => 'required',
      'destination_address' => 'required',

      ];

    // We auto trim everything
    Input::merge(array_map('trim', Input::all()));

    $fields = Input::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $user = Auth::user();
      $order_building = $user->order_building()->first();
      $order_preference = $order_building->order_preference()->first();
      
      // If the user hasn't billing address yet
      if ((!$user->hasBillingAddress()) || ($user->profiles()->count() <= 1)) {

        // We refresh the billing informations
        $user->city = $fields['billing_city'];
        $user->zip = $fields['billing_zip'];
        $user->address = $fields['billing_address'];
        $user->save();

      }

      // We refresh the destination informations
      $order_building->destination_first_name = $fields['destination_first_name'];
      $order_building->destination_last_name = $fields['destination_last_name'];
      $order_building->destination_city = $fields['destination_city'];
      $order_building->destination_zip = $fields['destination_zip'];
      $order_building->destination_address = $fields['destination_address'];

      // Let's go to the next step
      $order_building->step = 'delivery-mode';
      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getDeliveryMode()
  {

    $user = Auth::user();
    $order_building = $user->order_building()->first();
    $order_preference = $order_building->order_preference()->first();

    // Back
    view()->share('order_preference', $order_preference);

    if ( ! $order_building->isRegionalAddress()) {
      // He's not from Gironde, then he has imposed fees and no other choice
      $delivery_fees_per_delivery = DeliverySetting::first()->national_delivery_fees;

      /**
       * If it's a gift, we calculate the number of deliveries and add it directly to the price
       */
      if ($order_preference->gift) {
        $order_preference->delivery_fees = $delivery_fees_per_delivery * $order_preference->frequency;
      } else {
        $order_preference->delivery_fees = $delivery_fees_per_delivery;
      }

      $order_preference->take_away = FALSE;
      $order_preference->save();

      // Let's go to the next step
      $order_building->step = 'payment';
      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    }

    return view('order.delivery_mode');

  }

  public function postDeliveryMode()
  {

    $rules = [

      'take_away' => 'required|integer',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $user = Auth::user();
      $order_building = $user->order_building()->first();
      $order_preference = $order_building->order_preference()->first();

      $order_preference->take_away = $fields['take_away'];
      if ($order_preference->take_away) {
        $order_preference->delivery_fees = 0;
      } else {
        $delivery_fees_per_delivery = DeliverySetting::first()->regional_delivery_fees;

        // If it's a gift we calculate the fees for all the months directly now
        if ($order_preference->gift) {

          $order_preference->delivery_fees = $delivery_fees_per_delivery * $order_preference->frequency;
        } else {  
          $order_preference->delivery_fees = $delivery_fees_per_delivery;
        }
      
      }

      $order_preference->save();

      // Let's go to the next step
      if ($order_preference->take_away) $order_building->step = 'choose-spot';
      else $order_building->step = 'payment';

      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getChooseSpot()
  {

    $user = Auth::user();
    $order_building = $user->order_building()->first();
    $order_preference = $order_building->order_preference()->first();

    // In case the user come back
    $chosen_delivery_spot = $order_preference->delivery_spot()->first();

    if ($chosen_delivery_spot === NULL) $chosen_delivery_spot = 0;
    else $chosen_delivery_spot = $chosen_delivery_spot->id;

    $delivery_spots = DeliverySpot::where('active', TRUE)->get();

    return view('order.choose_spot')->with(compact(
      'chosen_delivery_spot',
      'delivery_spot'
    ));

  }

  public function postChooseSpot()
  {

    $rules = [

      'chosen_spot' => 'required|integer',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $user = Auth::user();
      $order_building = $user->order_building()->first();
      $order_preference = $order_building->order_preference()->first();

      $delivery_spot = DeliverySpot::find($fields['chosen_spot']);
      if ($delivery_spot === NULL) return Redirect::back();

      // We link the user preference to the delivery spot
      $order_preference->delivery_spot()->associate($delivery_spot);
      $order_preference->save();

      // We go to the next step
      $order_building->step = 'payment';
      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);
    }


  }

  public function getPayment()
  {
    $user = Auth::user();
    $order_building = $user->order_building()->first();
    $profile = $order_building->profile()->first();
    $order_preference = $order_building->order_preference()->first();
    $delivery_spot = $order_preference->delivery_spot()->first(); // May be NULL

    view('order.payment')->with(compact(
      'user',
      'order_building',
      'profile',
      'order_preference',
      'delivery_spot'
    ));

  }

  public function postPayment()
  {

    $rules = [

      'stripeToken' => 'required',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $stripe_token = $fields['stripeToken'];

      $user = Auth::user();
      $order_building = $user->order_building()->first();
      $profile = $order_building->profile()->first();

      /**
       * Stripe process system
       */

      // We make a new payment profile
      $payment_profile = new UserPaymentProfile;
      $payment_profile->profile()->associate($profile);

      /**
       * We will store the stripe card id
       */
      $payment_profile->stripe_token = $stripe_token;
      $payment_profile->save();

      /**
       * If the stripe customer doesn't exist we create it and store it to the users table
       * (And not another table, there's only one customer per account, make sense ?)
       */
      //if (!$user->stripe_customer) {
      //

        $stripe_customer = Payments::makeCustomer($stripe_token, $user, $profile);

        if (is_array($stripe_customer)) {

          return Redirect::back()->withErrors([
            "stripeToken" => $stripe_customer
          ]);
        }

        // We change the guy to "subscribed" because we made a new customer right now
        $profile->status = 'subscribed';

        // Then we come back to our payment process
        $profile->stripe_customer = $stripe_customer;
        $payment_profile->stripe_customer = $stripe_customer;
        $payment_profile->stripe_card = Payments::retrieveLastCard($stripe_customer);

        $profile->save();
        $payment_profile->save();

      //}

      // We retrieve the stripe customer data (from old or new customer)
      $stripe_customer = $profile->stripe_customer;

      /**
       * We build the orders
       */
      $order_preference = $order_building->order_preference()->first();


      // We look how many boxes we will send
      // The infinite plan is special, the num orders is artificial (6 months default)
      if ($order_preference->frequency == 0) $num_orders = 3;
      else $num_orders = $order_preference->frequency;

      $delivery_series = DeliverySerie::nextOpenSeries();
      $delivery_spot = $order_preference->delivery_spot()->first();

      /**
       * Small explanation :
       * If it's a gift, the guy will pay everything in a row
       * But each order will get a "paid" status (if everything goes well)
       * So each order as a unity price like the casual delivery
       * (it will done in cascade with the stripe callback)
       */
      if ($order_preference->gift) {
        $unity_and_fees_price = $order_preference->totalPricePerMonth() / $order_preference->frequency;
      } else {  
        $unity_and_fees_price = $order_preference->totalPricePerMonth();
      }

      $num = 0;

      while ($num < $num_orders) {
        // Matching series
        if (!isset($delivery_series[$num]))  {
          Log::info("ERROR : no enough delivery series to order (checkout OrderController line ~700");

          $profile->orders()->delete();

          return Redirect::back()->withErrors([
            "stripeToken" => ["Une erreur liée aux séries de boxes s'est produite, veuillez réessayer ultérieurement ou contacter notre support si le problème persiste."]
          ]);

        }

        $delivery_serie = $delivery_series[$num];

        $box = $profile->box()->first();

        /**
         * Duplicate protection
         * If someone blows up the system we absolutely must avoid double orders
         * which are the same. So we avoid the creation if needed
         */
        $order_already_exists = Order::where('user_profile_id', '=', $profile->id)->where('delivery_serie_id', '=', $delivery_serie->id)->first();

        if ($order_already_exists === NULL) {
          // We make the order
          $order = new Order;
          $order->user()->associate($user);
          $order->user_profile()->associate($profile);
          $order->delivery_serie()->associate($delivery_serie);
          $order->box()->associate($box);

          // We don't lock the new orders
          $order->locked = FALSE;

          // If there's a spot (take away only)
          if ($delivery_spot !== NULL) $order->delivery_spot()->associate($delivery_spot);

          $order->status = 'scheduled';
          $order->gift = $order_preference->gift;
          $order->take_away = $order_preference->take_away;
          $order->unity_and_fees_price = $unity_and_fees_price;
          $order->save();

          // We make the order billing
          $order_billing = new OrderBilling;
          $order_billing->order()->associate($order);
          $order_billing->first_name = $user->first_name;
          $order_billing->last_name = $user->last_name;
          $order_billing->city = $user->city;
          $order_billing->address = $user->address;
          $order_billing->zip = $user->zip;
          $order_billing->save();

          // We make the order destination
          $order_destination = new OrderDestination;
          $order_destination->order()->associate($order);
          $order_destination->first_name = $order_building->destination_first_name;
          $order_destination->last_name = $order_building->destination_last_name;
          $order_destination->city = $order_building->destination_city;
          $order_destination->address = $order_building->destination_address;
          $order_destination->zip = $order_building->destination_zip;
          $order_destination->save();

        }

        $num++;

      }

      /**
       * We finally invoice the user (no feedback here, we have InvoicesController to handle it)
       */
      if (($order_preference->gift) || ($order_preference->frequency == 1)) {
        // If it's a gift it's a direct invoice
        $feedback = Payments::invoice($stripe_customer, $user, $profile, $order_preference->totalPricePerMonth());

        if ($feedback !== TRUE) {
          // Not sure about it, but to be clean we might delete the orders we just built
          $profile->orders()->delete();

          return Redirect::back()->withErrors([
            "stripeToken" => $feedback
          ]);

        }

      } else {

        // If it's not a gift, even for 1 month subscription we will subscribe and directly cancel after the payment
        
        $plan_price = $order_preference->totalPricePerMonth();
        $plan_name = 'plan' . $plan_price * 100;
        $order_preference->stripe_plan = $plan_name;
        $order_preference->save();

        $feedback = Payments::makeSubscription($stripe_customer, $user, $profile, $plan_name, $plan_price);

        if (is_array($feedback)) {

          // Not sure about it, but to be clean we might delete the orders we just built
          $profile->orders()->delete();

          return Redirect::back()->withErrors([
          "stripeToken" => $feedback
          ]);

        } else {

          $payment_profile->stripe_subscription = $feedback;
          $payment_profile->stripe_plan = $plan_name;

          $payment_profile->save();
        }

      }

      return redirect('/order/confirmed');

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);
    }
  }

  public function getConfirmed()
  {
    // We will delete the user building system because we don't need it anymore
    Auth::user()->order_building()->first()->delete();

    return view('order.confirmed');
  }

  private function isCorrectStep($step)
  {

    if (Auth::user()->order_building()->first()->step == $step) return TRUE;
    else return FALSE;

  }

  private function guessStepFromUser()
  {

    /**
     * ORDER :
     * - Choose box
     * - Fill box form
     * - Choose frequency
     * - Billing address and details
     * - Choose delivery mode (can be skipped if outside allowed area)
     * - Fill payment
     * - Resumee
     */
    
    $user = Auth::user();
    $next_series = DeliverySerie::nextOpenSeries()->first();
    $order_building = $user->order_building()->first();

    // Means there's no step yet, let's go to the first one
    if ($order_building === NULL) {

      $order_building = new UserOrderBuilding;
      $order_building->user()->associate($user);
      $order_building->delivery_serie()->associate($next_series);

      // We will build the entire profile
      // All the other steps will only be updating (like that the user can go backward)
      
      $user_profile = new UserProfile;
      $user_profile->user()->associate($user);
      $user_profile->status = 'not-subscribed';
      $user_profile->priority = 'medium';

      $user_profile->save();

      // We can already build the contract id
      $user_profile->contract_id = strtoupper(str_random(1)) . rand(100,999) . $user->id . $user_profile->id;
      $user_profile->save();

      $order_building->profile()->associate($user_profile);

      $order_preference = new UserOrderPreference;
      $order_preference->user_profile()->associate($user_profile);
      $order_preference->gift = Session::get('isGift');
      $order_preference->save();

      $order_building->order_preference()->associate($order_preference);

      // Finally we set the current step
      $order_building->step = 'choose-box';
      $order_building->save();

    } else {

      $order_preference = $order_building->order_preference()->first();

      // We refresh the series in case it doesn't match anymore (only use this for statistics)
      $order_building->delivery_serie_id = $next_series->id;
      $order_building->save();

      // If the guy switches from a gift to a classic or anything like that
      // He will redo everything
      if ((is_bool(Session::get('isGift'))) && (Session::get('isGift') != $order_preference->gift)) {
        $order_preference->gift = Session::get('isGift');
        $order_preference->save();

        // Finally we set the current step
        $order_building->step = 'choose-box';
        $order_building->save();
      }
    }

    // Let's redirect depending on the step
    return '/order/' . $order_building->step;

  }

}