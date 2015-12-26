<?php

function generate_zip($name, $folder) {

  $zip_file = 'uploads/' . $name . '.zip';

  // We zip the folder itself
  $files = glob(public_path('uploads/' . $folder));

  Zipper::make($zip_file)->add($files);

  return redirect()->to($zip_file);

}

/**
 * Generate a PDF for the bills (linked to payments)
 */
function generate_pdf_bill($payment, $download=FALSE, $destination_folder=FALSE) {

  $user = $payment->user()->first();
  $profile = $payment->profile()->first();
  $user_order_preference = $profile->order_preference()->first();

  $order = $payment->order()->first();

  $box = $profile->box()->first();

  // In case the payment doesn't match any order in peculiar
  // So we will address to the user directly
  if ($order == NULL) {

    $billing = NULL;

  } else {

    $billing = $order->billing()->first();

  }

  $html = view('pdf.bill')->with(compact(
    'user',
    'user_order_preference',
    'box',
    'order',
    'billing',
    'payment',
    'profile'
  ));

  $pdf_name = $payment->bill_id . '.pdf';

  return generate_pdf($html, $pdf_name, $download, $destination_folder);

}

/**
 * Generate a PDF
 * @param  string $pdf_name           the pdf name at the end
 * @param   string the HTML view which will be used to generate the PDF
 * @param  boolean $download           will we download the pdf ? Or just show it in the browser ?
 * @param  string $destination_folder will we save it into the server ? If yes, here the destination folder
 * @return mixed 
 */
function generate_pdf($html, $pdf_name, $download, $destination_folder) {

  $pdf = App::make('dompdf.wrapper');

  if ($destination_folder) {

    $destinationPath = public_path('uploads/' . $destination_folder);
    make_folder($destinationPath);

    $outputName = $pdf_name;
    $pdfPath = $destinationPath . '/' . $outputName;

    File::put($pdfPath, $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->output());

  } else {

    if ($download) {

      return $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->download($pdf_name);

    } else {
      
      return $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->stream();

    } 

  }

}

/**
 * Generate a CSV for the payments
 * @param  string $file_name the file name
 * @param  object $payments the payment object
 * @return csv  
 */
function generate_csv_payments($file_name, $payments)
{

  $output[0] = [

    'ID',
    'Stripe Customer',
    'Stripe Event',
    'Stripe Charge',
    'Stripe Card',
    'Utilisateur',
    'ID Abonnement',
    'Box',
    'Série',
    'Type',
    'Montant',
    'Statut',
    'Derniers chiffres de carte',
    'Date'

  ];

  foreach ($payments as $payment) {

    // We prepare some stuff
    $profile = $payment->profile()->first();
    $user = $profile->user()->first();
    $box = $profile->box()->first();

    $email = $user->email;

    if ($box == NULL) $box_title = 'Non renseigné';
    else $box_title = $box->title;

    $amount = $payment->amount;

    if ($payment->order()->first() != NULL) {

      $serie = $payment->order()->first()->delivery_serie()->first()->delivery;

    } else {

      $serie = 'N/A';

    }

    $output[] = [

      $payment->id,
      $payment->stripe_customer,
      $payment->stripe_event,
      $payment->stripe_charge,
      $payment->stripe_card,

      Downloaders::prepareForCsv($user->getFullName()),
      $profile->id,
      Downloaders::prepareForCsv($box_title),
      $serie,
      readable_payment_type($payment->type),
      $payment->amount,
      readable_payment_status($payment->paid),
      $payment->last4,
      $payment->created_at->toDateTimeString()

      ];

  }

  return Downloaders::makeCsvFromArray($file_name, $output);

}

/**
 * Generate a CSV for the orders
 * @param  string $file_name the file name
 * @param  object $orders    the Order object
 * @return csv  
 */
function generate_csv_order($file_name, $orders, $short=false)
{

  if ($short) {

  // We make up the titles
    $output[0] = [

    'Utilisateur',
    'Téléphone utilisateur',
    'Email utilisateur',
    'Abonnement',
    'Destination / Spot',
    'Création'

    ];

  } else {

    // We make up the titles
    $output[0] = [

    'ID',
    'Série',
    'Utilisateur',
    'Adresse utilisateur',
    'Téléphone utilisateur',
    'Email utilisateur',
    'Abonnement',
    'Questions',
    'Réponses',
    'Paiement',
    'A offrir',
    'Etat de la commande',
    'Mode',
    'Destination / Spot',
    'Création',
    'Statut de la commande'

    ];

  }

  foreach ($orders as $order) {

    // We prepare some stuff
    $profile = $order->user_profile()->first();
    $user = $profile->user()->first();
    $box = $profile->box()->first();

    $email = $user->email;

    if ($box == NULL) $box_title = 'Non renseigné';
    else $box_title = $box->title;

    if ($box == NULL) $box_questions = 'Pas de question';
    else $box_questions = Downloaders::prepareForCsv(order_questions($box, $profile, " / "));

    if ($box == NULL) $box_answers = 'Pas de réponse';
    else $box_answers = Downloaders::prepareForCsv(order_answers($box, $profile, " / "));

    $paid = $order->already_paid." / ".$order->unity_and_fees_price;

    if ($order->gift) $order_gift == 'A offrir';
    else $order_gift = 'Pas à offrir';

    if ($order->locked) $order_locked = 'Commande bloquée';
    else $order_locked = 'Commande non bloquée';

    if ($order->take_away) $order_take_away = 'A emporter';
    else $order_take_away = 'En livraison';

    $order_spot_or_destination = Downloaders::prepareForCsv(order_spot_or_destination($order));
    $order_status = Downloaders::prepareForCsv(readable_order_status($order->status));

    if ($short) {

    $output[] = [

      Downloaders::prepareForCsv($user->getFullName()),
      Downloaders::prepareForCsv($user->phone),
      Downloaders::prepareForCsv($email),
      $box_title,
      $order_spot_or_destination,
      $order->created_at->toDateTimeString()

      ];

    } else {

      $output[] = [

      $order->id, 
      $order->delivery_serie()->first()->delivery,
      Downloaders::prepareForCsv($user->getFullName()),
      Downloaders::prepareForCsv($user->getFullAddress()),
      Downloaders::prepareForCsv($user->phone),
      Downloaders::prepareForCsv($email),
      $box_title,
      $box_questions,
      $box_answers,
      $paid,
      $order_gift,
      $order_locked,
      $order_take_away,
      $order_spot_or_destination,
      $order->created_at->toDateTimeString(),
      $order_status


      ];

    }

  }

  return Downloaders::makeCsvFromArray($file_name, $output);

}