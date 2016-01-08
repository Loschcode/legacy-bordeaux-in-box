<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\DeliverySerie;
use App\Models\Order;
use App\Models\DeliverySpot;

class OrdersController extends BaseController {

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
      $this->middleware('is.admin');

    }


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

		return view('master-box.admin.orders.index')->with(compact(
      'locked_orders',
      'packed_orders',
      'problem_orders'
    ));

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

			session()->flash('message', "La commande est listée dans les commandes à problème");
			return redirect()->back();

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

			session()->flash('message', "La commande est prête à l'envoi");
			return redirect()->back();

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

			session()->flash('message', "La commande a été envoyée");
			return redirect()->back();

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

			session()->flash('message', "La commande a été annulée");
			return redirect()->back();

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

			session()->flash('message', "La commande a bien été archivée");
			return redirect()->back();


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

			session()->flash('message', "Le point relais a été désactivé");
			return redirect()->back();


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

			session()->flash('message', "Le point relais a été activé");
			return redirect()->back();


		}

	}


	/**
	 * We a edit an spot
	 */
	public function getEdit($id)
	{

		$spot = DeliverySpot::find($id);

		if ($spot !== NULL) {

			return view('master-box.admin.spots.edit')->with(compact(
        'spot'
      ));

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


		$fields = Request::all();

		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$delivery_spot = DeliverySpot::find($fields['delivery_spot_id']);

			if ($delivery_spot !== NULL) {

				$delivery_spot->name = $fields['name'];
				$delivery_spot->zip = $fields['zip'];
				$delivery_spot->city = $fields['city'];
				$delivery_spot->address = $fields['address'];

				$delivery_spot->save();

			}

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

		$this->layout->content = view()->make('admin.spots.new');

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


		$fields = Request::all();

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

			return redirect()->to('/admin/spots')
			->withInput();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->back()
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

    return redirect()->to('/easygo/index');
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

    return redirect()->to('/easygo/index');
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

		return redirect()->back();

	}

	public function getEverythingHasBeenSent()
	{

		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		foreach ($locked_orders as $order) {

			$this->orderWasSent($order);

		}

		return redirect()->back();

	}

	public function getEmailLockedOrders()
	{

		$locked_orders = Order::LockedOrders()->notCanceledOrders()->get();

		return view('master-box.admin.orders.email_locked_orders')->with(compact(
      'locked_orders'
    ));

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
