<?php namespace App\Http\Controllers\MasterBox\Customer;

use App\Http\Controllers\MasterBox\BaseController;
use Session, Auth, Request, Redirect, URL, Validator, Config, Log;

use App\Models\Box;
use App\Models\Customer;
use App\Models\DeliverySerie;
use App\Models\DeliveryPrice;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentProfile;
use App\Models\CustomerOrderBuilding;
use App\Models\CustomerOrderPreference;
use App\Models\DeliverySetting;
use App\Models\DeliverySpot;
use App\Models\Order;
use App\Models\OrderBilling;
use App\Models\OrderDestination;
use App\Models\BoxQuestion;
use App\Models\BoxQuestionCustomerAnswer;
use App\Models\Coordinate;

use App\Libraries\Payments;

class PurchaseController extends BaseController {

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
    $this->middleware('is.customer', array('except' => ['getClassic', 'getGift', 'getChooseFrequency', 'postChooseFrequency']));
    $this->middleware('has.unpaid.order.building', array('except' => ['getIndex', 'getClassic', 'getGift', 'getChooseFrequency', 'postChooseFrequency', 'getBoxForm', 'postBoxForm', 'getConfirmed']));

    $this->middleware('has.paid.order.building', array('only' => ['getBoxForm', 'postBoxForm']));
    $this->middleware('below.serie.counter', array('except' => ['postPayment']));

    $this->middleware('is.not.take.away', array('only' => ['getChooseSpot', 'postChooseSpot']));
    $this->middleware('is.not.regional', array('only' => ['getChooseSpot', 'postChooseSpot']));

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

    session()->put('isGift', FALSE);

    if (Auth::guard('customer')->guest())
      return redirect()->action('MasterBox\Customer\PurchaseController@getChooseFrequency');

