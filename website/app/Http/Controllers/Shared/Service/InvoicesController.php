<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;

use Config, Log, Response;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentProfile;
use App\Models\CustomerOrderPreference;
use App\Models\Payment;

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

  /**
   * Home page
   */
  public function postIndex()
  {
    //$api_key = Config::get('stripe.api_key');
    //Stripe::setApiKey($api_key);

    $input = @file_get_contents("php://input");
    $datas = json_decode($input);

    $managed_events = ['charge.succeeded', 'charge.failed', 'charge.refunded'];

    Log::info('1. Transaction type ' . $datas->type);

    /**
     * We will get all the necessary datas
     */
    $stripe_type = $datas->type; // charge.succeeded

    if (in_array($stripe_type, $managed_events)) {

      $stripe_raw = $datas->data->object; // the object itself

      // All the strips id
      $stripe_event_id = $datas->id; // stripe event id
      $stripe_charge_id = $stripe_raw->id; // stripe charge id
      $stripe_card_id = $stripe_raw->card->id; // stripe card id
      $stripe_customer_id = $stripe_raw->customer; // customer id
      $stripe_invoice_id = $stripe_raw->invoice; // invoice id

      $stripe_refund = $stripe_raw->refunded;

      // All the details about the paiment
      $stripe_amount = $stripe_raw->amount; // amount in 0000
      $stripe_paid = $stripe_raw->paid; // true or false
      $stripe_card_last4 = $stripe_raw->card->last4; // last 4 digits

      // The user metadata recovering
      $metadata = $stripe_raw->metadata;

      Log::info('---');
      Log::info("1B. stripe_event_id : $stripe_event_id");
      Log::info("1B. stripe_charge_id : $stripe_charge_id");
      Log::info("1B. stripe_card_id : $stripe_card_id");
      Log::info("1B. stripe_customer_id : $stripe_customer_id");
      Log::info('---');
      Log::info("1C. stripe_refund : " . $this->inject_var_dump($stripe_refund));
      Log::info("1C. stripe_amount : " . $this->inject_var_dump($stripe_amount));
      Log::info("1C. stripe_paid : " . $this->inject_var_dump($stripe_paid));
      Log::info("1C. stripe_card_last4 : " . $this->inject_var_dump($stripe_card_last4));
      Log::info('---');
      Log::info("1D. metadata : " . $this->inject_var_dump($metadata));
      Log::info('---');

      if ( ! isset($metadata->customer_profile_id)) {

        Log::info("2. `customer_profile_id` doesn't exist in received metadata, processing another way ...");

        $invoice_callback = Payments::getInvoice($stripe_invoice_id);

        if (!$invoice_callback['success']) {

          Log::info('2.1 Invoice ID doesn\'t match, process aborted');
          Log::info('2.2 Stripe event trace : ' . $stripe_event_id);

        }

        // From the charge we got the invoice, from the invoice we got the subscription if there's one
        // There must be a subscription, because it has no metadata
        $stripe_subscription_id = $invoice_callback['invoice']['subscription'];

        Log::info("2.3 We retrieved the subscription id `$stripe_subscription_id`");

        // We retrieve the user profile from its subscription
        $customer_payment_profile = CustomerPaymentProfile::where('stripe_subscription', $stripe_subscription_id)->first();

        Log::info('3. We tried to look from the user profile and check the customer ID from his own profile ...');

        // If the user payment profile has been retrieved
        if ($customer_payment_profile !== NULL) {

          Log::info("4. It worked, let's process the payment ...");

          $customer_profile_id = $customer_payment_profile->profile()->first()->id;
          $customer_profile = CustomerProfile::find($customer_profile_id);

          $customer_id = $customer_profile->customer()->first()->id;
          $payment_type = 'plan';

        } else {

          Log::info('5. Customer ID doesn\'t match, process aborted');
          Log::info('6. Stripe event trace : ' . $stripe_event_id);

          return 'The customer ID doesn\'t match';
        }

      } else {

        $customer_profile_id = $metadata->customer_profile_id;
        $customer_id = $metadata->customer_id;
        $payment_type = $metadata->payment_type;

      }
      
      $profile = CustomerProfile::find($customer_profile_id);
      $customer = Customer::find($customer_id);

      $transaction_already_done = Payment::where('stripe_event', '=', $stripe_event_id)->first();

      // Profile / User has to be found
      if (($profile !== NULL) && ($customer !== NULL) && ($transaction_already_done == NULL)) {

        /**
         * Alright, let's process all the payment system
         */
        $payment = new Payment;
        $payment->profile()->associate($profile);
        $payment->customer()->associate($customer);

        $payment->stripe_event = $stripe_event_id;
        $payment->stripe_customer = $stripe_customer_id;
        $payment->stripe_charge = $stripe_charge_id;
        $payment->stripe_card = $stripe_card_id;

        $payment->type = $payment_type;
        $payment->paid = $stripe_paid;
        $payment->last4 = $stripe_card_last4;

        $database_amount = (float) $stripe_amount / 100;

        Log::info('7. We made the payment entry');

        // If it's a stripe refund the debit will be negative
        if ($stripe_refund) $payment->amount = -$database_amount;
        else $payment->amount = +$database_amount;
        
        /**
         * Refund auto-detect order (before to save this one)
         */
        if ($stripe_refund) {

          $original_payment = Payment::where('stripe_charge', '=', $stripe_charge_id)->whereNotNull('order_id')->first();
          
          if ($original_payment !== NULL)
            $payment->order_id = $original_payment->order_id;

        }

        $payment->save();

        /**
         * We take into consideration the fees
         */
        $callback = Payments::getBalanceFeesFromCharge($stripe_charge_id);

        if ($callback['success']) {

          $fees = $callback['fees'];

          if ($stripe_refund) $payment->fees = -$fees;
          else $payment->fees = +$fees;

          $payment->save();

        } else {

          Log::info('7.1 We couldn\'t retrieve the fees for this transaction.');
          Log::info('7.2 Stripe event trace : ' . $stripe_event_id);

        }

        /**
         * We will manage the billing lines and link it
         * If there's no order linked we must create a new company billing
         * If there an order, we just have to associate and add a company billing line
         */
        if ($payment->order()->first() !== NULL) {

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

          /**
           * If there's no order linked to this payment, there might a very high chance
           * There is no company billing existing for this payment so we must generate it in standalone mode
           */
          $company_billing = generate_new_company_billing_without_order($payment);

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

        Log::info("8. We will now fetch the orders ...");

        /**
         * 
         * ORDERS DONE SYSTEM 
         * (WARNING : BE CAREFUL WITH THIS SHIT
         * IT IS WERE WE SAY "THE USER PAID YOU CAN SEND A BOX")
         * 
         */
        
        // If he didn't really pay, he has no money left
        if ($payment->paid) { 
          $money_left = $payment->amount;
        } else {
          $money_left = 0;
        }

        Log::info('9. Money left : ' . $money_left . ' EUR');

        // Orders (if it's a refund we don't change the orders, otherwise we take the first unpaid one)
        if ($stripe_refund) $orders = [];
        else $orders = $payment->profile()->first()->orders()->where('status', '!=', 'paid')->where('status', '!=', 'delivered')->where('status', '!=', 'canceled')->get();
        // WARNING : If you change this, don't forget to change the $orders count variable at the bottom of this file, it cancels the plans

        if ($stripe_refund) {

          Log::info('10. It is a refund, we will skip some processes ...');

        } else {

          $orders_num = $orders->count();

          Log::info("11. $orders_num orders able to be filled right now");

          // If it failed
          if ($money_left === 0) {

            $order = $orders->first();

            if ($order != NULL) {

              //$order->payment()->associate($payment);
      
              $order->status = 'failed';
              $order->payment_way = 'stripe_card';
              $order->save();

              $payment->order()->associate($order);
              $payment->save();

            }

            Log::info('12. No money left, status failed.');

          }
        
          // We will calculate for each order until there's no money left
          foreach ($orders as $order) {

            /**
             * If we are in a special case of packing status
             * If the guy already paid it, we ignore this order and pass to the next one for the user
             */
            if ($order->status == 'packing') {

              Log::info('13. The order has a `packing` status, we will check if it has already been paid ...');

              $paid = intval($order->already_paid);

              if ($paid > 0) {

                Log::info('14. It has been paid : we will skip the order ...');

                continue;
              }

              Log::info('15. It has not been paid : we will not skip the order ...');

            }

            if ($money_left <= 0) {
              break;
            }

            Log::info("16. Order is fetching ($money_left EUR left)");

            // We decrement the money left and done the order each after the others
            // We round() it because this fucking PHP doesn't know how to count otherwise
            // (`-7.1054273576E-15` problem)
            $money_left = round($money_left - $order->unity_and_fees_price, 2);

            //Log::info("There will be $money_left (-".$order->unity_and_fees_price.") after this order");

            if ($money_left >= 0) {

              $payment->order()->associate($order);
              $payment->save();

              if ($order->status != 'packing') {
                $order->status = 'paid';
              }

              $order->payment_way = 'stripe_card';
              $order->already_paid = $order->unity_and_fees_price;
              $order->save();

              Log::info('17. The payment has been associated, the order is now paid ('.$money_left.' EUR left)');

              /**
               * INFINITE PLAN SYSTEM
               * We will generate the exact same order twice
               */
              
              if ($profile->order_preference()->first()->frequency == 0) {

                Log::info("18. It's an infinite plan, we will generate a new order for it ...");

                generate_new_order($customer, $profile);

              }

              /**
               * End of infinite plan system
               */

            } else {

              // If the money left is negative, there's a big problem here
              Log::info("19. The order was half-paid, there's $money_left EUR left");

              $payment->order()->associate($order);
              $payment->save();

              //$order->payment()->associate($payment);
            
              $order->status = 'half-paid';
              $order->payment_way = 'stripe_card';
              $order->already_paid = $order->unity_and_fees_price + $money_left;
              $order->save();

            }

          }

          Log::info('20. End of order fetching.');

          // We check if all the orders has been paid, if so, we cancel the subscription (the gift area is very important)
          $orders_unpaid_plans_fetch = $payment->profile()->first()->orders()->where('status', '!=', 'paid')->where('status', '!=', 'delivered')->where('status', '!=', 'canceled')->get();
          $orders_unpaid_plans = 0;

          foreach ($orders_unpaid_plans_fetch as $order) {

            /**
             * If we are in a special case of packing status
             * If the guy didn't pay, we add it to the orders unpaid plans
             */
            if ($order->status == 'packing') {

              Log::info("21. Packing special case : we will check if it is paid or not and take it out from our selection ...");

              $paid = intval($order->already_paid);

              if ($paid <= 0) {

                $orders_unpaid_plans++;

                Log::info("22. It is effectively unpaid, while packing, we might not cancel the plan if there is one.");
              }

              continue;

            }

            $orders_unpaid_plans++;

          }

          Log::info('23. There is ' . $orders_unpaid_plans . ' orders left at the end of this transaction.');

          if ($orders_unpaid_plans <= 0) {

            Log::info('24. We will cancel the plan ...');

            $order_preference = CustomerOrderPreference::where('customer_profile_id', $profile->id)->first();
            $plan = $order_preference->stripe_plan;

            // We will cancel the plan if it's not a frequency 1
            // (means he invoiced only once and doesn't have any plan)
            // or if it's not a gift (which means he also paid in once)
        
            if (($plan) && ($order_preference->frequency > 1) && (!$order_preference->gift)) {

              // Update 24/07/2015 -> We need it to cancel subscriptions
              $payment_profile = $profile->payment_profile()->orderBy('created_at', 'desc')->first(); // Just in case of bug
              $stripe_subscription_id = $payment_profile->stripe_subscription;

              Log::info('25. Cancelling the subscription : ' . $stripe_subscription_id . ' for the stripe customer : '. $stripe_customer_id);
              $feedback = Payments::cancelSubscription($stripe_customer_id, $stripe_subscription_id);

              if ($feedback !== FALSE) {

                Log::info('26. The plan has been canceled for this user.');
              } else {
                Log::info('26B. The plan has not been canceled, there is a stripe problem');
              }

            }

          }

        }

        // 
        // Now we will send a confirmation email after everything has been done
        // 
        
        // For the email
        $email_amount = number_format($database_amount, 2);
        if ($stripe_refund) $email_amount = $email_amount . ' (remboursement)';
        
        $data = [

          'first_name' => $customer->first_name,
          'amount' => $email_amount,

        ];

        $email = $customer->email;

        mailing_send($profile, "Bordeaux in Box - Confirmation de transaction", 'masterbox.emails.transaction', $data, NULL);

        Log::info('27. Transaction email sent to ' . $email);

      }

    }

    Log::info('28. Transaction succeeded.');
    Log::info('--------------------------------');
    return Response::make('Transaction succeeded.', 200);

  }

  private function inject_var_dump($var_dumped) 
  {
    ob_start();
    var_dump($var_dumped);
    $final_text = ob_get_clean();

    return $final_text;

  }


}
