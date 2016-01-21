<?php

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
    $company_billing->city = $billing->city;
    $company_billing->address = $billing->address;
    $company_billing->zip = $billing->zip;

  }

  $company_billing->save();

  if ($associate) {

    $order->company_billing_id = $company_billing->id;
    $order->save();

  }

  return $company_billing;

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

  $delivery_spot = $last_order->delivery_spot()->first();

  $delivery_serie = \App\Models\DeliverySerie::where('delivery', '>', $last_delivery_serie->delivery)->whereNull('closed')->orderBy('delivery', 'asc')->first();

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
  $order->gift = $last_order->gift;
  $order->take_away = $last_order->take_away;
  $order->unity_and_fees_price = $last_order->unity_and_fees_price;
  
  /**
   * We don't forget to guess the delivery fees / unity price of the order
   */
  $order->delivery_fees = $last_order->delivery_fees;
  $order->unity_price = $last_order->unity_price;

  $order->save();

  // We make the order billing
  $order_billing = new \App\Models\OrderBilling;
  $order_billing->order()->associate($order);
  $order_billing->first_name = $customer->first_name;
  $order_billing->last_name = $customer->last_name;
  $order_billing->city = $customer->city;
  $order_billing->address = $customer->address;
  $order_billing->zip = $customer->zip;
  $order_billing->save();

  $last_order_destination = $last_order->destination()->first();

  if ($last_order_destination != NULL) {

    // We make the order destination
    $order_destination = new \App\Models\OrderDestination;
    $order_destination->order()->associate($order);
    $order_destination->first_name = $last_order_destination->first_name;
    $order_destination->last_name = $last_order_destination->last_name;
    $order_destination->city = $last_order_destination->city;
    $order_destination->address = $last_order_destination->address;
    $order_destination->zip = $last_order_destination->zip;
    $order_destination->save();

  }

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
