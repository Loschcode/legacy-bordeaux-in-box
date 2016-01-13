<?php namespace App\Http\Controllers\Company\Admin;

use App\Http\Controllers\Company\BaseController;

use App\Models\DeliverySerie;

class FinancesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | Home page system
  |
  */

  /**
   * Home page
   */
  public function getIndex()
  {

    $series = DeliverySerie::orderBy('delivery', 'asc')->get();

    return view('company.admin.finances.index')->with(compact(
      'series'
    ));

  }

  /**
   * We generate all the PDF, zip it and download it afterwards
   * @param  integer $serie_id
   * @return void
   */
  public function getBills($serie_id)
  {

    $series = DeliverySerie::findOrFail($serie_id);

    // We will list all the payments as bills
    $payments = $series->payments()->get();

    $destination_zip = 'bills/' . uniqid();

    // We generate all the PDFs
    foreach ($payments as $payment) {

        generate_pdf_bill($payment, FALSE, $destination_zip);

    }

    $zip_name = 'bills-'.$series->delivery.'-'.time();
    return generate_zip($zip_name, $destination_zip);

  }

  public function getPayments($serie_id)
  {

    $series = DeliverySerie::findOrFail($serie_id);

    // We will list all the payments as bills
    $payments = $series->payments()->get();

    $csv_name = 'payments-'.$series->delivery.'-'.time().'.csv';
    return generate_csv_payments($csv_name, $payments);

  }


  public function getFinancesSpreadsheetTotalCredits()
  {

    // We will list all the payments as bills
    $payments = Payment::where('amount', '>=', 0)->where('paid', '=', TRUE)->get();

    $csv_name = 'finances-spreadsheet-total-debits-'.time().'.csv';
    
    return generate_csv_finances_spreadsheet($csv_name, $payments);

  }

  public function getFinancesSpreadsheetTotalDebits()
  {

    // We will list all the payments as bills
    $payments = Payment::where('amount', '<', 0)->where('paid', '=', TRUE)->get();

    $csv_name = 'finances-spreadsheet-total-debits-'.time().'.csv';
    
    return generate_csv_finances_spreadsheet($csv_name, $payments);

  }

  public function getFinancesSpreadsheetCredits($serie_id)
  {

    $series = DeliverySerie::find($serie_id);
    if ($series === NULL) return Redirect::to('/');

    // We will list all the payments as bills
    $payments = $series->payments()->where('paid', '=', TRUE)->where('amount', '>=', 0)->get();

    $csv_name = 'finances-spreadsheet-credits-'.$series->delivery.'-'.time().'.csv';
    
    return generate_csv_finances_spreadsheet($csv_name, $payments);

  }

  public function getFinancesSpreadsheetDebits($serie_id)
  {

    $series = DeliverySerie::find($serie_id);
    if ($series === NULL) return Redirect::to('/');

    // We will list all the payments as bills
    $payments = $series->payments()->where('paid', '=', TRUE)->where('amount', '<', 0)->get();

    $csv_name = 'finances-spreadsheet-debits-'.$series->delivery.'-'.time().'.csv';
    
    return generate_csv_finances_spreadsheet($csv_name, $payments);

  }


}