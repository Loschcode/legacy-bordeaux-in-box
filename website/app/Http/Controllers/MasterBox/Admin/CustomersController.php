<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator, Hash;
use App\Models\Customer;
use App\Models\Coordinate;


class CustomersController extends BaseController {

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
   * @return \Illuminate\View\View
   */
  public function getIndex()
  {

    $customers = Customer::orderBy('created_at', 'desc')->get();

    return view('masterbox.admin.customers.index')->with(compact(
      'customers'
      ));

  }

  /**
   * Display the emails from all customers
   * @return \Illuminate\View\View
   */
  public function getEmails($sort='')
  {

    switch ($sort) {

      case 'bought-a-box-but-stop':
        $emails = get_email_listing_from_customers_who_bought_a_box_but_stop();
        $title = 'Email des clients ayant déjà acheté une box mais qui n\'en ont plus actuellement';
      break;

      case 'never-bought-a-box':
        $emails = get_email_listing_from_customers_who_never_bought_a_box();
        $title = 'Emails des clients n\'ayant jamais achetés une box';
      break;

      case 'having-a-profile-subscribed':
        $emails = get_email_listing_from_customers_having_a_profile_subscribed();
        $title = 'Emails des clients ayant actuellement un abonnement à une box';
      break;

      default:
        $emails = get_email_listing_from_all_customers();
        $title = 'Emails de tout les clients';
      break;  

    }


    return view('masterbox.admin.customers.emails')->with(compact(
      'emails',
      'title'
      ));

  }


  /**
   * Focus on a user
   * @param  string $id Id of the user
   * @return \Illuminate\View\Viex
   */
  public function getFocus($id)
  {

    $customer = Customer::findOrFail($id);

    return view('masterbox.admin.customers.focus')->with(compact(
      'customer'
      ));

  }

  /**
   * Dislay the form to edit the user
   * @param  string $id Id of the user
   * @return \Illuminate\View\View
   */
  public function getEdit($id)
  {
    $customer = Customer::findOrFail($id);

    return view('masterbox.admin.customers.edit')->with(compact(
      'customer'
      ));
  }

  public function postEdit()
  {

		// New article rules
    $rules = [

    'customer_id' => 'required|integer',
    'email' => 'required',
    'password' => '',
    'phone' => 'required',

    'first_name' => 'required',
    'last_name' => 'required', 

    'address' => 'required',
    'address_detail' => '',
    'zip' => 'required',
    'city' => 'required'

    ];


    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

		// The form validation was good
    if ($validator->passes()) {

      if (!isset($fields['address_detail']))
        $fields['address_detail'] = '';
      
     $customer = Customer::findOrFail($fields['customer_id']);


     $customer->email = $fields['email'];

     if ( ! empty($fields['password'])) {

       $customer->password = Hash::make($fields['password']);

     }

     $customer->phone = $fields['phone'];

     $customer->first_name = $fields['first_name'];
     $customer->last_name = $fields['last_name'];

     $customer->coordinate_id = Coordinate::getMatchingOrGenerate($fields['address'], $fields['zip'], $fields['city'], $fields['address_detail'])->id;

     $customer->save();

			// If the user got profiles we will edit the next deliveries
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
              $billing->coordinate_id = Coordinate::getMatchingOrGenerate($customer->address, $customer->zip, $customer->city, $customer->address_detail)->id;

								// We save everything
              $billing->save();

            }

          }

        }

      }
    }

    return redirect()->action('MasterBox\Admin\CustomersController@getEdit', ['id' => $customer->id])
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