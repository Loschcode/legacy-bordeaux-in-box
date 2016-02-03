<?php namespace App\Http\Controllers\MasterBox\Customer;

use App\Http\Controllers\MasterBox\BaseController;

use Auth, Request, Validator;

use App\Models\Order;
use App\Models\DeliverySpot;
use App\Models\DeliverySerie;
use App\Models\Payment;
use App\Models\CustomerProfile;
use App\Models\Coordinate;

use App\Libraries\Payments;
use Hash, URL;

class ProfileController extends BaseController {

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
        $this->middleware('is.customer');
    }

	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'masterbox.layouts.master';

    /**
     * Index page
     */
    public function getIndex()
    {

    	$customer = Auth::guard('customer')->user();
    	$profiles = $customer->profiles()->orderBy('created_at', 'desc')->get();

    	// We get the destination (the last editable order destination)
    	$unlocked_orders = Order::where('customer_id', $customer->id)->where('locked', FALSE)->orderBy('created_at', 'desc')->get();

    	$destination = NULL;
    	$spot = NULL;

    	// We will look through all the unlocked orders
    	// To try to get the destination and spot if it exists
    	foreach ($unlocked_orders as $unlocked_order) {

    		if (($destination === NULL) && (!$unlocked_order->gift))
          $destination = $unlocked_order->destination()->first();

    		if ($spot === NULL)
          $spot = $unlocked_order->delivery_spot()->first();

    	}


    	if ($spot == NULL) $delivery_spots = [];
    	else $delivery_spots = DeliverySpot::where('active', TRUE)->orWhere('id', $spot->id)->get();
		  
      $active_menu = 'account';

  		return view('masterbox.customer.profile.index')->with(compact(
        'delivery_spots',
        'customer',
        'profiles',
        'destination',
        'spot',
        'active_menu'
      ));

    }

    // Check and download a bill
    public function getDownloadBill($bill_id)
    {

    	$customer = Auth::guard('customer')->user();
    	$payment = Payment::where('bill_id', $bill_id)->first();

    	if ($payment != NULL) {

    		// If it's not the user bill, we redirect him
    		if ($payment->customer()->first()->id != $customer->id) {

    			return redirect()->to('/profile');

    		}


    		return generate_pdf_bill($payment, TRUE);

    	}

		return redirect()->to('/profile');

    }

    public function getOrders()
    {
      
      $customer = Auth::guard('customer')->user();
      $profiles = $customer->profiles()->orderBy('created_at', 'desc')->get();
      $active_menu = 'orders';

      return view('masterbox.customer.profile.orders')->with(compact(
        'customer',
        'profiles',
        'active_menu'
      ));


    }

    // Get the orders details from one profile
    public function getOrder($id)
    {

    	$customer = Auth::guard('customer')->user();

    	// Small protection to be sure it's the correct user
    	if ($customer->profiles()->where('id', '=', $id)->first() == NULL) {
    		return redirect()->action('MasterBox\Customer\ProfileController@getIndex');
    	}

    	$profile = CustomerProfile::find($id);
    	$orders = $profile->orders()->get();
    	$payments = $profile->payments()->get();
      $payment_profile = $profile->payment_profile()->first();
      $active_menu = 'orders';

		  return view('masterbox.customer.profile.order')->with(compact(
        'customer',
        'profile',
        'payment_profile',
        'orders',
        'payments',
        'active_menu'
      ));


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
      'stripeToken' => 'required'

      ];

    $fields = Request::all();

    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we don't need password confirmation
     */
    if ($customer->hasProvider())
      unset($rules['old_password']);

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $profile = CustomerProfile::find($fields['profile_id']);

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

          session()->flash('error', $new_stripe_card[0]);
          return redirect()->back();

        }

        $new_stripe_card_last4 = Payments::getLast4FromCard($stripe_customer, $new_stripe_card);

        /**
         * Now we got the new card we will change the informations everywhere within the profile
         */
        
        // User Payment Profile
        $payment_profile->stripe_card = $new_stripe_card;
        $payment_profile->last4 = $new_stripe_card_last4;

        $payment_profile->save();

        session()->flash('message', "Votre carte a bien été mise à jour");
        return redirect()->back();

      }

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->to(URL::previous() . '#credit-card')
      ->withInput()
      ->withErrors($validator);

    }

  }

  public function postEditEmail()
  {

    $rules = [
      'email' => 'required|email|unique:customers,email,'. Auth::guard('customer')->user()->id,
      'old_password' => 'required|match_password'
    ];

    $fields = Request::all();

    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we don't need password confirmation
     */
    if ($customer->hasProvider())
      unset($rules['old_password']);

    $validator = Validator::make($fields, $rules);

    if ($validator->passes()) {
      
      if ($customer !== NULL) {

        $customer->email = $fields['email'];
        $customer->save();

      }

      session()->flash('message', 'Votre email à été mis à jour');
      
      return redirect()->back()
      ->withInput();

    } 

    // We return the same page with the error and saving the input datas
    return redirect()->to(URL::previous() . '#email-block')
    ->withInput()
    ->withErrors($validator, 'edit_email');

  }

  public function postEditPassword()
  {

    $rules = [
      'password' => 'required|min:5',
      'old_password' => 'required|match_password'
    ];


    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we can't edit password
     */
    if ($customer->hasProvider())
      return redirect()->back()
        ->withInput();

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    if ($validator->passes()) {
      
      $customer = Auth::guard('customer')->user();

      if ($customer !== NULL) {

        $customer->password = Hash::make($fields['password']);
        $customer->save();

      }

      session()->flash('message', 'Votre mot de passe à été mis à jour');
      
    return redirect()->back()
      ->withInput();

    } 

    // We return the same page with the error and saving the input datas
    return redirect()->to(URL::previous() . '#password-block')
    ->withInput()
    ->withErrors($validator, 'edit_password');
  }

  public function postEditBilling()
  {

    $rules = [

      'phone' => 'required',
      'first_name' => 'required',
      'last_name' => 'required', 
      'address' => 'required',
      'zip' => 'required',
      'city' => 'required',

      'old_password' => 'required|match_password'
    ];

    $fields = Request::all();

    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we don't need password confirmation
     */
    if ($customer->hasProvider())
      unset($rules['old_password']);

    $validator = Validator::make($fields, $rules);

    if ($validator->passes()) {
      
      if ($customer !== NULL) {

        $customer->first_name = $fields['first_name'];
        $customer->last_name = $fields['last_name'];
        $customer->phone = $fields['phone'];
        $customer->coordinate_id = Coordinate::getMatchingOrGenerate($fields['address'], $fields['zip'], $fields['city'])->id;

        $customer->save();

        // If the customer got profiles we will edit the next deliveries
        if ($customer->profiles()->first() != NULL) {

          $profiles = $customer->profiles()->get();
          
          foreach ($profiles as $profile) {

            if ($profile->orders()->first() != NULL) {

              // Only for editable orders
              $profile_orders = $profile->orders()->where('locked', FALSE)->get();

              foreach ($profile_orders as $profile_order) {

                if ($profile_order->billing()->first() != NULL) {

                  $billing = $profile_order->billing()->first();

                  $billing->first_name = $customer->first_name;
                  $billing->last_name = $customer->last_name;
                  $billing->coordinate_id = Coordinate::getMatchingOrGenerate($customer->address, $customer->zip, $customer->city)->id;

                  // We save everything
                  $billing->save();

                }

              }

            }

          }

        }

      }

      session()->flash('message', 'Vos informations de facturation ont été mises à jour');
      
      return redirect()->back()
      ->withInput();

    }

    // We return the same page with the error and saving the input datas
    return redirect()->to(URL::previous() . '#billing-block')
    ->withInput()
    ->withErrors($validator, 'edit_billing');

  }


  public function postEditDestination()
  {

    $rules = [

      'destination_first_name' => 'required',
      'destination_last_name' => 'required', 

      'destination_address' => 'required',
      'destination_zip' => 'required',
      'destination_city' => 'required',

      'old_password' => 'required|match_password'
    ];

    $fields = Request::all();

    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we don't need password confirmation
     */
    if ($customer->hasProvider())
      unset($rules['old_password']);

    $validator = Validator::make($fields, $rules);

    if ($validator->passes()) {

      if ($customer !== NULL) {

        // If the customer got profiles we will edit the next deliveries
        if ($customer->profiles()->first() != NULL) {

          $profiles = $customer->profiles()->get();
          
          foreach ($profiles as $profile) {

            if ($profile->orders()->first() != NULL) {

              // Only for editable orders
              $profile_orders = $profile->orders()->where('locked', FALSE)->notGift()->get();

              foreach ($profile_orders as $profile_order) {
                
                // Not a take away, it means we will update the destination
                if ($profile_order->take_away == FALSE) {

                  $destination = $profile_order->destination()->first();

                  /**
                   * If the destination for some reason doesn't exist, we generate one
                   * NOTE : Shouldn't happen but it's all about data consistency
                   */
                  if ($destination === NULL) {
                    $destination = new OrderDestination;
                    $destination->order_id = $profile_order->id;
                  }

                  $destination->first_name = $fields['destination_first_name'];
                  $destination->last_name = $fields['destination_last_name'];
                  $destination->coordinate_id = Coordinate::getMatchingOrGenerate($fields['destination_address'], $fields['destination_zip'], $fields['destination_city'])->id;


                  // We save everything
                  $destination->save();

                }

              }

            }

          }

        }

      }

      session()->flash('message', 'Vos informations de destination ont été mises à jour');
      
      return redirect()->back()
      ->withInput();

    }

    // We return the same page with the error and saving the input datas
    return redirect()->to(URL::previous() . '#destination-block')
    ->withInput()
    ->withErrors($validator, 'edit_destination');

  }

  public function postEditSpot()
  {

    $rules = [

      'chosen_spot' => 'integer',

      'old_password' => 'required|match_password'
    ];

    $fields = Request::all();

    $customer = Auth::guard('customer')->user();

    /**
     * If it has a provider such as Facebook, we don't need password confirmation
     */
    if ($customer->hasProvider())
      unset($rules['old_password']);

    $validator = Validator::make($fields, $rules);

    if ($validator->passes()) {
      
      if ($customer !== NULL) {

        // If the customer got profiles we will edit the next deliveries
        if ($customer->profiles()->first() != NULL) {

          $profiles = $customer->profiles()->get();
          
          foreach ($profiles as $profile) {

            if ($profile->orders()->first() != NULL) {

              // Only for editable orders
              $profile_orders = $profile->orders()->where('locked', FALSE)->get();

              foreach ($profile_orders as $profile_order) {
                
                $spot = DeliverySpot::find($fields['chosen_spot']);
                
                if ($profile_order->take_away == TRUE) {

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

      session()->flash('message', 'Votre point relais à été mis à jour');
      
      return redirect()->back()
      ->withInput();

    }

    // We return the same page with the error and saving the input datas
    return redirect()->to(URL::previous() . '#spot-block')
    ->withInput()
    ->withErrors($validator, 'edit_spot');

  }

  /**
   * Display form contact
   * @return Illuminate\View\View
   */
  public function getContact()
  {

    $customer = Auth::guard('customer')->user();
    $active_menu = 'contact';

    return view('masterbox.customer.profile.contact')->with(compact(
      'customer',
      'active_menu'
    ));

  }

}
