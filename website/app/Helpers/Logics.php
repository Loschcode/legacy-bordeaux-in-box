<?php

function is_someone_online_slack()
{

  return Cache::remember('is_someone_online_slack', 5, function() {

    $slack = new App\Libraries\Slack;

    return $slack->isSomeoneOnline();

  });

}

function prepare_log_metadata() {

  $metadata = [];
  foreach (func_get_args() as $array) {

    if (isset($array['created_at'])) unset($array['created_at']);
    if (isset($array['updated_at'])) unset($array['updated_at']);
    if (isset($array['id'])) unset($array['id']);

    foreach ($array as $label => $element) {

      if (is_bool($element)) {
        if ($element === TRUE) $array[$label] = "TRUE";
        if ($element === FALSE) $array[$label] = "FALSE";
      }

    }

    $metadata = array_merge($metadata, $array);

  }

  return $metadata;

}

/**
 * Add a customer profile log
 * @param  object $customer_profile
 * @param  string $log_message
 * @param  array  $metadata
 * @return void
 */
function customer_profile_log($customer_profile, $log_message, $metadata=[]) {

  $log = new \App\Models\CustomerProfileLog;
  $log->log = $log_message;

  if (!Auth::guard('administrator')->guest())
    $log->administrator_id = Auth::guard('administrator')->user()->id;

  $log->customer_profile_id = $customer_profile->id;
  $log->metadata = $metadata;
  $log->save();

}

/**
 * We generate a complete billing without any order
 * @return object
 */
function generate_new_company_billing_without_order($payment) {

  $customer = $payment->customer()->first();

  $company_billing = new \App\Models\CompanyBilling;
  $company_billing->branch = 'masterbox';
  $company_billing->customer_id = retrieve_customer_id($customer);
  $company_billing->contract_id = generate_contract_id('MBX', $customer);
  $company_billing->bill_id = generate_bill_id('MBX', $customer);
  $company_billing->encrypted_access = Crypt::encrypt($company_billing->branch.$company_billing->customer_id.$company_billing->contract_id.$company_billing->bill_id);

  $company_billing->title = 'Box principale';
  $company_billing->save();

  return $company_billing;

}

/**
 * We generate a complete billing from an order
 * @param  object $order the order model object
 * @return object company billing object
 */
function generate_new_company_billing_from_order($order, $associate=TRUE) {

  // If it already exists
  if ($order->company_billing()->first() !== NULL)
    return FALSE;

  $customer = $order->customer()->first();
  $billing = $order->billing()->first();

  $company_billing = new \App\Models\CompanyBilling;
  $company_billing->branch = 'masterbox';
  $company_billing->customer_id = retrieve_customer_id($customer);
  $company_billing->contract_id = generate_contract_id('MBX', $customer);
  $company_billing->bill_id = generate_bill_id('MBX', $customer, $order);
  $company_billing->encrypted_access = Crypt::encrypt($company_billing->branch.$company_billing->customer_id.$company_billing->contract_id.$company_billing->bill_id);

  $company_billing->title = 'Box principale';

  if ($billing === NULL) {

    $company_billing->first_name = $customer->first_name;
    $company_billing->last_name = $customer->last_name;

  } else {

    $company_billing->first_name = $billing->first_name;
    $company_billing->last_name = $billing->last_name;
    $company_billing->coordinate_id = App\Models\Coordinate::getMatchingOrGenerate($billing->address, $billing->zip, $billing->city, $billing->address_detail)->id;


  }

  $company_billing->save();

  if ($associate) {

    $order->company_billing_id = $company_billing->id;
    $order->save();

  }

  return $company_billing;

}

function generate_new_delivery_serie() {

  /**
   * We get the very last serie whatever it is
   */
  $last_delivery_serie = App\Models\DeliverySerie::orderBy('id', 'desc')->first();
  $last_delivery = strtotime($last_delivery_serie->delivery);

  /**
   * We generate the serie
   */
  $delivery_serie = new App\Models\DeliverySerie;
  $delivery_serie->delivery = date("Y-m-d", strtotime("+1 month", $last_delivery));
  $delivery_serie->goal = 0;
  $delivery_serie->save();

  return $delivery_serie;

}
/**
 * Duplicate an existing order and apply the new order to the targeted series
 * @param   object $order
 * @param  object $delivery_serie
 * @return void
 */
function generate_new_order($customer, $profile) {

  $last_order = $profile->orders()->orderBy('created_at', 'desc')->orderBy('orders.id', 'desc')->first();
  $last_delivery_serie = $last_order->delivery_serie()->first();

  $order_preference = $profile->order_preference()->first();

  $delivery_spot = $last_order->delivery_spot()->first();

  try {
    
    $delivery_serie = \App\Models\DeliverySerie::where('delivery', '>', $last_delivery_serie->delivery)->whereNull('closed')->orderBy('delivery', 'asc')->first();

  } catch (Exception $e) {

    warning_tech_admin('masterbox.emails.admin.no_more_delivery_serie_to_generate_order', 'Plus assez de séries pour générer des commandes', $customer, $profile);
    
    $delivery_serie = generate_new_delivery_serie();

  }

  // We make the order
  $order = new \App\Models\Order;
  $order->customer()->associate($customer);
  $order->customer_profile()->associate($profile);
  $order->delivery_serie()->associate($delivery_serie);

  // We don't lock the new orders
  $order->locked = FALSE;

  // If there's a spot (take away only)
  if ($delivery_spot !== NULL) $order->delivery_spot()->associate($delivery_spot);

  $order->status = 'scheduled';
  $order->gift = $order_preference->gift;
  $order->take_away = $order_preference->take_away;

  /**
   * We don't forget to guess the delivery fees / unity price of the order
   */
  if ($order->gift) {

    $order->unity_and_fees_price = $order_preference->totalPricePerMonth() / $order_preference->frequency;
    $order->delivery_fees = $order_preference->delivery_fees / $order_preference->frequency;
    $order->unity_price = $order_preference->unity_price / $order_preference->frequency;

  } else {

    $order->unity_and_fees_price = $order_preference->totalPricePerMonth();
    $order->delivery_fees = $order_preference->delivery_fees;
    $order->unity_price = $order_preference->unity_price;

  }

  $order->save();

  // We make the order billing
  $order_billing = new \App\Models\OrderBilling;
  $order_billing->order()->associate($order);
  $order_billing->first_name = $customer->first_name;
  $order_billing->last_name = $customer->last_name;
  $order_billing->coordinate_id = \App\Models\Coordinate::getMatchingOrGenerate($customer->address, $customer->zip, $customer->city, $customer->address_detail)->id;
  $order_billing->save();

  $last_order_destination = $last_order->destination()->first();

  if ($last_order_destination !== NULL) {

    // We make the order destination
    $order_destination = new \App\Models\OrderDestination;
    $order_destination->order()->associate($order);
    $order_destination->first_name = $last_order_destination->first_name;
    $order_destination->last_name = $last_order_destination->last_name;
    $order_destination->coordinate_id = \App\Models\Coordinate::getMatchingOrGenerate($last_order_destination->address, $last_order_destination->zip, $last_order_destination->city, $last_order_destination->address_detail)->id;
    $order_destination->save();

  }

  /**
   * Finally we generate the company billing of the order
   */
  generate_new_company_billing_from_order($order, TRUE);

}

/**
 * Check if the question type can have answers
 * @param  string  $type question type
 * @return boolean
 */
function has_no_answer_possible($type) {

  $arr_check = config('bdxnbx.no_answer_question_type');
  
  if (in_array($type, $arr_check)) return TRUE;
  else return FALSE;

}
