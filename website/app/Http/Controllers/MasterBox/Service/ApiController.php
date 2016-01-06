<?php namespace App\Http\Controllers\MasterBox\Service;

use App\Http\Controllers\MasterBox\BaseController;

use App\Models\Contact;
use App\Models\PartnerProduct;
use App\Models\DeliverySerie;

class ApiController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Api Controller
  |--------------------------------------------------------------------------
  |
  | Api system
  |
  */
  public function __construct()
  {
    $this->middleware('isAdmin', ['only' => ['getContacts', 'getOrdersCount']]);
  }

  /**
   * Resolve a specific partner product
   */
  public function postGetPartnerProduct($id)
  {
    $partner_product = PartnerProduct::find($id);

    if ($partner_product == NULL) {

      return response()->json(['success' => FALSE, 'error' => 'Impossible to find this product']);

    } else {

      return response()->json(['success' => TRUE, 'datas' => $partner_product]);
      
    }
  }

  /**
   * Fetch all contacts (emails)
   */
  public function getContacts()
  {
    $contacts = Contact::orderBy('created_at', 'asc')->get();
    return response()->json($contacts->toJson());
  }

  /**
   * Count the orders (for the bip system)
   */
  public function getOrdersCount()
  {
    $current_serie = DeliverySerie::nextOpenSeries()->first();

    $count = $current_serie->orders()->notCanceledOrders()->count();
    return response()->json(['count' => $count]);
  }

}
