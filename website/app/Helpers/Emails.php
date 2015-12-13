<?php

use App\Models\EmailTrace;

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_orders($orders) {

  $email_already_used = [];

  foreach ($orders as $order) {

    $profile = $order->user_profile()->first();

    if (($profile != NULL) && (!in_array($profile->user()->first()->email, $email_already_used))) {

      array_push($email_already_used, $profile->user()->first()->email);

    }

  }

  return $email_already_used;

}

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_unfinished_profiles($series) {

  $email_already_used = [];

  foreach ($series->user_order_buildings()->get() as $user_order_building) {

    $profile = $user_order_building->profile()->first();

    if (($profile != NULL) && (!in_array($profile->user()->first()->email, $email_already_used))) {

      array_push($email_already_used, $profile->user()->first()->email);

    }

  }

  return $email_already_used;

}

function mailing_send($profile, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

  // We resolve everything
  $user = $profile->user()->first();
  $email = $user->email;

  $datas = [

    'email' => $email,
    'user' => $user,
    'profile' => $profile,
    'subject' => $subject,
    'template' => $template,
    'template_data' => $template_data,
    'additional_mailgun_variables' => $additional_mailgun_variables,

  ];

  send_email_with_trace($datas);

}

function mailing_send_user_only($user, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

  $email = $user->email;

  $datas = [

    'email' => $email,
    'user' => $user,
    'subject' => $subject,
    'template' => $template,
    'template_data' => $template_data,
    'additional_mailgun_variables' => $additional_mailgun_variables,

  ];

  send_email_with_trace($datas);

}

function send_email_with_trace($datas) {

  if (isset($datas['email'])) $email = $datas['email']; else $email = NULL;
  if (isset($datas['user'])) $user = $datas['user']; else $user = NULL;
  if (isset($datas['profile'])) $profile = $datas['profile']; else $profile = NULL;
  if (isset($datas['subject'])) $subject = $datas['subject']; else $subject = NULL;
  if (isset($datas['template'])) $template = $datas['template']; else $template = NULL;
  if (isset($datas['template_data'])) $template_data = $datas['template_data']; else $template_data = NULL;
  if (isset($datas['additional_mailgun_variables'])) $additional_mailgun_variables = $datas['additional_mailgun_variables']; else $additional_mailgun_variables = NULL;

  // We resolve the body for the email trace logs
  $body_preparation = View::make($template, $template_data);
  $body = $body_preparation->render();

  // We will queue the email (we could add a protection here)
  Mail::queue($template, $template_data, function($message) use ($email, $subject, $body, $user, $profile, $additional_mailgun_variables)
  {

    // We prepare the email trace
    $email_trace = new EmailTrace;
    $email_trace->recipient = $email;
    $email_trace->subject = $subject;

    if ($user !== NULL) $email_trace->user_id = $user->id;
    if ($profile !== NULL) $email_trace->user_profile_id = $profile->id;
    
    $email_trace->prepared_at = date('Y-m-d H:i:s');

    if ($profile !== NULL) $profile_id = $profile->id; else $profile_id = NULL;
    if ($user !== NULL) $user_id = $user->id; else $profile_id = NULL;

    $email_trace->content = $body;
    $email_trace->save();

    // We prepare the MailGun variables
    $mailgun_variables = [

      'user_id' => (int) $user_id,
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