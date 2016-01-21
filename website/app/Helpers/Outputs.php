<?php

use App\Libraries\Downloaders;

function generate_contract_id($branch='MBX', $customer) {

  return $branch . $customer->created_at->format('ymd') . 'CON' . $customer->id . 'R' . rand(0,999);

}

function generate_bill_id($branch='MBX', $customer, $order=NULL) {

  if ($order === NULL)
    return $branch . $customer->created_at->format('ymd') . 'BIL' . $customer->id . 'R' . rand(0,999);
  else
    return $branch . $order->created_at->format('ymd') . 'BIL' . $customer->id . 'O' . $order->id;

}

function retrieve_customer_id($customer) {

  return 'BDNBX' . $customer->created_at->format('ymd') . 'CUS' . $customer->id;

}

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
function generate_pdf_bill($company_billing, $download=FALSE, $destination_folder=FALSE) {

  $company_billing_lines = $company_billing->billing_lines()->get();
  $total = $company_billing_lines->sum('amount');

  $html = view('masterbox.pdf.bill')->with(compact(
    'company_billing',
    'company_billing_lines',
    'total'
  ));

  $pdf_name = $company_billing->bill_id . '.pdf';

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
 * Generate a CSV for the orders
 * @param  string $file_name the file name
 * @param  object $orders    the Order object
 * @return csv  
 */
function generate_csv_finances_spreadsheet($file_name, $payments, $only_fees=FALSE)
{

  // We make up the titles
  $output[0] = [

    'Date facture',
    'Date réglement',
    'Montant',
    'Type gain',

    '',

    'Méthode',
    'Créditeur',
    'Type créditeur',
    'Statut',
    'Facture',

    '',

    'Branche',
    'Gestion',
    'Note'

  ];

  foreach ($payments as $payment) {

    // We prepare some stuff
    $profile = $payment->profile()->first();
    $customer = $profile->customer()->first();
    $email = $customer->email;

    $order = $payment->order()->first();
    if ($order !== NULL) {
      $delivery_serie = $order->delivery_serie()->first();
    } else {
      $delivery_serie = NULL;
    }

    // Now we get the datas
    $date_facture = date_format($payment->created_at,"d/m/Y");
    $date_reglement = date_format($payment->created_at,"d/m/Y");

    // Refund or not ?
    if ($payment->amount >= 0) {

      if ($only_fees) {

        $amount = $payment->fees;
        $note = "Frais de transfert Stripe pour le paiement de ".Downloaders::prepareForCsv($customer->getFullName());
        $type_gain = "Frais de transaction";

      } else {

        $amount = $payment->amount;
        $note = "Vente sur le site à ".Downloaders::prepareForCsv($customer->getFullName());
        $type_gain = "Vente de boxes";

      }

      $statut = "Payé";

    } else {

      if ($only_fees) {

        $amount = ($payment->fees) - ($payment->fees*2);
        $note = "Remboursement des frais de transfert Stripe pour le paiement de ".Downloaders::prepareForCsv($customer->getFullName());
        $type_gain = "Remboursement de frais de transaction";

      } else {

        $amount = ($payment->amount) - ($payment->amount*2);
        $note = "Remboursement sur le site à ".Downloaders::prepareForCsv($customer->getFullName());
        $type_gain = "Remboursement client";

      }

      $statut = "Payé";

    }

    if ($delivery_serie !== NULL) {
      $note .= " pour la série ".$delivery_serie->delivery;
    }

    //setlocale(LC_MONETARY, 'fr_FR');
    $montant = str_replace('.', '%coma%', $amount); //money_format('%i', $payment->amount);

    $methode = "Carte bancaire"; // This not included any CHECK

    if ($only_fees) {

      $crediteur = "Stripe Ltd";
      $type_crediteur = "Professionnel";
      $facture = "N/A";

    } else {

      $crediteur = retrieve_customer_id($customer);
      $type_crediteur = "Personnel";
      $facture = "\"=HYPERLINK(\"\"https://www.bordeauxinbox.fr/v1/archive/public-bill/".$payment->bill_id."\"\"%coma% \"\"".$payment->bill_id."\"\")\"";

    }

    $branche = "Boxes principales";
    $gestion = "Laurent Schaffner";

    // Downloaders::prepareForCsv($customer->getFullName());

    $output[] = [

      $date_facture,
      $date_reglement,
      $montant,
      $type_gain,
      ' ',
      $methode,
      $crediteur,
      $type_crediteur,
      $statut,
      $facture,
      ' ',
      $branche,
      $gestion,
      $note

    ];

  }

  return Downloaders::makeCsvFromArray($file_name, $output);

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
    $customer = $profile->customer()->first();

    $email = $customer->email;
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

      Downloaders::prepareForCsv($customer->getFullName()),
      $profile->id,
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
    $profile = $order->customer_profile()->first();
    $customer = $profile->customer()->first();

    $email = $customer->email;

    $questions = Downloaders::prepareForCsv(order_questions($profile, " / "));
    $answers = Downloaders::prepareForCsv(order_answers($profile, " / "));

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

      Downloaders::prepareForCsv($customer->getFullName()),
      Downloaders::prepareForCsv($customer->phone),
      Downloaders::prepareForCsv($email),
      $order_spot_or_destination,
      $order->created_at->toDateTimeString()

      ];

    } else {

      $output[] = [

      $order->id, 
      $order->delivery_serie()->first()->delivery,
      Downloaders::prepareForCsv($customer->getFullName()),
      Downloaders::prepareForCsv($customer->getFullAddress()),
      Downloaders::prepareForCsv($customer->phone),
      Downloaders::prepareForCsv($email),
      $questions,
      $answers,
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