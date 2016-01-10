<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Order;
use App\Models\CustomerProfile;
use App\Models\Customer;
use App\Models\CustomerAnswer;
use App\Models\CustomerOrderBuilding;
use App\Models\Payment;
use App\Models\PaymentProfile;
use App\Models\CustomerOrderPreference;
use App\Models\DeliverySerie;


class DebugController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Debug Controller
  |--------------------------------------------------------------------------
  |
  | Check any bug
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
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {

    $all_transactions = Payment::whereNull('order_id')->orderBy('created_at', 'desc')->get();
    $refunded_payments = Payment::whereNull('order_id')->orderBy('created_at', 'desc')->where('amount', '<', 0)->get();
    $series_refunded_payments = Payment::whereNotNull('order_id')->orderBy('created_at', 'desc')->where('amount', '<', 0)->get();
    $payments = Payment::whereNull('order_id')->where('amount', '>=', 0)->orderBy('created_at', 'desc')->get();

    return view('masterbox.admin.debug.index')->with(compact(
      'payments',
      'all_transactions',
      'series_refunded_payments',
      'refunded_payments'
    ));

  }

  public function getDatabaseCorrectionCompletedSeries()
  {

    $affected_rows = 0;

    $orders = Order::notCanceledOrders()->where('status', '=', 'delivered')->where('delivery_serie_id', '=', 24)->get();

    foreach ($orders as $order) {

      $order->date_completed = '2015-05-18';
      $order->save();
      $affected_rows++;

    }

    session()->flash('message', "Les livraisons ont été définies comme complétées, merci de ne jamais réutiliser cette commande. ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  public function getDatabaseCorrectionSeriesInfiniteSequel()
  {

    $profiles = CustomerProfile::get();
    $affected_rows = 0;

    foreach ($profiles as $profile) {

      $double_orders = $profile->orders()->where('delivery_serie_id', '=', 28)->count();

      if ($double_orders > 1) {

        $order_to_change = $profile->orders()->where('delivery_serie_id', '=', 28)->orderBy('id', 'desc')->first();
        $order_to_change->delivery_serie_id = 30;
        $order_to_change->save();

        $affected_rows++;

      }

    }

    session()->flash('message', "Les livraisons ont été modifiées, merci de ne jamais réutiliser cette commande. ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  public function getDatabaseCorrectionSeriesInfinite()
  {

    $profiles = CustomerProfile::get();
    $affected_rows = 0;

    foreach ($profiles as $profile) {

      $double_orders = $profile->orders()->where('delivery_serie_id', '=', 25)->count();

      if ($double_orders > 1) {

        $order_to_change = $profile->orders()->where('delivery_serie_id', '=', 25)->orderBy('id', 'desc')->first();
        $order_to_change->delivery_serie_id = 29;
        $order_to_change->save();

        $affected_rows++;

      }

    }

    session()->flash('message', "Les livraisons ont été modifiées, merci de ne jamais réutiliser cette commande. ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the order `date_completed` as `date_sent` equivalent if it is empty and it has been sent
   */
  public function getUpdateDateCompletedToSentOrders()
  {

    $orders = Order::whereNotNull('date_sent')->get();
    $affected_rows = 0;

    foreach ($orders as $order) {

      $order->date_completed = $order->date_sent;
      $order->save();
      $affected_rows++;

    }

    session()->flash('message', "Les livraisons effectivement envoyées ont une date complétée (`date_completed`) ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Convert the sponsor answer from the user into an email (or remove it if needed)
   * -> NOTE : Very slow loop.
   */
  public function getConvertSponsorAnswerIntoEmail()
  {

    $affected_rows = 0;

    $customers = Customer::get();

    foreach ($customers as $customer) {

        $slugged_user = Str::slug($customer->getFullName());

        $customer_answer = CustomerAnswer::where('slug', '=', $slugged_user)->first();

        if ($customer_answer !== NULL) {
          
          $customer_answer->answer = $customer->email;
          $customer_answer->slug = Str::slug($customer->email);
          $customer_answer->save();

          $affected_rows++;

        }

    }

    // Now we will remove the ones that aren't emails
    $customer_answers = CustomerAnswer::get();

    foreach ($customer_answers as $customer_answer) {

      if ($customer_answer->box_question()->first()->slug == 'sponsor') {

        if (!filter_var($customer_answer->answer, FILTER_VALIDATE_EMAIL)) {

          $customer_answer->delete();
          $affected_rows++;

        }

      }

    }

    session()->flash('message', "Les marraines liées aux questionnaires ont été mises à jour ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the order `status` to `delivered` if it was effectively sent (`date_sent`)
   */
  public function getUpdateSentOrdersAsDelivered()
  {

    $orders = Order::whereNotNull('date_sent')->where('status', '=', 'paid')->get();
    $affected_rows = 0;

    foreach ($orders as $order) {

      $order->status = 'delivered';
      $order->save();
      $affected_rows++;

    }

    session()->flash('message', "Les livraisons effectivement envoyées ont un statut `delivered` ($affected_rows entrées affectées)");
    return redirect()->back();

  }


  /**
   * Update the `status_updated_at` data within the `customer_profiles` table if empty
   * @return [type] [description]
   */
  public function getUpdateCustomerProfileStatusUpdatedAt()
  {

    $profiles = CustomerProfile::get();
    $affected_rows = 0;

    foreach ($profiles as $profile) {

      // If the system didn't have the status_updated_at for this entry, we will guess it
      if ($profile->status_updated_at == NULL) {

        // If he's not subscribed at all, the creation is certainly the correct data
        if ($profile->status == 'not-subscribed') {

          $profile->status_updated_at = $profile->created_at->format('Y-m-d H:i:s');

        // If the status is in-progress, it's certainly the created_at too because people tend to progress directly after subscription
        } elseif ($profile->status == 'in-progress') {

          $profile->status_updated_at = $profile->created_at->format('Y-m-d H:i:s');

        // For this one too, he certainly subscribed directly
        } elseif ($profile->status == 'subscribed') {

          $profile->status_updated_at = $profile->created_at->format('Y-m-d H:i:s');

        // For this one it's a bit more complicated, we need to check the last delivered order
        } elseif ($profile->status == 'expired') {

          $matching_order = $profile->orders()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->first(); // Becaue last() fucking don't exist in Laravel.

          // If there's nothing, we should try to get the last order and the last update (shouldn't happen but we never know)
          if ($matching_order == NULL) {

            $matching_order = $profile->orders()->last();
            $matching_time = $matching_order->updated_at->format('Y-m-d H:i:s');

          } else {

            // This order exists from the beginning, we check the date_sent because it matches with the expire status
            $matching_time = $matching_order->date_sent;

            // If not, we simply take the last update of the order, once again
            if ($matching_time == NULL) {

              $matching_time = $matching_order->updated_at->format('Y-m-d H:i:s');

            }

          }

          // We update the profile
          $profile->status_updated_at = $matching_time;

        }

      // We save everything
      $profile->save();
      $affected_rows++;

      }

    }

    session()->flash('message', "Le `status_updated_at` des abonnements on été mis à jour ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the `status` data within the `customer_profiles` table if empty
   * @return [type] [description]
   */
  public function getUpdateCustomerProfileStatus()
  {

    $profiles = CustomerProfile::get();
    $affected_rows = 0;

    foreach ($profiles as $profile) {

      if ($profile->status == NULL) {

        // There's not even a box selected, he's not subscribed at all
        if ($profile->box()->first() == NULL) {

          $profile->status = 'not-subscribed';

        // He selected a box and is in the middle of the subscription process (may have aborted it in the middle)
        } elseif (($profile->box()->first()->questions()->get() != NULL) && ($profile->orders()->first() == NULL)) {

          $profile->status = 'in-progress';

          // Last case : he's subscribed / expired
        } else {

          // There's no order left, the subscription is expired
          if ($profile->orders()->whereNull('date_sent')->count() <= 0) {

            // Sometimes we have exceptions, they are not complete customer and don't have any orders not sent
            if ($profile->stripe_customer == NULL) {

              $profile->status = 'not-subscribed';

            } else {

              $profile->status = 'expired';

            }

          // This guy is certainly subscribed and still active
          } else {

            // Sometimes we have exceptions, they are not complete customer and don't have any orders not sent
            if ($profile->stripe_customer == NULL) {

              $profile->status = 'not-subscribed';

            } else {

              $profile->status = 'subscribed';

            }

          }

        }

      $profile->save();
      $affected_rows++;

      }

    }

    session()->flash('message', "Le `status` des abonnements on été mis à jour ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the `customer_profile_id` data within the `customer_order_buildings` table if empty
   * @return void
   */
  public function getUpdateLinkCustomerProfilesAndCustomerOrderBuilding()
  {

    $customer_order_buildings = CustomerOrderBuilding::whereNull('customer_profile_id')->get();
    $affected_rows = 0;

    foreach ($customer_order_buildings as $customer_order_building) {

        // We take the first we don't have any other solution
        
        $customer = $customer_order_building->customer()->first();

        if ($customer != NULL) {

          $customer_order_building->customer_profile_id = $customer->profiles()->first()->id;
          $customer_order_building->save();

          $affected_rows++;

        }

    }

    session()->flash('message', "Les profils non terminés ne possédant pas de `customer_profile` ont été reliés ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the `order_id` data within the `payments` table if empty
   * @return void
   */
  public function getInheritPaymentsOrderIdViaOrders()
  {

    $orders = Order::get();
    $affected_rows = 0;

    // We check for every order
    foreach ($orders as $order) {

      // If there's a payment linked
      if ($order->payment_id != NULL) {

        // We find it
        $payment = Payment::find($order->payment_id);

        // We associate it if needed
        if ($payment->order_id == NULL) {

          $payment->order_id = $order->id;
          $payment->save();

          $affected_rows++;

        }

      }

    }

    session()->flash('message', "Les liaisons ont été créées dans le système de paiement ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  public function getRetrieveLast4InCustomerPaymentProfilesWithStripe()
  {

    $customer_payment_profiles = CustomerPaymentProfile::get();

    $affected_rows = 0;

    foreach ($customer_payment_profiles as $payment_profile) {

      if (empty($payment_profile->last4)) {

        $stripe_customer_id = $payment_profile->stripe_customer;
        $stripe_card_id = $payment_profile->stripe_card;

        $stripe_last4 = Payments::getLast4FromCard($stripe_customer_id, $stripe_card_id);

        if ($stripe_last4 == FALSE) $payment_profile->last4 = '';
        else $payment_profile->last4 = $stripe_last4;

        $payment_profile->save();

        $affected_rows++;

      }

    }

    session()->flash('message', "Le `stripe_plan` dans `payment_profile_payments` a été peuplé via Stripe ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  public function getRetrievePlanIdInCustomerPaymentProfilesWithStripe()
  {

    $customer_payment_profiles = CustomerPaymentProfile::get();

    $affected_rows = 0;

    foreach ($customer_payment_profiles as $payment_profile) {

      if (empty($payment_profile->stripe_plan)) {

        $stripe_subscription_id = $payment_profile->stripe_subscription;
        $stripe_customer_id = $payment_profile->stripe_customer;

        $stripe_plan = Payments::getPlanFromSubscription($stripe_customer_id, $stripe_subscription_id);

        if ($stripe_plan == FALSE) $payment_profile->stripe_plan = '';
        else $payment_profile->stripe_plan = $stripe_plan->id;

        $payment_profile->save();

        $affected_rows++;

      }

    }

    session()->flash('message', "Le `stripe_plan` dans `payment_profile_payments` a été peuplé via Stripe ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Generate the `slug` of the `customer_answers` if empty
   * @return void
   */
  public function getGenerateCustomerAnswersSlugs()
  {

    $customer_answers = CustomerAnswer::get();
    $affected_rows = 0;

    foreach ($customer_answers as $customer_answer) {

      if (empty($customer_answer->slug)) {

        $customer_answer->slug = Str::slug($customer_answer->answer);
        $customer_answer->save();

        $affected_rows++;

      }

    }

    session()->flash('message', "Le `slug` des `customer_answers` a été généré si nécessaire ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the `frequency` data within the `customer_order_preferences` table if 0 to NULL
   * @return void
   */
  public function getConvertCustomerOrderPreferencesZeroToNull()
  {

    $customer_order_preferences = CustomerOrderPreference::get();
    $affected_rows = 0;

    foreach ($customer_order_preferences as $customer_order_preference) {

      if ($customer_order_preference->frequency == 0) {

        $customer_order_preference->frequency = NULL;
        $customer_order_preference->save();

      }

    }

    session()->flash('message', "Les profils non terminés ne possédant pas de série dédiée ont été reliées ($affected_rows entrées affectées)");
    return redirect()->back();

  }

  /**
   * Update the `delivery_serie_id` data within the `customer_order_buildings` table if empty
   * @return void
   */
  public function getUpdateLinkSeriesAndCustomerOrderBuilding()
  {
    $series = DeliverySerie::orderBy('delivery', 'asc')->get();
    $affected_rows = 0;

    foreach ($series as $serie) {

      // We look for the time it was delivered (100% accurate, but close enough) -> look at the closed, or look at the delivery if we don't have the data
      if ($serie->closed == NULL) $serie_date = $serie->delivery;
      else $serie_date = $serie->closed;

      $customer_order_buildings = CustomerOrderBuilding::where('created_at', '<=', $serie_date)->whereNull('delivery_serie_id')->get();
      $affected_rows += $customer_order_buildings->count();

      // For each one of those unbuilt profile we will assign a serie (because there weren't any delivery serie id before)
      foreach ($customer_order_buildings as $customer_order_building) {

        $customer_order_building->delivery_serie_id = $serie->id;
        $customer_order_building->save();

      }

    }

    session()->flash('message', "Les profils non terminés ne possédant pas de série dédiée ont été reliées ($affected_rows entrées affectées)");
    return redirect()->back();

  }

}