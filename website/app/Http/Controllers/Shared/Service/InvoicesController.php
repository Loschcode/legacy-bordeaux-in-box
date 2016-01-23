<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;

use Config, Log, Response, Mail;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentProfile;
use App\Models\CustomerOrderPreference;
use App\Models\Payment;

use App\Models\CompanyBilling;
use App\Models\CompanyBillingLine;

use App\Models\ContactSetting;

use App\Libraries\Payments;

class InvoicesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Invoices Controller
  |--------------------------------------------------------------------------
  |
  | Will receive the stripes callbacks from user invoices
  |
  */
  protected $managed_events = ['charge.succeeded', 'charge.failed', 'charge.refunded'];
  protected $no_updatable_order_status = ['packing', 'delivered', 'canceled'];
  protected $log_num = 0;
  protected $log_store = '';
  protected $infinite_scheduled_orders = 5;

  protected $stripe_raw = FALSE;
  protected $stripe_environment = [];
  protected $stripe_transaction = [];
  protected $stripe_metadata = FALSE;

  public function log_now($message) {

    Log::info($this->log_num.". $message");
    $this->log_store .= $this->log_num.". $message\r\n";
    $this->log_num++;

  }

  public function prepare_callback_reception() {

    $input = @file_get_contents("php://input");
    $datas = json_decode($input);
    return $datas;

  }

  public function end_transaction() {

    $this->log_now('Transaction succeeded.');
    $this->log_now('--------------------------------');
    return Response::make('Transaction succeeded.', 200);

  }

  public function abort_transaction() {

    $this->log_now('Transaction aborted.');
    
    if (isset($this->stripe_environment['event_id']))
      $this->log_now('Stripe event trace : ' . $this->stripe_environment['event_id']);

    $this->log_now('--------------------------------');
    return Response::make('Transaction failed.', 500);

  }

  public function setup_stripe_variables($datas) {

    try {

      $this->stripe_raw = $datas->data->object;

      /**
       * Environmental data
       */
      
      $this->stripe_environment['event_id'] = $datas->id; // stripe event id
      $this->stripe_environment['charge_id'] = $this->stripe_raw->id; // stripe charge id
      $this->stripe_environment['card_id'] = $this->stripe_raw->source->id; // stripe card id
      $this->stripe_environment['customer_id'] = $this->stripe_raw->customer; // customer id
      $this->stripe_environment['invoice_id'] = $this->stripe_raw->invoice; // invoice id

      $this->log_now("Stripe environment : " . $this->inject_var_dump($this->stripe_environment));

      /**
       * Transactional data
       */
      if ($datas->type === 'charge.refunded') {

        $this->stripe_transaction['refund'] = TRUE;
        $this->stripe_transaction['amount'] = $this->stripe_raw->amount_refunded;

      } else {
        
        $this->stripe_transaction['refund'] = $this->stripe_raw->refunded;
        $this->stripe_transaction['amount'] = $this->stripe_raw->amount; // amount in 0000

      }

      $this->stripe_transaction['paid'] = $this->stripe_raw->paid; // true or false
      $this->stripe_transaction['type'] = $datas->type;

      $this->log_now("Stripe transaction : " . $this->inject_var_dump($this->stripe_transaction));

      /**
       * Metadata
       */
      $this->stripe_metadata = $this->stripe_raw->metadata;

      $this->log_now("Stripe metadata : " . $this->inject_var_dump($this->stripe_metadata));

    } catch (Exception $e) {

      $this->log_now("Bad stripe environment|transaction|metadata variable.");
      return $this->abort_transaction();

    }
    
  }

  public function is_order_status_updatable($order) {

    if (!in_array($order->status, $this->no_updatable_order_status))
      return TRUE;
    else
      return FALSE;

  }

  public function is_handlable_transaction($datas) {

    $this->log_now('Transaction type ' . $datas->type);

    if (in_array($datas->type, $this->managed_events))
      return TRUE;
    else
      return FALSE;

  }

  /**
   * Are the metadata well formatted ? Is it processable afterwards ?
   */
  public function has_processable_metadata() {

    if (!isset($this->stripe_metadata->customer_id))
      return FALSE;

    if (!isset($this->stripe_metadata->customer_profile_id))
      return FALSE;

    if (!isset($this->stripe_metadata->payment_type))
      return FALSE;

    return TRUE;

  }

  public function retrieve_subscription_id_from_invoice() {

    $invoice_callback = Payments::getInvoice($this->stripe_environment['invoice_id']);

    if (!$invoice_callback['success']) {

      $this->log_now('Invoice ID doesn\'t match, process aborted');
      $this->log_now('Stripe event trace : ' . $this->stripe_environment['event_id']);
      return FALSE;

    }

    // From the charge we got the invoice, from the invoice we got the subscription if there's one
    // There must be a subscription, because it has no metadata
    $stripe_subscription_id = $invoice_callback['invoice']['subscription'];

    $this->log_now("We retrieved the subscription id `$stripe_subscription_id`");

    return $stripe_subscription_id;

  }

  public function transaction_already_done() {

    $transaction_already_done = Payment::where('stripe_event', '=', $this->stripe_environment['event_id'])->first();

    if ($transaction_already_done !== NULL) {

      $this->log_now('This transaction we already done.');
      return TRUE;
    
    }

    return FALSE;

  }

  public function associate_orders_from_original_payment($payment) {

    $this->log_now('We will try to recover the order from the original charge of the refund and associate it');
    
    $original_payment = Payment::where('stripe_charge', '=', $this->stripe_environment['charge_id'])->withOrders()->first();

    # We associate all the orders to the refund
    if ($original_payment !== NULL) {

      foreach ($original_payment->orders()->get() as $order) {
        $payment->orders()->attach($order->id);
      }

    }

   return NULL;

  }

  public function generate_payment($customer, $customer_profile, $payment_type, $database_amount) {

    /**
     * Alright, everything seems clean.
     * Let's process all the payment system
     */
    $payment = new Payment;
    $payment->profile()->associate($customer_profile);
    $payment->customer()->associate($customer);

    $payment->stripe_event = $this->stripe_environment['event_id'];
    $payment->stripe_customer = $this->stripe_environment['customer_id'];
    $payment->stripe_charge = $this->stripe_environment['charge_id'];
    $payment->stripe_card = $this->stripe_environment['card_id'];

    $payment->type = $payment_type;
    $payment->paid = $this->stripe_transaction['paid'];
    $payment->last4 = Payments::getLast4FromCard($this->stripe_environment['customer_id'], $this->stripe_environment['card_id']);

    $this->log_now('We made the payment entry');

    // If it's a stripe refund the debit will be negative
    if ($this->stripe_transaction['refund']) $payment->amount = -$database_amount;
    else $payment->amount = +$database_amount;

    $payment->save();

    /**
     * Refund auto-detect orders
     * If it's a refund, we might have the same stripe_charge in the database
     * We can recover it to recover the orders as well
     */
    if ($this->stripe_transaction['refund']) {

      $this->associate_orders_from_original_payment($payment);

    }

    /**
     * We take into consideration the fees
     */
    $callback = Payments::getBalanceFeesFromCharge($this->stripe_environment['charge_id']);

    if ($callback['success']) {

      $fees = $callback['fees'];

      if ($this->stripe_transaction['refund']) $payment->fees = -$fees;
      else $payment->fees = +$fees;

    } else {

      $this->log_now('We could not retrieve the fees for this transaction ; it will be considered 0.');

    }

    $payment->save();

    return $payment;

  }

  public function manage_orders_from_refund($customer, $customer_profile, $payment, $money_left) {

    /**
     * We will get the refundable orders and fill them successively
     */
    $orders = $customer_profile->orders()->onlyRefundable()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();
    $orders_num = $orders->count();

    $this->log_now("$orders_num orders able to be refunded right now");

    // We will calculate for each order until there's no money left to refund
    foreach ($orders as $order) {

      if ($money_left >= 0)
        break;

      $this->log_now("Order (`".$order->id."`) is fetching (refund) : $money_left euros left");

      $money_to_debit = $order->already_paid;
      $money_left = round($money_left + $money_to_debit, 2);

      $order->already_paid -= $money_to_debit;

      if ($money_left > 0)
        $order->already_paid += $money_left;

      /**
       * If it's packing, we won't change the status since it's already in packing mode
       */
      if ($this->is_order_status_updatable($order)) {

        if ($order->already_paid >= $order->unity_and_fees_price)
          $order->status = 'paid';
        elseif ($order->already_paid == 0)
          $order->status = 'unpaid';
        else
          $order->status = 'half-paid';

      }

      $order->payment_way = 'stripe_card';
      $order->save();

      $payment->orders()->attach($order);
      $payment->save();

      $this->log_now('The payment has been associated, the order (`'.$order->id.'`) is now paid (refund) : '.$money_left.' euros left)');

    }

  }

  public function manage_orders_from_payment($customer, $customer_profile, $payment, $money_left) {

    /**
     * We will get the payable orders and fill them successively
     */
    
    $orders = $customer_profile->orders()->onlyPayable()->orderBy('created_at', 'asc')->orderBy('id', 'asc')->get();
    $orders_num = $orders->count();

    $this->log_now("$orders_num orders able to be filled right now");

    // We will calculate for each order until there's no money left
    foreach ($orders as $order) {

      /**
       * On each loop we check the money left
       * If there's none we don't even touch the orders left
       */
      if ($money_left <= 0)
        break;

      /**
       * If the payment failed, we will loop the orders anyway
       * And set to `failed` all the orders concerned by this payment
       */
      if (!$payment->paid) {

        $order->status = 'failed';
        $order->payment_way = 'stripe_card';
        $order->save();

        $money_to_debit = $order->unity_and_fees_price - $order->already_paid;
        $money_left = round($money_left - $money_to_debit, 2);

        $payment->orders()->attach($order);
        $payment->save();

        /**
         * We skip the rest of the procedure for this one
         * Since it will never fill anything for real
         */
        continue;

      }

      $this->log_now("Order is fetching : $money_left euros left");

      $money_to_debit = $order->unity_and_fees_price - $order->already_paid;
      $money_left = round($money_left - $money_to_debit, 2);

      $order->already_paid += $money_to_debit;

      if ($money_left < 0)
          $order->already_paid += $money_left; // eg. money_left -5 and paid 20, will result 15

      /**
       * If it's packing, we won't change the status since it's already in packing mode
       */
      if ($this->is_order_status_updatable($order)) {

        if ($order->already_paid >= $order->unity_and_fees_price)
          $order->status = 'paid';
        else
          $order->status = 'half-paid';

      }

      $order->payment_way = 'stripe_card';
      $order->save();

      $payment->orders()->attach($order);
      $payment->save();

      $this->log_now('The payment has been associated, the order is now paid : '.$money_left.' euros left');

      /**
       * It's an infinite plan so each time someone pays, we generate a new order
       */
      if ($customer_profile->order_preference()->first()->frequency == 0) {

        $this->log_now("It's an infinite plan, we will generate a new order for it ...");

        // Only if don't exceed a certain amount of orders in advance (`scheduled`), we add one
        if ($customer_profile->orders()->where('status', '=', 'scheduled')->count() < $this->infinite_scheduled_orders)
          generate_new_order($customer, $customer_profile);

      }

    }

  }

  public function calculate_total_unpaid_orders($customer, $customer_profile, $payment) {

    $orders_unpaid_plans_fetch = $payment->profile()->first()->orders()->onlyPayable()->get();
    $orders_unpaid_plans = 0;

    foreach ($orders_unpaid_plans_fetch as $order) {

      /**
       * If we are in a special case of packing status
       * If the guy didn't pay, we add it to the orders unpaid plans
       */
        if ($order->status == 'packing') {

          $this->log_now("Packing special case : we will check if it is paid or not and take it out from our selection ...");

          $paid = intval($order->already_paid);

          if ($order->already_paid <= $order->unity_and_fees_price) {

            $orders_unpaid_plans++;

            $this->log_now("It is effectively unpaid / half-paid, while packing, we might not cancel the plan if there is one.");
            warning_tech_admin('masterbox.emails.admin.packing_order_not_paid_warning', 'Problème de commande en cours de packing non payée', $customer, $customer_profile, $payment, $this->log_store);
          
          }

          continue;

        }

        $orders_unpaid_plans++;

    }

    $this->log_now('There is ' . $orders_unpaid_plans . ' unpaid orders left at the end of this transaction.');

    return $orders_unpaid_plans;

  }

  public function cancel_current_subscription($customer, $customer_profile, $payment) {

    $this->log_now('We will cancel the plan ...');

    $order_preference = CustomerOrderPreference::where('customer_profile_id', $customer_profile->id)->first();
    $plan = $order_preference->stripe_plan;

    // We will cancel the plan if it's not a frequency 1
    // (means he invoiced only once and doesn't have any plan)
    // or if it's not a gift (which means he also paid in once)

    if (($plan) && ($order_preference->frequency > 1) && (!$order_preference->gift)) {

      // Update 24/07/2015 -> We need it to cancel subscriptions
      $payment_profile = $customer_profile->payment_profile()->orderBy('created_at', 'desc')->first(); // Just in case of bug
      $stripe_subscription_id = $payment_profile->stripe_subscription;

      $this->log_now('Cancelling the subscription : ' . $stripe_subscription_id . ' for the stripe customer : '. $this->stripe_environment['customer_id']);
      $feedback = Payments::cancelSubscription($this->stripe_environment['customer_id'], $stripe_subscription_id);

      if ($feedback !== FALSE) {

          $this->log_now('The plan has been canceled for this user.');

      } else {
          
          $this->log_now('The plan has not been canceled, there is a stripe problem');
                
      }

    } else {

      $this->log_now('We cannot cancel the plan, it is defined as an infinite one. The customer might have paid for nothing.');
      warning_tech_admin('masterbox.emails.admin.trying_to_cancel_infinite_plan_while_paying', 'Tentative d\'annulation automatisée d\'un plan infini', $customer, $customer_profile, $payment, $this->log_store);

    }

  }

  /**
   * Home page
   */
  public function postIndex()
  {

    /**
     * We prepare the transaction and setup some variables
     */
    $datas = $this->prepare_callback_reception();

    /**
     * If we don't manage this event we shouldn't go further
     * We end it properly
     */
    if (!$this->is_handlable_transaction($datas))
      return $this->end_transaction();

    /**
     * Now we setup all stripe variables
     */
    $this->setup_stripe_variables($datas);

    /**
     * If this transaction has already been done, we just end it here.
     */
    if ($this->transaction_already_done())
      return $this->end_transaction();

    /**
     * If it hasn't processable metadata directly from the charge
     * It means it's certainly a subscription callback
     * So we process another way to get some more information about the customer (customer & customer_profile)
     */
    if (!$this->has_processable_metadata()) {

      $this->log_now("No processable metadata, it might be a subscription callback, not a direct transaction ...");

      /**
       * WARNING : 
       * I use this way to retrieve data directly from the profile id
       * If we use the same account for multiple things we won't be able to do this
       * I saved the other way in case this one fail so it's still usable
       * But ONLY if we got metadata when a card is charged (plenty of test if you change this)
       */
      
      // TODO REFACTO THIS INTO SOME OTHER FUNCTIONS (ONE FUNCTION = ONE WAY)
      // SHOULD ALSO BE BRANCH READY JUST IN CASE (PUT IN TODO LATER)
      if (!$customer_payment_profile = CustomerPaymentProfile::where('stripe_customer', $this->stripe_environment['customer_id'])->first()) {

        /**
         * We get the subscription id from the invoice id we got in the metadata
         */
        if (!$stripe_subscription_id = $this->retrieve_subscription_id_from_invoice()) {

          $this->log_now('We could not retrieve the subscription id from the invoice');
          return $this->abort_transaction();

        }

        // We retrieve the user profile from its subscription
        $customer_payment_profile = CustomerPaymentProfile::where('stripe_subscription', $stripe_subscription_id)->first();

      }

      $this->log_now('We tried to look from the user profile and check the customer ID from his own profile ...');

      // If the user payment profile has been retrieved
      if ($customer_payment_profile === NULL) {

        $this->log_now('Customer ID does not match any database data');
        return $this->abort_transaction();

      }

      $this->log_now("It worked, let's process the payment ...");

      /**
       * It all we need to pursue the process
       */
      $customer_profile = $customer_payment_profile->profile()->first();
      $customer = $customer_profile->customer()->first();
      $payment_type = 'plan';


    } else {

      /**
       * It all we need to pursue the process
       */
      $customer_profile = CustomerProfile::find($this->stripe_metadata->customer_profile_id);
      $customer = Customer::find($this->stripe_metadata->customer_id);
      $payment_type = $this->stripe_metadata->payment_type;

    }

    /**
     * If we didn't find anything within the models, we abort
     */
    if (($customer === NULL) || ($customer_profile === NULL)) {

      $this->log_now('We did not find any matching customer or customer profile with the data given.');
      $this->abort_transaction();

    }

    /**
     * We couldn't check before because we lacked data about the user
     * Now we can check if the amount refunded wasn't total or not, if it's a refund
     */
    
    if (($this->stripe_transaction['refund']) && ($his->stripe_raw->amount_refunded < $this->stripe_raw->amount)) {

      warning_tech_admin('masterbox.emails.admin.partial_refund_order_problem', 'Problème de remboursement partiel d\'une commande', $customer, $customer_profile, NULL, $this->log_store);

    }

    // Will be the database amount (10.00 instead of 1000)
    $database_amount = (float) $this->stripe_transaction['amount'] / 100;

    $payment = $this->generate_payment($customer, $customer_profile, $payment_type, $database_amount);
    $money_left = $payment->amount;
    
    if (!$payment->paid)
      $this->log_now('This transaction has not been successfull.');

    $this->log_now('Money for this transaction : ' . $money_left . ' euros.');

    /**
     * 
     * ORDERS DONE SYSTEM 
     * (WARNING : BE CAREFUL WITH THIS SHIT
     * IT IS WERE WE SAY "THE USER PAID YOU CAN SEND A BOX")
     * 
     */

    $this->log_now("We will now fetch the orders ...");

    /**
     * If it's a refund we manage the orders in the exact opposite way from normal
     */
    if ($this->stripe_transaction['refund']) {

      $this->manage_orders_from_refund($customer, $customer_profile, $payment, $money_left);

    } else {

      $this->manage_orders_from_payment($customer, $customer_profile, $payment, $money_left);

    }

    $this->log_now('End of order fetching.');

    /**
     * We will check if all the orders has been paid or not
     * We must do a loop because the `packing` status is payable so we should look at it precisely
     * If it's not 100% paid during packing, we should increment it.
     */
    $orders_unpaid_plans = $this->calculate_total_unpaid_orders($customer, $customer_profile, $payment);

    if ($orders_unpaid_plans <= 0) {

      $this->cancel_current_subscription($customer, $customer_profile, $payment);

    }

    /**
     * We will manage the billing lines and link it
     * If there's no order linked we must create a new company billing
     * If there an order, we just have to associate and add a company billing line
     */
    if ($payment->orders()->first() !== NULL) {

      /**
      * Each order linked to the payment will be linked to the payment in the billing system
       */
      foreach ($payment->orders()->get() as $order) {

        $this->log_now('We will add 2 lines to the company billing linked to this order ('.$order->unity_price.' / '.$order->delivery_fees.')');

        $company_billing = $order->company_billing()->first();

        // If the company billing is empty we generate it beforehand
        if ($company_billing === NULL)
          $company_billing = generate_new_company_billing_from_order($order, TRUE);

        if ($this->stripe_transaction['refund']) {

          $billing_line = new CompanyBillingLine;
          $billing_line->company_billing_id = $company_billing->id;
          $billing_line->payment_id = $payment->id;
          $billing_line->label = "Remboursement de la box surprise";
          $billing_line->amount = $payment->amount;
          $billing_line->save();

        } else {

          $unity_price = $order->unity_price;
          $delivery_fees = $order->delivery_fees;

          $billing_line = new CompanyBillingLine;
          $billing_line->company_billing_id = $company_billing->id;
          $billing_line->payment_id = $payment->id;
          $billing_line->label = "Achat de la box surprise";
          $billing_line->amount = $unity_price;
          $billing_line->save();

          $billing_line = new CompanyBillingLine;
          $billing_line->company_billing_id = $company_billing->id;
          $billing_line->payment_id = $payment->id;
          $billing_line->label = "Frais de transport de la box surprise";
          $billing_line->amount = $delivery_fees;
          $billing_line->save();

       }

      }

    } else {

      $this->log_now('We will generate a company billing without order from the payment `'.$payment->id.'`');

      /**
       * If there's no order linked to this payment, there might a very high chance
       * There is no company billing existing for this payment so we must generate it in standalone mode
       */
      $company_billing = generate_new_company_billing_without_order($payment);

      $this->log_now('We will add 1 line to the company billing freshly generated');

      /**
       * Now we can guess if it's a refund or not what is the label of the bill
       */
      if (!$this->stripe_transaction['refund']) {

        $billing_line = new CompanyBillingLine;
        $billing_line->company_billing_id = $company_billing->id;
        $billing_line->payment_id = $payment->id;
        $billing_line->label = "Achat et frais de transport de la box surprise";
        $billing_line->amount = $payment->amount;
        $billing_line->save();

      } else {

        $billing_line = new CompanyBillingLine;
        $billing_line->company_billing_id = $company_billing->id;
        $billing_line->payment_id = $payment->id;
        $billing_line->label = "Remboursement de la box surprise";
        $billing_line->amount = $payment->amount;
        $billing_line->save();

      }

    }

    // 
    // Now we will send a confirmation email after everything has been done
    // 

    // For the email
    $email_amount = euros($database_amount);
    if ($this->stripe_transaction['refund']) $email_amount = euros($email_amount) . ' (remboursement)';

    $data = [

    'first_name' => $customer->first_name,
    'amount' => $email_amount,
    'paid' => $payment->paid,

    ];

    $email = $customer->email;

    mailing_send($customer_profile, "Bordeaux in Box - Transaction bancaire", 'masterbox.emails.transaction', $data, NULL);

    $this->log_now('27. Transaction email sent to ' . $email);

    /**
     * It's an orphan payment, we should check it now
     */
    if ($payment->orders()->first() === NULL) {

      warning_tech_admin('masterbox.emails.admin.orphan_payment_warning', 'Paiement orphelin détecté', $customer, $customer_profile, $payment, $this->log_store);

    }

   /**
    * The payment hasn't been paid at the end
    */
    if (!$payment->paid) {

      warning_tech_admin('masterbox.emails.admin.transaction_fail_warning', 'Problème de transaction bancaire', $customer, $customer_profile, $payment, $this->log_store);

    }

    $this->end_transaction();

  }

  private function inject_var_dump($var_dumped) 
  {
    ob_start();
    var_dump($var_dumped);
    $final_text = ob_get_clean();

    return $final_text;

  }


}
