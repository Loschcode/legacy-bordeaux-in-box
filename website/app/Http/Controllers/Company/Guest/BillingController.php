<?php namespace App\Http\Controllers\Company\Guest;

use App\Http\Controllers\Company\BaseController;

use App\Models\CompanyBilling;

class BillingController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Dashboard Controller
  |--------------------------------------------------------------------------
  |
  | The admin dashboard
  |
  */
 
  /**
   * Index dashboard
   * @return void
   */
  public function getWatch($encrypted_access)
  {

    $company_billing = CompanyBilling::where('encrypted_access', '=', $encrypted_access)->first();

      if ($company_billing !== NULL) {

        return generate_pdf_bill($company_billing);

      }

    return redirect()->action('MasterBox\Guest\HomeController@getIndex');

  }

  public function getDownload($encrypted_access)
  {

    $company_billing = CompanyBilling::where('encrypted_access', '=', $encrypted_access)->first();

      if ($company_billing !== NULL) {

        return generate_pdf_bill($company_billing, TRUE);

      }

    return redirect()->action('MasterBox\Guest\HomeController@getIndex');

  }

}
