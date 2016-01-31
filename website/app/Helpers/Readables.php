<?php

/**
 * Get the slug from the box question type
 * @param  string $slug e.g. tech-idea, tech-bug
 * @return string
 */
function readable_question_type($slug) {

  $arr = Config::get('bdxnbx.question_types');

  if (isset($arr[$slug])) return $arr[$slug];
  else return 'Inconnu';

}

/**
 * Get the slug from the service contact and output a readable label
 * @param  string $slug e.g. tech-idea, tech-bug
 * @return string
 */
function readable_contact_service($slug) {

  $arr = Config::get('bdxnbx.contact_service');

  if (strpos($slug, 'com-') !== FALSE) {

    $arr = $arr['Commercial'];

  } elseif (strpos($slug, 'tech-') !== FALSE) {

    $arr = $arr['Technique'];

  }

  if (isset($arr[$slug])) return $arr[$slug];
  else return 'Inconnu';

}

function readable_payment_type($type) {

  if ($type == 'plan') return 'Abonnement';
  elseif ($type == 'direct_invoice') return 'Transfert unique';
  else return 'Inconnu';
  
}

function readable_payment_status($status) {

  if ($status) return 'Succès';
  else return 'Echec';

}

function readable_profile_priority($priority) {

  if ($priority === 'high') return 'Elevée';
  elseif ($priority === 'medium') return 'Normale';
  elseif ($priority === 'low') return 'Basse';
  else return 'N/A';

}

function readable_order_status($status) {

  if ($status == 'paid') return 'Payé';
  elseif ($status == 'unpaid') return 'Non payé';
  elseif ($status == 'scheduled') return 'Planifié';
  elseif ($status == 'failed') return 'Echec';
  elseif ($status == 'delivered') return 'Envoyé';
  elseif ($status == 'half-paid') return 'Partiellement payé';
  elseif ($status == 'packing') return 'En préparation';
  elseif ($status == 'ready') return 'Prêt pour envoi';
  elseif ($status == 'problem') return 'Problème';
  elseif ($status == 'canceled') return 'Annulé';

}

/**
 * Translate the customer role
 * @param  string $role The role (ex. user, admin)
 * @return string
 */
function readable_customer_role($role)
{
  switch ($role) {
    case 'admin':
      return 'Administrateur';
    break;

    case 'user':
      return 'Client';
    break;

    default:
      return $role;
    break;
  }
}

/**
 * Prettify the phone format
 *
 * @return string
 */
function readable_customer_phone($phone)
{
  $phone = trim($phone);

  $formatPhone = str_replace('.', '', $phone);
  $formatPhone = str_replace(' ', '', $formatPhone);
  $formatPhone = str_replace('+330', '0', $formatPhone);
  $formatPhone = str_replace('+33', '0', $formatPhone);

  // Ok it's well formated now, we can split the numbers
  // for a better display. Else we let the phone as is.
  if (strlen($formatPhone) === 10) {
    $formatPhone = join('.', str_split($formatPhone, 2));
    return $formatPhone;
  }

  return $phone;
}