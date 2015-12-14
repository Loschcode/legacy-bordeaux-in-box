<?php

/**
 * We output the questions and answers in HTML (for the admin dashboard orders reading)
 */
Html::macro('getReadableProductSize', function($size)
{

  $product_sizes_list = Config::get('bdxnbx.product_sizes');
  return $product_sizes_list[$size];

});

Html::macro('getReadableProfilePriority', function($priority)
{

  return readable_profile_priority($priority);

});

/**
 * If the value is empty we return N/A
 */
Html::macro('getReadableEmpty', function($value, $empty='N/A')
{
  if (empty($value))
  {
    return $empty;
  }
  else
  {
    return $value;
  }
  
});


/**
 * We get readable boolean status (active = true / false)
 */
Html::macro('getReadableActive', function($active)
{

  if ($active) return 'Activé';
  else return 'Désactivé';

});

/**
 * Get a readable version of the service involved
 */
Html::macro('getReadableContactService', function($slug)
{

  return readable_contact_service($slug);

});

/**
 * Get a readable order status
 */
Html::macro('getReadableOrderStatus', function($status)
{

  return readable_order_status($status);

});

/**
 * Get a readable payment type
 */
Html::macro('getReadablePaymentType', function($type)
{

  return readable_payment_type($type);

});

/**
 * Get a readable payment status
 */
Html::macro('getReadablePaymentStatus', function($status)
{

  return readable_payment_status($status);

});

/**
 * Get a readable take away (yes or not)
 */
Html::macro('getReadableTakeAway', function($take_away)
{

  if ($take_away) return 'A emporter';
  else return 'En livraison';

});

/**
 * We get readable locked for orders
 */
Html::macro('getReadableOrderLocked', function($bool)
{

  if ($bool) return 'Bloqué';
  else return 'Editable';

});

/**
 * Get a readable question type involved
 */
Html::macro('getReadableQuestionType', function($slug)
{

  return readable_question_type($slug);

});

/**
 * Get a readable role for the users
 */
Html::macro('getReadableRole', function($role)
{

  return readable_role($role);

});

/**
 * Get a readable profile status
 */
Html::macro('getReadableProfileStatus', function($status)
{

  if ($status === 'subscribed') return 'Abonné';
  elseif ($status === 'not-subscribed') return 'Non abonné';
  elseif ($status === 'in-progress') return 'En création';
  elseif ($status == 'expired') return 'Expiré';
  else return $status;

});

/**
 * Say yes or no (true / false)
 */
Html::macro('boolYesOrNo', function($bool)
{

  if ($bool) return 'Oui';
  else return 'Non';

});

/**
 * Get a readable month from a date
 */
Html::macro('convertMonth', function($date)
{

  $timestamp = strtotime($date);
  $month = date('m', $timestamp);

  if ($month == '1') return 'Janvier';
  if ($month == '2') return 'Février';
  if ($month == '3') return 'Mars';
  if ($month == '4') return 'Avril';
  if ($month == '5') return 'Mai';
  if ($month == '6') return 'Juin';
  if ($month == '7') return 'Juillet';
  if ($month == '8') return 'Aout';
  if ($month == '9') return 'Septembre';
  if ($month == '10') return 'Octobre';
  if ($month == '11') return 'Novembre';
  if ($month == '12') return 'Décembre';

});

/**
 * Get html class color from profile status
 */
Html::macro('getColorFromProfileStatus', function($status)
{

  if ($status === 'subscribed') return 'spyro-btn-primary';
  elseif ($status === 'not-subscribed') return 'spyro-btn-default';
  elseif ($status === 'in-progress') return 'spyro-btn-success';
  elseif ($status == 'expired') return 'spyro-btn-danger';
  else return '';

});
