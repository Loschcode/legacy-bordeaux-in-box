<?php

use App\Models\EmailTrace;

function warning_tech_admin($template, $subject, $customer, $customer_profile=NULL, $payment=NULL, $log_store=NULL) {

  // Communication recipient will receive the failure
  $email = 'laurent@bordeauxinbox.com'; //App\Models\ContactSetting::first()->tech_support;

  $data = [

  'customer_email' => $customer->email,
  'customer_full_name' => $customer->getFullName(),
  'customer_id' => $customer->id

  ];

  if ($customer_profile !== NULL) {

    $data['customer_profile_id'] = $customer_profile->id;

  }

  if ($payment !== NULL)
    $data['payment_id'] = $payment->id;
  else
    $data['payment_id'] = 'N/A';

  if ($log_store !== NULL)
    $data['log_store'] = $log_store;
  else
    $data['log_store'] = 'N/A';

  Mail::queue($template, $data, function($message) use ($email, $subject)
  {

    $message->from($email)->to($email)->subject('WARNING : ' . $subject);

  });

}

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_orders($orders) {

  $email_already_used = [];

  foreach ($orders as $order) {

    $profile = $order->customer_profile()->first();

    if (($profile != NULL) && (!in_array($profile->customer()->first()->email, $email_already_used))) {

      array_push($email_already_used, $profile->customer()->first()->email);

    }

  }

  return $email_already_used;

}

/**
 * Get email listing from all customers
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_all_customers() {

  return App\Models\Customer::lists('email');

}

/**
 * @return array
 */
function get_email_listing_from_customers_having_a_profile_subscribed()
{

  return App\Models\Customer::whereHas('profiles', function($q) {
    $q->where('status', 'subscribed');
  })->lists('email');

}

/**
 * @return array
 */
function get_email_listing_from_customers_who_never_bought_a_box()
{
  $emails = App\Models\Customer::whereDoesntHave('profiles')
    ->orWhereHas('profiles', function($q) {
      $q
        ->where('status', '!=', 'subscribed')
        ->where('status', '!=', 'canceled')
        ->where('status', '!=', 'expired');
    })
    ->lists('email');

  return $emails;
}

/**
 * @return array
 */
function get_email_listing_from_customers_who_bought_a_box_but_stop()
{

  $emails = [];

  // Loop every customers
  foreach (App\Models\Customer::get() as $customer) {

    // Has profile
    if ($customer->profiles()->first() !== NULL) {

      $has_expired = FALSE;
      $has_subscribed = FALSE;

      // We try to find if the customer has a profile expired and has not a profile
      // subscribed
      foreach ($customer->profiles()->get() as $profile) {

        if ($profile->status == 'expired') {
          $has_expired = TRUE;
        }

        if ($profile->status == 'subscribed') {
          $has_subscribed = TRUE;
        }

      }

      if ($has_expired === TRUE && $has_subscribed === FALSE) {
        $emails[] = $customer->email;
      }

    }

  }

  return $emails;

}

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_unfinished_profiles($series) {

  $email_already_used = [];

  foreach ($series->customer_order_buildings()->get() as $customer_order_building) {

    $profile = $customer_order_building->profile()->first();

    if (($profile != NULL) && (!in_array($profile->customer()->first()->email, $email_already_used))) {

      array_push($email_already_used, $profile->customer()->first()->email);

    }

  }

  return $email_already_used;

}

function mailing_send($profile, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

  // We resolve everything
  $customer = $profile->customer()->first();
  $email = $customer->email;

  /**
   * In case we can't send an email
   */
  if ($customer->emails_allowed === FALSE)
    return FALSE;

  $template_data = array_merge($template_data, [

    'email' => $email,
    'customer' => $customer,
    'profile' => $profile,
    'subject' => $subject,
    'template' => $template

    ]);

  $datas = [

    'email' => $email,
    'customer' => $customer,
    'profile' => $profile,
    'subject' => $subject,
    'template' => $template,
    'template_data' => $template_data,
    'additional_mailgun_variables' => $additional_mailgun_variables,

  ];

  send_email_with_trace($datas);

}

function mailing_send_customer_only($customer, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

  $email = $customer->email;

  /**
   * In case we can't send an email
   */
  if ($customer->emails_allowed === FALSE)
    return FALSE;

  $template_data = array_merge($template_data, [

    'email' => $email,
    'customer' => $customer,
    'subject' => $subject,
    'template' => $template

    ]);

  $datas = [

    'email' => $email,
    'customer' => $customer,
    'subject' => $subject,
    'template' => $template,
    'template_data' => $template_data,
    'additional_mailgun_variables' => $additional_mailgun_variables,

  ];

  send_email_with_trace($datas);

}

function send_email_with_trace($datas) {

  if (isset($datas['email'])) $email = $datas['email']; else $email = NULL;
  if (isset($datas['customer'])) $customer = $datas['customer']; else $customer = NULL;
  if (isset($datas['profile'])) $profile = $datas['profile']; else $profile = NULL;
  if (isset($datas['subject'])) $subject = $datas['subject']; else $subject = NULL;
  if (isset($datas['template'])) $template = $datas['template']; else $template = NULL;
  if (isset($datas['template_data'])) $template_data = $datas['template_data']; else $template_data = NULL;
  if (isset($datas['additional_mailgun_variables'])) $additional_mailgun_variables = $datas['additional_mailgun_variables']; else $additional_mailgun_variables = NULL;

  // We resolve the body for the email trace logs
  $body_preparation = View::make($template, $template_data);
  $body = $body_preparation->render();

  // We will queue the email (we could add a protection here)
  Mail::queue($template, $template_data, function($message) use ($email, $subject, $body, $customer, $profile, $additional_mailgun_variables)
  {

    // We prepare the email trace
    $email_trace = new EmailTrace;
    $email_trace->recipient = $email;
    $email_trace->subject = $subject;

    if ($customer !== NULL) $email_trace->customer_id = $customer->id;
    if ($profile !== NULL) $email_trace->customer_profile_id = $profile->id;
    
    $email_trace->prepared_at = date('Y-m-d H:i:s');

    if ($profile !== NULL) $profile_id = $profile->id; else $profile_id = NULL;
    if ($customer !== NULL) $customer_id = $customer->id; else $profile_id = NULL;

    $email_trace->content = $body;
    $email_trace->save();

    // We prepare the MailGun variables
    $mailgun_variables = [

      'customer_id' => (int) $customer_id,
      'profile_id' => (int) $profile_id,
      'email_trace_id' => (int) $email_trace->id,

    ];

    // Is there any additional variable ?
    if ($additional_mailgun_variables !== NULL) $mailgun_variables += $additional_mailgun_variables;

    // We encode it
    $encoded_mailgun_variables = json_encode($mailgun_variables);

    // We finally send the email with all the correct headers
    $message->to($email)->subject($subject);
    $message->getHeaders()->addTextHeader('X-Mailgun-Variables', $encoded_mailgun_variables);
    
  });

}