    $redirect = $this->guessStepFromUser();
    return redirect($redirect);

  }

  /**
   * Order gift way (general access, can be unknown user)
   */
  public function getGift()
  {

    session()->put('isGift', TRUE);

    if (Auth::guard('customer')->guest())
      return redirect()->action('MasterBox\Customer\PurchaseController@getChooseFrequency');

    $redirect = $this->guessStepFromUser();
    return redirect($redirect);

  }

  /**
   * Choose frequency page
   */
  public function getChooseFrequency()
  {

    if (Auth::guard('customer')->guest()) {
      
      /**
       * We prepare the redirection
       */
      session()->put('after-login-redirection', Request::url());
    
      $customer = NULL;
      $order_building = NULL;
      $order_preference = NULL;

      if (session()->get('isGift') === TRUE)
        $is_gift = TRUE;
      else
        $is_gift = FALSE;

    } else {

      /**
       * If the guy just subscribed and has this session defined
       * We will make the whole special process
       */
      if (session()->get('postChooseFrequency')) {

        $redirect = $this->guessStepFromUser();
        return redirect($redirect);

      }

      $customer = Auth::guard('customer')->user();
      $order_building = $customer->order_buildings()->getCurrent()->first();

      if ($order_building === NULL) {

        // Then we redirect
        $redirect = $this->guessStepFromUser();
        return redirect($redirect);

      }

      $order_preference = $order_building->order_preference()->first();

      if ($order_preference->gift)
        $is_gift = TRUE;
      else
        $is_gift = FALSE;

    }

    $next_series = DeliverySerie::nextOpenSeries();
    $delivery_prices = DeliveryPrice::where('gift', $is_gift)->orderBy('unity_price', 'asc')->get();
    
    return view('masterbox.customer.order.choose_frequency')->with(
                  compact(
                    'next_series',
                    'delivery_prices', 
                    'order_preference',
                    'is_gift'
                  )
                );

  }

  /**
   * Frequency was chosen
   */
  public function postChooseFrequency()
  {

    $rules = [

      'delivery_price' => 'required|integer',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      if (Auth::guard('customer')->guest())  {

        /**
         * If he's not logged in we propose him to do so and we save what he chose
         */
        session()->put('postChooseFrequency', $fields['delivery_price']);
        return redirect()->action('MasterBox\Connect\CustomerController@getSubscribe');

      }

      return redirect()->to($this->save_choose_frequency($fields['delivery_price']));

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  private function save_choose_frequency($delivery_price_id) {

    $delivery_price = DeliveryPrice::where('id', '=', $delivery_price_id)->first();

    if ($delivery_price === NULL)
      return redirect()->action('MasterBox\Customer\PurchaseController@getChooseFrequency');

    $customer = Auth::guard('customer')->user();
    $order_building = $customer->order_buildings()->getCurrent()->first();
    $profile = $order_building->profile()->first();

    // We change the profile status (the guy chose something)
    $profile->status = 'in-progress';
    $profile->save();

    $order_preference = $order_building->order_preference()->first();

    // We duplicate the delivery price (because it could change with the time)
    $order_preference->frequency = $delivery_price->frequency;
    $order_preference->unity_price = $delivery_price->unity_price;
    $order_preference->save();

    // Let's go to the next step
    $order_building->step = 'billing-address';
    $order_building->save();

    // We force forget this now that we used the process (might unset nothing in many cases)
    session()->forget('postChooseFrequency');

    // Then we redirect
    $redirect = $this->guessStepFromUser();
    return $redirect;

  }

  public function getBillingAddress()
  {

    $customer = Auth::guard('customer')->user();

    $order_building = $customer->order_buildings()->getCurrent()->first();

    /**
     * Order building Addresses auto-filling
     * If we don't have the data we will try our best to get it
     */
    
    //$order_building = $this->fill_empty_order_building_destination($customer, $order_building);
    if (!empty($order_building->destination_city)) {

      $destination = new \stdClass();
      $destination->city = $order_building->destination_city;
      $destination->address = $order_building->destination_address;
      $destination->address_detail = $order_building->destination_address_detail;
      $destination->zip = $order_building->destination_zip;
      $destination->first_name = $order_building->destination_first_name;
      $destination->last_name = $order_building->destination_last_name;

    } else {

      $destination = new \stdClass();
      $destination->city = $customer->city;
      $destination->address = $customer->address;
      $destination->address_detail = $customer->address_detail;
      $destination->zip = $customer->zip;
      $destination->first_name = $customer->first_name;
      $destination->last_name = $customer->last_name;

    }

    //dd($order_building->city);

    $order_preference = $order_building->order_preference()->first();

    return view('masterbox.customer.order.billing_address')->with(compact('customer', 'order_building', 'order_preference', 'destination'));

  }

  public function postBillingAddress()
  {

    session()->put('flag-billing-address', true);

    $rules = [

      'billing_first_name' => 'required',
      'billing_last_name' => 'required',
      'billing_city' => 'required',
      'billing_zip' => 'required',
      'billing_address' => 'required',
      'billing_address_detail' => '',

      'destination_first_name' => 'required',
      'destination_last_name' => 'required',
      'destination_city' => 'required',
      'destination_zip' => 'required',
      'destination_address' => 'required',
      'destination_address_detail' => '',

      ];

    // We auto trim everything
    Request::merge(array_map('trim', Request::all()));

    $fields = Request::all();

    /**
     * It's mandatory if it exists effectively
     */
    if (isset($fields['customer_phone']))
      $rules['customer_phone'] = 'required';

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      if (!isset($fields['billing_address_detail']))
        $fields['billing_address_detail'] = '';

      if (!isset($fields['destination_address_detail']))
        $fields['destination_address_detail'] = '';

      $customer = Auth::guard('customer')->user();
      
      if (isset($fields['customer_phone'])) {

        $customer->phone = $fields['customer_phone'];
        $customer->save();

      }

      $order_building = $customer->order_buildings()->getCurrent()->first();
      $order_preference = $order_building->order_preference()->first();
      
      // If the user hasn't billing address yet
      if ((!$customer->hasBillingAddress()) || ($customer->profiles()->count() <= 1)) {

        // We refresh the billing informations
        $customer->coordinate_id = Coordinate::getMatchingOrGenerate($fields['billing_address'], $fields['billing_zip'], $fields['billing_city'], $fields['billing_address_detail'])->id;
        $customer->save();

      }

      // We refresh the destination informations
      $order_building->destination_first_name = $fields['destination_first_name'];
      $order_building->destination_last_name = $fields['destination_last_name'];
      $order_building->destination_coordinate_id = Coordinate::getMatchingOrGenerate($fields['destination_address'], $fields['destination_zip'], $fields['destination_city'], $fields['destination_address_detail'])->id;

      // Let's go to the next step
      $order_building->step = 'delivery-mode';
      $order_building->save();

      // Then we redirect
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getDeliveryMode()
  {

    $customer = Auth::guard('customer')->user();
    $order_building = $customer->order_buildings()->getCurrent()->first();
    $order_preference = $order_building->order_preference()->first();

    // Back
    view()->share('order_preference', $order_preference);

    if (!$order_building->isRegionalAddress()) {

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

    return view('masterbox.customer.order.delivery_mode');

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

      $customer = Auth::guard('customer')->user();
      $order_building = $customer->order_buildings()->getCurrent()->first();
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
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function getChooseSpot()
  {

    $customer = Auth::guard('customer')->user();
    $order_building = $customer->order_buildings()->getCurrent()->first();
    $order_preference = $order_building->order_preference()->first();
    $coordinate = $order_building->destination_coordinate()->first();

    // In case the user come back
    $chosen_delivery_spot = $order_preference->delivery_spot()->first();

    if ($chosen_delivery_spot === NULL) $chosen_delivery_spot = 0;
    else $chosen_delivery_spot = $chosen_delivery_spot->id;

    /**
     * 10Km max
     */
    $delivery_spots = DeliverySpot::where('active', TRUE)->orderByDistanceFrom($coordinate, 10000)->get();

    return view('masterbox.customer.order.choose_spot')->with(compact(

      'chosen_delivery_spot',
      'delivery_spots',
      'order_building',
      'order_preference'

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

      $customer = Auth::guard('customer')->user();
      $order_building = $customer->order_buildings()->getCurrent()->first();
      $order_preference = $order_building->order_preference()->first();

      $delivery_spot = DeliverySpot::find($fields['chosen_spot']);
      if ($delivery_spot === NULL) return redirect()->back();

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
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);
    }


  }

  public function getPayment()
  {
    $customer = Auth::guard('customer')->user();
    $order_building = $customer->order_buildings()->getCurrent()->first();
    $profile = $order_building->profile()->first();
    $order_preference = $order_building->order_preference()->first();
    $delivery_spot = $order_preference->delivery_spot()->first(); // May be NULL

    /**
     * Small protection to avoid steps bugs
     */
    if ($order_building->step !== 'payment') {
      $redirect = $this->guessStepFromUser();
      return redirect($redirect);
    }

    return view('masterbox.customer.order.payment')->with(compact(
      'customer',
      'order_building',
      'profile',
      'order_preference',
      'delivery_spot'
    ));

  }

  public function postApplyCoupon()
  {

    $rules = [

      'coupon' => 'required',

      ];

    $fields = Request::all();
    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $customer = Auth::guard('customer')->user();
      $order_building = $customer->order_buildings()->getCurrent()->first();
      $profile = $order_building->profile()->first();
      $order_preference = $order_building->order_preference()->first();

      $coupon = $fields['coupon'];

      /*if ($coupon === 'FIN-MARS-2016') {

        if ($order_preference->frequency == 1) {
        
          $order_preference->unity_price = 21.90;
          $order_building->delivery_serie_id = 37; // RAW
          $order_building->end_series_special_offer = 37; // RAW

          $order_building->save();
          $order_preference->save();

          session()->flash('message', "La réduction ainsi que l'inscription aux fin de séries de Mars 2016 a bie été prise en compte !");

        }

      }*/

      if ($coupon === 'FIN-AVRIL-2016') {

        if ($order_preference->frequency == 1) {
        
          $order_preference->unity_price = 21.90;
          $order_building->delivery_serie_id = 38; // RAW

          $order_building->save();
          $order_preference->save();

          session()->flash('message', "La réduction ainsi que l'inscription aux fin de séries de Avril 2016 a bie été prise en compte !");

        } else {

          session()->flash('error', "La réduction ainsi que l'inscription aux fin de séries de Avril 2016 ne peut pas être effectuée sur un abonnement sans engagement.");

        }

      } else {

          session()->flash('error', "Coupon non valide.");

      }

    }

    return redirect()->back();

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

      $customer = Auth::guard('customer')->user();
      $order_building = $customer->order_buildings()->getCurrent()->first();
      $profile = $order_building->profile()->first();
      $order_preference = $order_building->order_preference()->first();

      /**
       * Small protection to avoid steps bugs
       */
      if ($order_building->step !== 'payment') {
        $redirect = $this->guessStepFromUser();
        return redirect($redirect);
      }

      /**
       * Stripe process system
       */

      // We make a new payment profile
      $payment_profile = new CustomerPaymentProfile;
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
      $stripe_customer = Payments::makeCustomer($stripe_token, $customer, $profile, "Client de la box principale ".$customer->getFullName(), $profile->contract_id);

      if (is_array($stripe_customer)) {

        return redirect()->back()->withErrors([
          "stripeToken" => $stripe_customer
          ]);
      }

      // We change the guy to "subscribed" because we made a new customer right now
      $profile->status = 'subscribed';

      // Then we come back to our payment process
      $profile->stripe_customer = $stripe_customer;

      $payment_profile->stripe_customer = $stripe_customer;

      $stripe_card = Payments::retrieveLastCard($stripe_customer);
      $payment_profile->stripe_card = $stripe_card;
      $payment_profile->last4 = Payments::getLast4FromCard($stripe_customer, $stripe_card);

      $profile->save();
      $payment_profile->save();

      // We retrieve the stripe customer data (from old or new customer)
      $stripe_customer = $profile->stripe_customer;

      /**
       * We build the orders
       */
      $order_preference = $order_building->order_preference()->first();

      // We look how many boxes we will send
      // The infinite plan is special, the num orders is artificial (we will add them up progressively)
      if ($order_preference->frequency == 0) $num_orders = Config::get('bdxnbx.infinite_plan_orders');
      else $num_orders = $order_preference->frequency;

      /**
       * If it's a special offer we might have an artificial number (like FIN-MARS-2016)
       */
      if ($order_building->end_series_special_offer !== NULL)
        $delivery_series = DeliverySerie::where('id', '=', $order_building->end_series_special_offer)->get();
      else
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
        $unity_price = $order_preference->unity_price / $order_preference->frequency;
        $delivery_fees = $order_preference->delivery_fees / $order_preference->frequency;
      
      } else { 

        $unity_and_fees_price = $order_preference->totalPricePerMonth();
        $unity_price = $order_preference->unity_price;
        $delivery_fees = $order_preference->delivery_fees;
        
      }

      $num = 0;

      while ($num < $num_orders) {

        // Matching series
        if (!isset($delivery_series[$num]))  {

          Log::info("ERROR : no enough delivery series to order (checkout PurchaseController line ~700");
          $profile->orders()->delete();

          return redirect()->back()->withErrors([
            "stripeToken" => ["Une erreur liée aux séries de boxes s'est produite, veuillez réessayer ultérieurement ou contacter notre support si le problème persiste."]
          ]);

        }

        $delivery_serie = $delivery_series[$num];

        /**
         * Duplicate protection
         * If someone blows up the system we absolutely must avoid double orders
         * which are the same. So we avoid the creation if needed
         */
        $order_already_exists = Order::where('customer_profile_id', '=', $profile->id)->where('delivery_serie_id', '=', $delivery_serie->id)->first();

        if ($order_already_exists === NULL) {

          // We make the order
          $order = new Order;
          $order->customer()->associate($customer);
          $order->customer_profile()->associate($profile);
          $order->delivery_serie()->associate($delivery_serie);

          // We don't lock the new orders
          $order->locked = FALSE;

          // If there's a spot (take away only)
          if ($delivery_spot !== NULL) $order->delivery_spot()->associate($delivery_spot);

          $order->status = 'scheduled';
          $order->gift = $order_preference->gift;
          $order->take_away = $order_preference->take_away;
          $order->unity_and_fees_price = $unity_and_fees_price;

          $order->unity_price = $unity_price;
          $order->delivery_fees = $delivery_fees;

          $order->save();

          // We make the order billing
          $order_billing = new OrderBilling;
          $order_billing->order()->associate($order);
          $order_billing->first_name = $customer->first_name;
          $order_billing->last_name = $customer->last_name;
          $order_billing->coordinate_id = Coordinate::getMatchingOrGenerate($customer->address, $customer->zip, $customer->city)->id;

          $order_billing->save();

          // We make the order destination
          $order_destination = new OrderDestination;
          $order_destination->order()->associate($order);
          $order_destination->first_name = $order_building->destination_first_name;
          $order_destination->last_name = $order_building->destination_last_name;
          $order_destination->coordinate_id = Coordinate::getMatchingOrGenerate($order_building->destination_address, $order_building->destination_zip, $order_building->destination_city)->id;
          
          $order_destination->save();

          /**
           * Finally we generate the company billing of the order
           */
          generate_new_company_billing_from_order($order, TRUE);

        }

        $num++;

      }

      /**
       * We finally invoice the user (no feedback here, we have InvoicesController to handle it)
       */
      if (($order_preference->gift) || ($order_preference->frequency == 1)) {
        
        // If it's a gift it's a direct invoice
        $feedback = Payments::makeCharge($stripe_customer, $customer, $profile, $order_preference->totalPricePerMonth());

        if ($feedback !== TRUE) {
          // Not sure about it, but to be clean we might delete the orders we just built
          $profile->orders()->delete();

          return redirect()->back()->withErrors([
            "stripeToken" => $feedback
          ]);

        }

      } else {

        // If it's not a gift, even for 1 month subscription we will subscribe and directly cancel after the payment
        $plan_name = guess_stripe_plan_from_order_preference($order_preference);

        $order_preference->stripe_plan = $plan_name;
        $order_preference->save();

        $plan_price = $order_preference->totalPricePerMonth();
        $feedback = Payments::makeSubscription($stripe_customer, $customer, $profile, $plan_name, $plan_price);

        if (is_array($feedback)) {

          // Not sure about it, but to be clean we might delete the orders we just built
          $profile->orders()->delete();

          return redirect()->back()->withErrors([
          "stripeToken" => $feedback
          ]);

        } else {

          $payment_profile->stripe_subscription = $feedback;
          $payment_profile->stripe_plan = $plan_name;

          $payment_profile->save();
        }

      }

      // We go to the next step (after the payment)
      $order_building->paid_at = date('Y-m-d H:i:s');
      $order_building->save();

      // Then we redirect to the optional form
      return redirect()->action("MasterBox\Customer\PurchaseController@getBoxForm");

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);
    }
  }


  /**
   * Form depending on the box page
   */
  public function getBoxForm()
  {

    $customer = Auth::guard('customer')->user();

    $order_building = $customer->order_buildings()->getLastPaid()->first();

    // So we never paid
    if ($order_building === NULL) {

      $redirect = $this->guessStepFromUser();
      return redirect($redirect);

    }

    $order_preference = $order_building->order_preference()->first();
    $profile = $order_building->profile()->first();

    if ($order_preference->isGift())
      $questions = $profile->unansweredQuestions()->with('answers')->orderBy('position', 'asc')->get();
    else
      $questions = $profile->unansweredQuestions()->notOnlyGift()->with('answers')->orderBy('position', 'asc')->get();
    
    // If no more questions, redirect.
    if ($questions->count() == 0) {
      return redirect()->action('MasterBox\Customer\PurchaseController@getConfirmed');
    }


    // Back case
    //$answers = $profile->answers();
    //view()->share('answers', $answers);

    return view('masterbox.customer.order.box_form')->with(compact(
      'profile', 
      'box', 
      'questions', 
      'order_preference'
    ));

  }

  public function getConfirmed()
  {

    $customer = Auth::guard('customer')->user();
    $order_building = $customer->order_buildings()->getLastPaid()->first();

    // We remove the last order building as it's useless to keep it now
    if ($order_building !== NULL)
      $order_building->delete();

    return view('masterbox.customer.order.confirmed');

  }

  private function guessStepFromUser()
  {

    /**
     * ORDER :
     * - Choose frequency
     * - Billing address and details
     * - Choose delivery mode (can be skipped if outside allowed area)
     * - Fill payment
     */
    
    $customer = Auth::guard('customer')->user();
    $next_series = DeliverySerie::nextOpenSeries()->first();
    $order_building = $customer->order_buildings()->getCurrent()->first();

    // Means there's no step yet, let's go to the first one
    if ($order_building === NULL) {

      $order_building = new CustomerOrderBuilding;
      $order_building->customer()->associate($customer);
      $order_building->delivery_serie()->associate($next_series);

      // We will build the entire profile
      // All the other steps will only be updating (like that the user can go backward)
      
      $customer_profile = new CustomerProfile;
      $customer_profile->customer()->associate($customer);
      $customer_profile->status = 'not-subscribed';
      $customer_profile->priority = 'medium';

      $customer_profile->save();

      // We can already build the contract id
      $customer_profile->contract_id = generate_contract_id('MBX', $customer);
      $customer_profile->save();

      $order_building->profile()->associate($customer_profile);

      $order_preference = $this->generate_new_order_preference($customer_profile, $order_building);

      // Finally we set the current step
      $order_building->step = 'choose-frequency';
      $order_building->save();

    } else {

      $order_preference = $order_building->order_preference()->first();

      if ($order_preference === NULL)
        $order_preference = $this->generate_new_order_preference($customer_profile, $order_building);

      // We refresh the series in case it doesn't match anymore (only use this for statistics)
      $order_building->delivery_serie_id = $next_series->id;
      $order_building->save();

      // If the guy switches from a gift to a classic or anything like that
      // He will redo everything
      if ((is_bool(session()->get('isGift'))) && (session()->get('isGift') != $order_preference->gift)) {
        
        $order_preference->gift = session()->get('isGift');
        $order_preference->save();

        // Finally we set the current step
        $order_building->step = 'choose-frequency';
        $order_building->save();

      }
    }

    if (session()->get('postChooseFrequency'))
      return $this->save_choose_frequency(session()->get('postChooseFrequency'));

    /**
     * Dynamic URL from step
     */
    $methods_from_step = [

      'choose-frequency' => 'getChooseFrequency',
      'delivery-mode' => 'getDeliveryMode',
      'choose-spot' => 'getChooseSpot',
      'billing-address' => 'getBillingAddress',
      'payment' => 'getPayment',

    ];

    /**
     * Small protection, if it doesn't exist we remove the building itself
     */
    if (!isset($methods_from_step[$order_building->step])) {

      $order_building->delete();
      return action("MasterBox\Customer\PurchaseController@getIndex");


    }

    // Let's redirect depending on the step
    return action("MasterBox\Customer\PurchaseController@".$methods_from_step[$order_building->step]);

  }

  private function generate_new_order_preference($customer_profile, $order_building) {

    $order_preference = new CustomerOrderPreference;
    $order_preference->customer_profile()->associate($customer_profile);

    if (session()->get('isGift') === NULL)
      session()->put('isGift', FALSE);

    $order_preference->gift = session()->get('isGift');
    $order_preference->save();

    $order_building->order_preference()->associate($order_preference);

    return $order_preference;

  }

}