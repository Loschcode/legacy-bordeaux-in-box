<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Carbon\Carbon; 

use App\Models\DeliverySerie;

class TaxesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Taxes Controller
  |--------------------------------------------------------------------------
  |
  | All about the taxes
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {

      $this->beforeMethod();
      $this->middleware('isAdmin');

  }
    

  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {

    $series = DeliverySerie::orderBy('delivery', 'asc')->get();

    return view('master-box.admin.taxes.index')->with(compact(
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

}