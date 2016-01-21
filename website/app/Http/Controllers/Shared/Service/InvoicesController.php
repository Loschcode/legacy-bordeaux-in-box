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

  protected $stripe_raw = FALSE;
  protected $stripe_environment = [];
  protected $stripe_transaction = [];
  protected $stripe_metadata = FALSE;

  public function log_now($message) {

    Log::info($this->log_num.". $message");
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
      $this->stripe_transaction['refund'] = $this->stripe_raw->refunded;
      $this->stripe_transaction['amount'] = $this->stripe_raw->amount; // amount in 0000
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

    if (!in_array($datas->type, $this->no_updatable_order_status))
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

  public function associate_orders_from_original_payment() {

    $this->log_now('We will try to recover the order from the original charge of the refund and associate it');
    
    $original_payment = Payment::where('stripe_charge', '=', $this->stripe_environment['charge_id'])->withOrders()->first();

    # We associate all the orders to the refund
    if ($original_payment !== NULL) {

      foreach ($original_payment->orders()->get() as $order) {
        $payment->orders()->attach($orders->id);
      }

    }

   return NULL;

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

      $this->log_now("No processable metadata, it might me a subscription callback, not a direct transaction ...");

      /**
       * We get the subscription id from the invoice id we got in the metadata
       */
      if (!$stripe_subscription_id = $this->retrieve_subscription_id_from_invoice()) {

        $this->log_now('We could not retrieve the subscription id from the invoice');
        return $this->abort_transaction();

      }

      // We retrieve the user profile from its subscription
      $customer_payment_profile = CustomerPaymentProfile::where('stripe_subscription', $stripe_subscription_id)->first();

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
      $customer_ = $customer_profile->customer()->first()->id;
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
    if (($customer === NULL) || ($customer_profile === NULL)) {

      $this->log_now('We did not find any matching customer or customer profile with the data given.');
      $this->abort_transaction();

    }

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

    $database_amount = (float) $this->stripe_transaction['amount'] / 100;

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

    $this->log_now("We will now fetch the orders ...");

    /**
     * 
     * ORDERS DONE SYSTEM 
     * (WARNING : BE CAREFUL WITH THIS SHIT
     * IT IS WERE WE SAY "THE USER PAID YOU CAN SEND A BOX")
     * 
     */
      
    // If he didn't really pay, he has no money left (VERY IMPORTANT)
    //if ($payment->paid) $money_left = $payment->amount;
    //else $money_left = 0;
    
    if (!$payment->paid)
      $this->log_now('This transaction has not been successfull.');

    $this->log_now('Customer money left for this transaction : ' . $money_left . ' euros.');

    /**
     * If it's a refund we skip all the order payment process
     */
    if ($this->stripe_transaction['refund']) {

      /*if ($payment->order()->first() !== NULL) {

        $payment

      }*/

    } else {

    /**
     * We will get the payable orders and fill them successively
     */
    $orders = $customer_profile->orders()->onlyPayable()->get();
    $orders_num = $orders->count();

    $this->log_now("$orders_num orders able to be filled right now");

    // We will calculate for each order until there's no money left
    foreach ($orders as $order) {

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

        $money_left = round($money_left - $order->unity_and_fees_price, 2);

        $payment->orders()->attach($order);
        $payment->save();
     
      } else {

          $this->log_now("Order is fetching ($money_left EUR left)");
          
          $money_left = round($money_left - $order->unity_and_fees_price + $order->already_paid, 2);

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
          
          $this->log_now('The payment has been associated, the order is now paid ('.$money_left.' EUR left)');

          /**
           * It's an infinite plan so each time someone pays, we generate a new order
           */
          if ($customer_profile->order_preference()->first()->frequency == 0) {

              $this->log_now("It's an infinite plan, we will generate a new order for it ...");

              generate_new_order($customer, $customer_profile);

          }

          /**
            * If we are in a special case of packing status
               * If the guy already paid it, we ignore this order and pass to the next one for the user
               */
              /*if ($order->status == 'packing') {

                $this->log_now('The order has a `packing` status, we will check if it has already been paid ...');

                $paid = intval($order->already_paid);

                if ($paid > 0) {

                  $this->log_now('It has been paid : we will skip the order ...');

                  continue;
                }

                $this->log_now('It has not been paid : we will not skip the order ...');

              }

              if ($money_left <= 0) {
                break;
              }*/

              // We decrement the money left and done the order each after the others
              // We round() it because this fucking PHP doesn't know how to count otherwise
              // (`-7.1054273576E-15` problem)
              //$money_left = round($money_left - $order->unity_and_fees_price, 2);

              //$this->log_now("There will be $money_left (-".$order->unity_and_fees_price.") after this order");

              //if ($money_left >= 0) {

                //$payment->order()->associate($order);
                //$payment->save();

                //if ($order->status != 'packing') {
                //  $order->status = 'paid';
                //}

                //$order->payment_way = 'stripe_card';
                //$order->already_paid = $order->unity_and_fees_price;
                //$order->save();

                /**
                 * INFINITE PLAN SYSTEM
                 * We will generate the exact same order twice
                 */
                
                /*if ($customer_profile->order_preference()->first()->frequency == 0) {

                  $this->log_now("It's an infinite plan, we will generate a new order for it ...");

                  generate_new_order($customer, $customer_profile);

                }*/

                /**
                 * End of infinite plan system
                 */

              /*} else {

                // If the money left is negative, there's a big problem here
                $this->log_now("The order was half-paid, there's $money_left EUR left");

                $payment->order()->associate($order);
                $payment->save();

                //$order->payment()->associate($payment);
              
                $order->status = 'half-paid';
                $order->payment_way = 'stripe_card';
                $order->already_paid = $order->unity_and_fees_price + $money_left;
                $order->save();

              }*/

            }

          }

          $this->log_now('End of order fetching.');

          // We check if all the orders has been paid, if so, we cancel the subscription (the gift area is very important)
          $orders_unpaid_plans_fetch = $payment->profile()->first()->orders()->where('status', '!=', 'paid')->where('status', '!=', 'delivered')->where('status', '!=', 'canceled')->get();
          $orders_unpaid_plans = 0;

          foreach ($orders_unpaid_plans_fetch as $order) {

            /**
             * If we are in a special case of packing status
             * If the guy didn't pay, we add it to the orders unpaid plans
             */
            if ($order->status == 'packing') {

              $this->log_now("Packing special case : we will check if it is paid or not and take it out from our selection ...");

              $paid = intval($order->already_paid);

              if ($paid <= 0) {

                $orders_unpaid_plans++;

                $this->log_now("It is effectively unpaid, while packing, we might not cancel the plan if there is one.");
              }

              continue;

            }

            $orders_unpaid_plans++;

          }

          $this->log_now('There is ' . $orders_unpaid_plans . ' orders left at the end of this transaction.');

          /**
           * We will manage the billing lines and link it
           * If there's no order linked we must create a new company billing
           * If there an order, we just have to associate and add a company billing line
           */
          if (($payment->order()->first() !== NULL) && ($payment->order()->first()->company_billing()->first() !== NULL)) {

            $this->log_now('We will add 2 lines to the company billing linked to this order ('.$order->unity_price.' / '.$order->delivery_fees.')');

            $order = $payment->order()->first();
            $company_billing = $order->company_billing()->first();

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
            if ($payment->amount >= 0) {

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

          if ($orders_unpaid_plans <= 0) {

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

            }

          }

        }

        // 
        // Now we will send a confirmation email after everything has been done
        // 
        
        // For the email
        $email_amount = number_format($database_amount, 2);
        if ($this->stripe_transaction['refund']) $email_amount = $email_amount . ' (remboursement)';
        
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
        if ($payment->order()->first() === NULL) {

          // Technical recipient will receive the failure
          $email = ContactSetting::first()->tech_support;
          $customer_email = $customer->email;
          $customer_full_name = $customer->getFullName();
          $customer_profile_id = $customer_profile->id;

          $data = [

            'payment_id' => $payment->id,
            'customer_email' => $customer_email,
            'customer_full_name' => $customer_full_name,
            'profile_id' => $customer_profile_id,

          ];

          // WE SHOULD MAKE A FUNCTION FOR THIS
          Mail::queue('masterbox.emails.admin.orphan_payment_warning', $data, function($message) use ($email)
          {

              $message->from($email)->to($email)->subject('WARNING : Paiement orphelin détecté');

          });

        }

        /**
         * The payment hasn't been paid at the end
         */
        if (!$payment->paid) {

          // Communication recipient will receive the failure
          $email = ContactSetting::first()->com_support;
          $customer_email = $customer->email;
          $customer_full_name = $customer->getFullName();
          $customer_profile_id = $customer_profile->id;

          $data = [

            'customer_email' => $customer_email,
            'customer_full_name' => $customer_full_name,
            'profile_id' => $customer_profile_id,

          ];

          // WE SHOULD MAKE A FUNCTION FOR THIS
          Mail::queue('masterbox.emails.admin.transaction_fail_warning', $data, function($message) use ($email)
          {

              $message->from($email)->to($email)->subject('WARNING : Problème de transaction bancaire');

          });


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
