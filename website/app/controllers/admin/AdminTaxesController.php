<?php

use Carbon\Carbon; 

class AdminTaxesController extends BaseController {

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
      $this->beforeFilter('isAdmin');

  }
    
  /**
   * The layout that should be used for responses.
   */
  protected $layout = 'layouts.admin';

  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {

    $series = DeliverySerie::orderBy('delivery', 'asc')->get();
    View::share('series', $series);

    $this->layout->content = View::make('admin.taxes.index');

  }

  /**
   * We generate all the PDF, zip it and download it afterwards
   * @param  integer $serie_id
   * @return void
   */
  public function getBills($serie_id)
  {

    $series = DeliverySerie::find($serie_id);
    if ($series === NULL) return Redirect::to('/');

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

    $series = DeliverySerie::find($serie_id);
    if ($series === NULL) return Redirect::to('/');

    // We will list all the payments as bills
    $payments = $series->payments()->get();

    $csv_name = 'payments-'.$series->delivery.'-'.time().'.csv';
    return generate_csv_payments($csv_name, $payments);

  }

}