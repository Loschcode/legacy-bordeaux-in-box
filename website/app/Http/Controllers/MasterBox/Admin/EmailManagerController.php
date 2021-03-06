<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\DeliverySerie;
use App\Models\DeliverySpot;

class EmailManagerController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Illustration Controller
	|--------------------------------------------------------------------------
	|
	| Add / Edit / Delete blog
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
     * Get the listing page of the blog
     * @return void
     */
	public function getIndex()
	{

	}

	/**
	 * Send an email to the orders linked with the $series for shipped orders (take_away = false)
	 * @param  integer $series_id
	 * @param  integer $spot_id
	 * @return void
	 */
	public function getSendEmailToSeriesShippedOrders($series_id)
	{

		$series = DeliverySerie::find($series_id);
		if ($series == NULL) return redirect()->to('/');

		$delivered_orders = $series->orders()->DeliveredOrders()->where('take_away', '=', false)->get();

		$email_counter = 0;

		foreach ($delivered_orders as $order) {

			// Shipping address
			$destination = $order->destination()->first();
			$billing = $order->billing()->first();

			if ($destination == NULL) $destination_address = FALSE;
			else $destination_address = $destination->emailReadableDestination();

			if ($billing == NULL) $billing_address = FALSE;
			else $billing_address = $billing->emailReadableBilling();

			$gift = $order->gift;

			// Details about the user
			$customer = $order->customer()->first();
			$profile = $order->customer_profile()->first();

			// Now we get the important informations we don't have yet
			$email = $customer->email;

			$data = [

				'first_name' => $customer->first_name,

				'series_date' => $series->delivery,

				'destination_address' => $destination_address,
				'billing_address' => $billing_address,

				'gift' => $gift,

			];

			// Finally we send the email
			mailing_send($profile, "Ta box est en cours de livraison", 'masterbox.emails.orders.shipped_delivered', $data, NULL);
			$email_counter++;

		}
			
		session()->flash('message', "La série d'emails a bien été distribuée ($email_counter emails envoyés)");
		return redirect()->back();

	}


	/**
	 * Send an email to the orders linked with the $series and only for a specific $spot
	 * @param  integer $series_id
	 * @param  integer $spot_id
	 * @return void
	 */
	public function getSendEmailToSeriesSpotOrders($series_id, $spot_id)
	{

		$series = DeliverySerie::find($series_id);
		if ($series == NULL) return redirect()->to('/');

		$spot = DeliverySpot::find($spot_id);
		if ($spot == NULL) return redirect()->to('/');

		$delivered_orders = $spot->getDeliveredSeriesOrders($series)->get();

		$email_counter = 0;

		foreach ($delivered_orders as $order) {

			// Details about the user
			$customer = $order->customer()->first();
			$profile = $order->customer_profile()->first();

			// Now we get the important informations we don't have yet
			$email = $customer->email;
			$gift = $order->gift;

			$data = [

				'first_name' => $customer->first_name,

				'series_date' => $series->delivery,

				'gift' => $gift,

				'spot_name' => $spot->name,
				'spot_name_and_infos' => $spot->emailReadableSpot(),
				'spot_schedule' => nl2br($spot->schedule), // the schedule might be on a couple of lines

			];

			// Finally we send the email
			mailing_send($profile, "Ta box vient d'être livrée", 'masterbox.emails.orders.spot_delivered', $data, NULL);
			$email_counter++;

		}
			
		session()->flash('message', "La série d'emails a bien été distribuée ($email_counter emails envoyés).");
		return redirect()->back();

	}

}