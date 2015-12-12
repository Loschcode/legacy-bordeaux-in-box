<?php namespace App\Http\Controllers;

class ApiController extends \BaseController {

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
    $this->beforeFilter('isAdmin', ['only' => ['getContacts', 'getOrdersCount']]);
  }

  /**
   * Resolve a specific partner product
   */
  public function postGetPartnerProduct($id)
  {
    
    $partner_product = PartnerProduct::find($id);

    if ($partner_product == NULL) {

      return Response::json(['success' => FALSE, 'error' => 'Impossible to find this product']);

    } else {

      return Response::json(['success' => TRUE, 'datas' => $partner_product]);

    }


  }

  /**
   * Fetch all contacts (emails)
   */
  public function getContacts()
  {
    $contacts = Contact::orderBy('created_at', 'asc')->get();
    return Response::Json($contacts->toJson());
  }

  /**
   * Count the orders (for the bip system)
   */
  public function getOrdersCount()
  {
    $current_serie = DeliverySerie::nextOpenSeries()->first();

    $count = $current_serie->orders()->notCanceledOrders()->count();
    return Response::Json(['count' => $count]);
  }

}
