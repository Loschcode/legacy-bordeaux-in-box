<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Payment;
use App\Models\DeliverySerie;
use App\Models\Box;
use App\Models\Order;

use Request, Validator;


class PaymentsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Admin Payments Controller
	|--------------------------------------------------------------------------
	|
	| Check and edit payments
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

		$payments = Payment::orderBy('created_at', 'desc')->get();
		$series = DeliverySerie::orderBy('delivery', 'desc')->get();
		$boxes = Box::orderBy('created_at', 'desc')->get();

		return view('masterbox.admin.payments.index')->with(compact(

      'payments',
      'series',
      'boxes'

    ));

	}

	public function getFocus($id)
	{

		$payment = Payment::find($id);

		$profile = $payment->profile()->first();

		if ($payment->order()->first() == NULL) $payment_order_id = 0;
		else $payment_order_id = $payment->order()->first()->id;


		// We generate the order depending on what the user got
		$order_series_list = [0 => '-'];

		foreach ($profile->orders()->get() as $order) {

			$order_id = $order->id;
			$series_label = $order->delivery_serie()->first()->delivery;

			$order_series_list[$order_id] = $series_label;

		}


		return view('masterbox.admin.payments.focus')->with(compact(
      'payment',
      'profile',
      'payment_order_id',
      'order_series_list'
    ));

	}

	public function postUpdatePaymentOrder()
	{

		$rules = [

			'payment_id' => 'required|numeric',
			'order_id' => 'required|numeric',

			];

		$fields = Request::all();
		$validator = Validator::make($fields, $rules);

		// The form validation was good
		if ($validator->passes()) {

			$payment = Payment::find($fields['payment_id']);
			$order = Order::find($fields['order_id']);

			if ($payment == NULL) return redirect()->to('/');

			if ($order == NULL) $payment->order_id = NULL;
			else {

				// If it's a normal payment 
				// We should consider the order as paid now
				if (($payment->amount > 0) && ($payment->paid)) {

					$order->already_paid = $order->already_paid + $payment->amount;
					$order->payment_way = 'stripe_card'; // all the payments from here are stripe cards

					if (($order->stauts == 'scheduled') || ($order->status == 'paid') || ($order->status == 'half-paid')) {

						// Half paid
						if ($order->already_paid < $order->unity_and_fees_price) {

							$order->status = 'half-paid';

						// Completely paid or even more
						} else {

							$order->status = 'paid';

						}

					}

					// If there were an old order, we should remove the payment from it
					// And cancel the amount
					$old_order = $payment->order()->first();

					if ($old_order !== NULL) {

						// We recalculate the already paid
						$already_paid = $old_order->already_paid - $payment->amount;
						if ($already_paid < 0) $already_paid = 0;
						$old_order->already_paid = $already_paid;

						// If it was paid and the amount is less, it becomes half-paid or unpaid
						if (($old_order->status == 'paid') || ($old_order->status == 'half-paid')) {

							if (!$old_order->already_paid) $old_order->status == 'unpaid';
							elseif ($old_order->already_paid < $old_order->unity_and_fees_price) $old_order->status = 'half-paid';

						}

						$old_order->save();

					}

				}

				// If it's a refund we need to change the data
				// Linked with the already_paid
				if ($payment->amount <= 0) {

					// We recalculate the already paid
					$already_paid = $order->already_paid + $payment->amount;
					if ($already_paid < 0) $already_paid = 0;

					$order->already_paid = $already_paid;

					// If it was paid and the amount is less, it becomes half-paid or unpaid
					if (($old_order->status == 'paid') || ($old_order->status == 'half-paid')) {

						if (!$order->already_paid) $order->status == 'unpaid';
						elseif ($order->already_paid < $order->unity_and_fees_price) $order->status = 'half-paid';

					}


				}

				$payment->order_id = $order->id;

			}

			$order->save();
			$payment->save();

			// Then we redirect
			session()->flash('message', "Le paiement vient d'être relié");
			return redirect()->back();

		} else {

			// We return the same page with the error and saving the input datas
			return redirect()->to(URL::previous())
			->withInput()
			->withErrors($validator);

		}

	}

	public function getLinkPaymentToNextSeries($payment_id)
	{

		$payment = Payment::find($payment_id);
		$profile = $payment->profile()->first();
		$order_to_link = $profile->orders()->where('status', 'scheduled')->where('locked', '!=', TRUE)->first();

		if ($order_to_link != NULL) {

			$payment->order_id = $order_to_link->id;
			$payment->save();
			//$order_to_link->payment_id = $payment->id;

			$order_to_link->already_paid = $order_to_link->already_paid + $payment->amount;
			$order_to_link->status = 'paid';
			$order_to_link->save();

			session()->flash('message', "Le paiement a été relié à cette série");

		} else {

			session()->flash('error', "Ce paiement ne peut être relié à aucune série");

		}

		return redirect()->back();

	}

	/**
	 * We remove the payment
	 */
	public function getDelete($id)
	{

		$payment = Payment::find($id);

		if ($payment !== NULL)
		{

			$payment->delete();

			session()->flash('message', "Le paiement a été archivé");
			return redirect()->back();

		}

	}


    // Check a bill
    public function getDownloadBill($bill_id)
    {

    	$user = Auth::user();
    	$payment = Payment::where('bill_id', $bill_id)->first();

    	if ($payment != NULL) {

    		return generate_pdf_bill($payment, TRUE); // And download

    	}

		return redirect()->to('/');

    }

	/**
	 * We make it fail
	 */
	public function getMakeFail($id)
	{

		$payment = Payment::find($id);

		if ($payment !== NULL)
		{

			$payment->paid = FALSE;
			$payment->save();

			$order = $payment->order()->first();;

			if ($order != NULL) {

				$order->status = 'failed';
				$order->already_paid = 0;
				$order->save();

			}

			session()->flash('message', "Le statut du paiement a bien été changé");
			return redirect()->back();

		}

	}


	/**
	 * We make it fail
	 */
	public function getMakeSuccess($id)
	{

		$payment = Payment::find($id);

		if ($payment !== NULL)
		{

			$payment->paid = TRUE;
			$payment->save();

			$order = $payment->order()->first();
			$money_left = $payment->amount;

			// We will calculate for each order until there's no money left
			if ($order != NULL) {

				if ($money_left <= 0) {

					break;

				}

				// We decrement the money left and done the order each after the others
				$money_left = $money_left - $order->unity_and_fees_price;

				if ($money_left >= 0) {

					$order->status = 'paid';
					$order->already_paid = $order->unity_and_fees_price;
					$order->save();

				} else {

					// If the money left is negative, there's a big problem here
					$order->status = 'half-paid';
					$order->already_paid = $order->unity_and_fees_price + $money_left;
					$order->save();

				}

			}

			session()->flash('message', "Le statut du paiement a bien été changé");
			return redirect()->back();

		}

	}

}