<?php

/**
 * Get the readable role of a user
 * @param  string $role
 * @return string
 */
function readable_role($role) {

  if ($role === 'admin') return 'Administrateur';
  elseif ($role === 'user') return 'Utilisateur';
  else return $role;

}

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