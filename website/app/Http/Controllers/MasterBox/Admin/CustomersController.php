<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Request, Validator;
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
     * @return void
     */
	public function getIndex()
	{

		$customers = Customer::orderBy('created_at', 'desc')->get();
    $email_listing_from_all_customers = get_email_listing_from_all_customers();

		return view('masterbox.admin.customers.index')->with(compact(
      'customers',
      'email_listing_from_all_customers'
    ));

	}

  /**
   * Return json to populate the table of the view 
   * getIndex()
   *     
   * @return json
   */
  public function getJsonCustomers()
  {

    $draw = request()->input('draw');
    $start = request()->input('start');
    $search = request()->input('search')['value'];
    $length = request()->input('length');
    $order_column = request()->input('order')[0]['column'];
    $order_sort = request()->input('order')[0]['dir'];

    $columns = [
      '1' => 'id',
      '2' => 'first_name',
      '3' => 'email',
      '4' => 'phone'
    ];

    // Translate the order column
    $order_column = $columns[$order_column];

    $total_results = Customer::count();

    if (empty($search)) {

      $customers = Customer::with('profiles')->orderBy($order_column, $order_sort)->skip($start)->take($length)->get();
      $total_results_after_filtered = $total_results;

    } else {

    //
    //\DB::enableQueryLog();

      $query = Customer::research($search);


      $total_results_after_filtered = $query->count();
      $customers = $query->orderBy($order_column, $order_sort)->skip($start)->take($length)->get();


    }

         // dd(\DB::getQueryLog());
      
    
    return response()->json([
      'data' => $customers,
      'recordsTotal' => $total_results,
      'recordsFiltered' => $total_results_after_filtered,
      'draw' => (int) $draw
    ]);
  }


    /**
     * Focus on a user
     * @return void
     */
	public function getFocus($id)
	{

		$customer = Customer::findOrFail($id);

		return view('masterbox.admin.customers.focus')->with(compact(
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
			'zip' => 'required',
			'city' => 'required'

			];


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$customer = Customer::findOrFail($fields['customer_id']);


      $customer->email = $fields['email'];

      if ( ! empty($fields['password'])) {

       $customer->password = Hash::make($fields['password']);

      }

      $customer->phone = $fields['phone'];

      $customer->first_name = $fields['first_name'];
      $customer->last_name = $fields['last_name'];

      $customer->coordinate_id = Coordinate::getMatchingOrGenerate($fields['address'], $fields['zip'], $fields['city'])->id;

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
                $billing->zip = $customer->zip;
                $billing->city = $customer->city;
                $billing->address = $customer->address;

								// We save everything
                $billing->save();

              }

            }

          }

        }
      }

			return redirect()->action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id])
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