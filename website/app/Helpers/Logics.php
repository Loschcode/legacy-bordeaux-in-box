<?php

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

  $delivery_serie = DeliverySerie::where('delivery', '>', $last_delivery_serie->delivery)->whereNull('closed')->orderBy('delivery', 'asc')->first();

  // We make the order
  $order = new Order;
  $order->user()->associate($customer);
  $order->customer_profile()->associate($profile);
  $order->delivery_serie()->associate($delivery_serie);
  $order->box()->associate($last_order->box()->first());

  // We don't lock the new orders
  $order->locked = FALSE;

  // If there's a spot (take away only)
  if ($delivery_spot !== NULL) $order->delivery_spot()->associate($delivery_spot);

  $order->status = 'scheduled';
  $order->gift = $last_order->gift;
  $order->take_away = $last_order->take_away;
  $order->unity_and_fees_price = $last_order->unity_and_fees_price;
  $order->save();

  // We make the order billing
  $order_billing = new OrderBilling;
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
    $order_destination = new OrderDestination;
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

  $arr_check = Config::get('bdxnbx.no_answer_question_type');
  
  if (in_array($type, $arr_check)) return TRUE;
  else return FALSE;

}
