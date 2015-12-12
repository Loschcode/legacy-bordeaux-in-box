<?php namespace App\Http\Controllers;

class AdminOrdersController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Orders Controller
	|--------------------------------------------------------------------------
	|
	| Manage the orders
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

		// Orders part
		$next_series = DeliverySerie::nextOpenSeries();

		//$locked_orders = Order::where('locked', TRUE)->whereNull('date_sent')->orderBy('delivery_serie_id', 'asc')->orderBy('created_at', 'asc')->get();

		$locked_orders = Order::LockedOrders()->where('status', '=', 'packing')->get();
		$packed_orders = Order::LockedAndPackedOrders()->get();

		$problem_orders = Order::where('status', 'problem')->orderBy('updated_at', 'asc')->get();

		View::share('locked_orders', $locked_orders);
		View::share('packed_orders', $packed_orders);
		View::share('problem_orders', $problem_orders);

		$this->layout->content = View::make('admin.orders.index');

	}

	/**
	 * Order has a problem
	 */
	public function getConfirmProblem($id)
	{

		$order = Order::find($id);

		if ($order !== NULL)
		{

			$order->status = 'problem';
			$order->save();

			Session::flash('message', "La commande est listée dans les commandes à problème");
			return Redirect::back();

		}

	}

	/**
	 * Order ready to be sent
	 */
	public function getConfirmReady($id)
	{

		$order = Order::find($id);

		if ($order !== NULL)
		{

			$order->date_completed = date('Y-m-d');
			$order->date_sent = NULL;
			$order->status = 'ready';
			$order->save();

			Session::flash('message', "La commande est prête à l'envoi");
			return Redirect::back();

		}

	}

	/**
	 * Order was sent
	 */
	public function getConfirmSent($id)
	{

		$order = Order::find($id);

		if ($order !== NULL)
		{

			$this->orderWasSent($order);

			Session::flash('message', "La commande a été envoyée");
			return Redirect::back();

		}

	}


	/**
	 * Order was sent
	 */
	public function getConfirmCancel($id)
	{

		$order = Order::find($id);

		if ($order !== NULL)
		{

			$order->status = 'canceled';
			$order->save();

			Session::flash('message', "La commande a été annulée");
			return Redirect::back();

		}

	}

	/**
	 * We remove the spot
	 */
	public function getDelete($id)
	{

		$order = Order::find($id);

		if ($order !== NULL)
		{

			$order->delete();

			Session::flash('message', "La commande a bien été archivée");
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
			return Redirect::back();


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
			$delivery_spot->active = TRUE;

			$delivery_spot->save();

			return Redirect::to('/admin/spots')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return Redirect::back()
			->withInput()
			->withErrors($validator);

		}


	}

  public function getReadySpot($id)
  {
    $locked_orders = Order::LockedOrders()->notCanceledOrders()->where('delivery_spot_id', $id)->where('take_away', true)->get();


    foreach ($locked_orders as $order) {

      $order->date_completed = date('Y-m-d');
      $order->date_sent = NULL;
      $order->status = 'ready';
      $order->save();

    }

    return Redirect::to('/easygo/index');
  }

  public function getReadyNoTakeAway()
  {
    $locked_orders = Order::LockedOrders()->notCanceledOrders()->where('take_away', false)->get();


    foreach ($locked_orders as $order) {

      $order->date_completed = date('Y-m-d');
      $order->date_sent = NULL;
      $order->status = 'ready';
      $order->save();

    }

    return Redirect::to('/easygo/index');
  }


	public function getEverythingIsReady()
	{

		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		foreach ($locked_orders as $order) {

			$order->date_completed = date('Y-m-d');
			$order->date_sent = NULL;
			$order->status = 'ready';
			$order->save();

		}

		return Redirect::back();

	}

	public function getEverythingHasBeenSent()
	{

		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		foreach ($locked_orders as $order) {

			$this->orderWasSent($order);

		}

		return Redirect::back();

	}

	public function getEmailLockedOrders()
	{

		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		View::share('locked_orders', $locked_orders);

		$this->layout->content = View::make('admin.orders.email_locked_orders');

	}

	/**
	 * We make a CSV out of the current order to process
	 * @return void
	 */
	public function getDownloadCsvLockedOrders()
	{

		$file_name = "orders-locked-not-packed".time().".csv";
		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		return generate_csv_order($file_name, $locked_orders);

	}

	/**
	 * We make a CSV out of the current order to process
	 * @return void
	 */
	public function getDownloadCsvReadyOrders()
	{

		$file_name = "orders-ready-".time().".csv";
		$packed_orders = Order::LockedAndPackedOrders()->notCanceledOrders()->get();

		return generate_csv_order($file_name, $packed_orders);

	}

	private function orderWasSent($order)
	{

		$order->date_sent = date('Y-m-d');
		$order->status = 'delivered';
		$order->save();

		/**
		 * We set the subscription to `expired` if the order is linked with a profile
		 * And if there's no other order to send.
		 * We didn't do it to the invoice because this action is made when it's sent for real.
		 */
		$profile = $order->user_profile()->first();

		if ($profile != NULL) {

			// If there's no other date to fill for the orders (or if there's no order at all)
			if ($profile->orders()->whereNull('date_sent')->count() <= 0) {

				$profile->status = 'expired';
				$profile->save();

		    // We send an email to get the customer back
		    $profile->sendExpirationEmail(TRUE);

			}

		}

	}

}
