<?php namespace App\Http\Controllers\MasterBox\Admin;

use App\Http\Controllers\MasterBox\BaseController;

use Carbon\Carbon; 

use App\Models\Payment;
use App\Models\Box;
use App\Models\DeliverySerie;
use App\Models\DeliverySetting;
use App\Models\DeliveryPrice;
use App\Models\DeliverySpot;
use App\Models\Order;
use App\Models\BoxQuestion;

use Illuminate\Support\Str, Config;

class DeliveriesController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Admin Deliveries Controller
  |--------------------------------------------------------------------------
  |
  | Check and edit deliveries
  |
  */

  /**
   * Filters
   */
  public function __construct()
  {

      $this->beforeMethod();

  }
    
  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {
    
    # Payment part
    $payments = Payment::orderBy('created_at', 'desc')->get();
    $series = DeliverySerie::withOrdersOnly()->orderBy('id', 'desc')->get();
    $prices = DeliveryPrice::orderBy('unity_price')->get();
    $settings = DeliverySetting::first();

    //$config_graph_all_orders = $this->all_orders_graph_config($series);
    //$config_graph_all_payments = $this->all_payments_graph_config($series);

    return view('masterbox.admin.deliveries.index')->with(compact(
      'payments',
      'series',
      'prices',
      'settings',
      'config_graph_all_orders',
      'config_graph_all_payments'
    ));

  }

  /**
   * Display configuration offers
   * @return [type] [description]
   */
  public function getConfigurationOffers()
  {

  }

  public function getFocus($id)
  {

    $series = DeliverySerie::find($id);
    $orders = $series->orders()->notCanceledOrders()->get();

    return view('masterbox.admin.deliveries.focus')->with(compact(
      'series',
      'orders'
    ));

  }

  /**
   * Display spots for the serie X
   * @param  String $id Series id
   * @return \Illuminate\View\View
   */
  public function getSpots($id)
  {
    $spots = DeliverySpot::get();
    $series = DeliverySerie::find($id);

    return view('masterbox.admin.deliveries.spots')->with(compact(
      'series',
      'spots'
    ));
  }

  /**
   * Display questions answers for the series X
   * @param  String $id Series id
   * @return \Illuminate\View\View
   */
  public function getQuestionsAnswers($id)
  {
    $series = DeliverySerie::find($id);
    $box_questions = BoxQuestion::get();
    $form_stats = $series->getFormStats();

    return view('masterbox.admin.deliveries.questions_answers')->with(compact(
      'box_questions',
      'form_stats',
      'series'
    ));

  }

  public function getStatistics($id)
  {

    $series = DeliverySerie::find($id);
    $customer_profiles = $series->customer_profiles()->get();
    $customers_with_unfinished = $series->customers(TRUE)->get();
    $customers = $series->customers()->get();
    $orders_not_canceled = $series->orders()->notCanceledOrders()->get();

    $daily_statistics = [];

    /**
     * Unfinished customers count
     */
    foreach ($customers_with_unfinished as $customer) {

      $day = ucfirst(\Date::parse($customer->created_at)->format('l'));

      if (!isset($daily_statistics[$day]['account_creation_with_unfinished']))
        $daily_statistics[$day]['account_creation_with_unfinished'] = 0;
      else
        $daily_statistics[$day]['account_creation_with_unfinished']++;

    }

    /**
     * Finished customers count
     */
    foreach ($customers as $customer) {

      $day = ucfirst(\Date::parse($customer->created_at)->format('l'));

      if (!isset($daily_statistics[$day]['account_creation_only_finished']))
        $daily_statistics[$day]['account_creation_only_finished'] = 0;
      else
        $daily_statistics[$day]['account_creation_only_finished']++;

    }

    /**
     * Not canceled orders
     */
    foreach ($orders_not_canceled as $order) {

      $day = ucfirst(\Date::parse($order->created_at)->format('l'));

      if (!isset($daily_statistics[$day]['not_canceled_orders']))
        $daily_statistics[$day]['not_canceled_orders'] = 0;
      else
        $daily_statistics[$day]['not_canceled_orders']++;

    }

    return view('masterbox.admin.deliveries.statistics')->with(compact(
      'series',
      'daily_statistics'
    ));


  }

  /**
   * Display emails for the series X
   * @param  String $id Series id
   * @return \Illuminate\View\View
   */
  public function getListingEmails($id)
  {
    $series = DeliverySerie::find($id);

    $series_email_listing = get_email_listing_from_orders($series->orders()->notCanceledOrders()->get());
    $series_unfinished_email_listing = get_email_listing_from_unfinished_profiles($series);

    return view('masterbox.admin.deliveries.listing_emails')->with(compact(
      'series',
      'series_email_listing',
      'series_unfinished_email_listing'
    ));
    
  }

  /**
   * Get the edit page
   * @return void
   */
  public function getEdit($id)
  {
    $series = DeliverySerie::findOrFail($id);

    return view('masterbox.admin.deliveries.edit')->with(compact(
      'series'
    ));
  }


    /**
     * Edit a series
     * @return void
     */
  public function postEdit()
  {

    // New article rules
    $rules = [

      'delivery_series_id' => 'required|integer',
      'delivery' => 'required',
      'goal' => 'integer',

      ];


    $fields = request()->all();

    $validator = validator()->make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $serie = DeliverySerie::findOrFail($fields['delivery_series_id']);

      $serie->delivery = $fields['delivery'];

      if ($fields['goal']) $serie->goal = $fields['goal'];
      else $serie->goal = NULL;

      $serie->save();

      session()->flash('message', "La série a bien été mise à jour");
      return redirect()->to('/admin/deliveries');


    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }


  }


  /**
   * We remove the profile
   */
  public function getDelete($id)
  {

    $profile = DeliverySerie::findOrFail($id);
    
    $profile->delete();

    session()->flash('message', "La série a bien été supprimé");
    return redirect()->back();

  }

  public function postAddPrice()
  {


    // New article rules
    $rules = [

      'frequency' => 'required|integer',
      'unity_price' => 'required|numeric',
      'title' => '',
      'gift' => 'required|integer',

      ];


    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $delivery_price = new DeliveryPrice;
      $delivery_price->frequency = $fields['frequency'];
      $delivery_price->unity_price = $fields['unity_price'];
      $delivery_price->title = $fields['title'];
      
      $gift = $fields['gift'];

      if ($gift == '1') $delivery_price->gift = TRUE;
      else $delivery_price->gift = FALSE;

      $delivery_price->save();

      session()->flash('message', "Cette offre a bien été ajoutée");
      return redirect()->back();

    }

    // We return the same page with the error and saving the input datas
    return redirect()->back()
      ->withInput()
      ->withErrors($validator);

  }

  public function postEditPrice()
  {


    // New article rules
    $rules = [

      'delivery_price_id' => 'required',
      'frequency' => 'required|integer',
      'title' => '',
      'unity_price' => 'required|numeric',

      ];


    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $delivery_price = DeliveryPrice::findOrFail($fields['delivery_price_id']);

      $delivery_price->frequency = $fields['frequency'];
      $delivery_price->unity_price = $fields['unity_price'];
      $delivery_price->title = $fields['title'];
      $delivery_price->save();

      session()->flash('message', "Cette offre a bien été modifiée");

      return redirect()->back();

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }



  }

  public function postEditSettings()
  {


    // New article rules
    $rules = [

      'regional_delivery_fees' => 'required|numeric',
      'national_delivery_fees' => 'required|numeric',

      ];

    $fields = Request::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $settings = DeliverySetting::first();

      $settings->regional_delivery_fees = $fields['regional_delivery_fees'];
      $settings->national_delivery_fees = $fields['national_delivery_fees'];
      $settings->save();

      session()->flash('message', "Les coûts de livraison ont bien été mis à jour");

      return redirect()->back();

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }



  }

  /**
   * Add a new serie
   * @return void
   */
  public function postIndex()
  {


    // New article rules
    $rules = [

      'delivery' => 'required',
      'goal' => 'required|integer',

      ];

    $fields = request()->all();

    $validator = validator()->make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $serie = new DeliverySerie;

      $serie->delivery = $fields['delivery'];
      $serie->goal = $fields['goal'];

      $serie->save();

      session()->flash('message', "La série a bien été ajoutée");
      return redirect()->back();

    } else {

      // We return the same page with the error and saving the input datas
      return redirect()->back()
      ->withInput()
      ->withErrors($validator);

    }


  }

  /**
   * We lock the delivery
   */
  public function getLock($id)
  {

    $serie = DeliverySerie::findOrFail($id);

    $serie->closed = date('Y-m-d');

    $orders = $serie->orders()->notCanceledOrders()->get();

    foreach ($orders as $order) {

     $order->status = 'packing';
     $order->locked = TRUE;
     $order->save();

    }

   $serie->save();

   session()->flash('message', "La série a bien été bloquée");
   return redirect()->back();

  }

  /**
   * We unlock the delivery
   */
  public function getUnlock($id)
  {

    $serie = DeliverySerie::find($id);

    if ($serie !== NULL)
    {

      $serie->closed = NULL;

      $orders = $serie->orders()->notCanceledOrders()->get();

      foreach ($orders as $order) {

      	if ($order->already_paid >= $order->unity_and_fees) {
      		$order->status = 'paid';
      	} elseif ($order->already_paid <= 0) {
      		$order->status = 'unpaid';
      	} else {
      		$order->status = 'half-paid';
      	}
      	
        $order->locked = FALSE;
        $order->save();

      }

      $serie->save();

      session()->flash('message', "La série a bien été débloquée");
      return redirect()->back();

    }

  }

  /**
   * We remove the delivery price
   */
  public function getDeletePrice($id)
  {

    $delivery_price = DeliveryPrice::find($id);

    if ($delivery_price !== NULL)
    {

      $delivery_price->delete();

      session()->flash('message', "L'offre a été correctement supprimée");
      return redirect()->to('admin/deliveries#offers');


    }

  }


  /**
   * We make a CSV out of the orders from a specific series
   * @return void
   */
  public function getDownloadCsvOrdersFromSeries($series_id)
  {

    $series = DeliverySerie::find($series_id);

    if ($series != NULL) {

      $file_name = "orders-from-series-".$series->delivery."-".time().".csv";
      $orders = Order::where('delivery_serie_id', $series->id)->get();

      return generate_csv_order($file_name, $orders);

    }

  }

  /**
   * We make a CSV out of the orders from a specific series and spot
   * @return void
   */
  public function getDownloadCsvOrdersFromSeriesAndSpot($series_id, $spot_id)
  {

    $series = DeliverySerie::find($series_id);
    $spot = DeliverySpot::find($spot_id);

    if (($series != NULL) && ($spot != NULL)) {

      $file_name = "orders-from-series-".$series->delivery."-and-spot-".$spot->slug."-".time().".csv";
      $orders = Order::notCanceledOrders()->where('delivery_serie_id', $series->id)->where('take_away', true)->where('delivery_spot_id', '=', $spot->id)->get();

      return generate_csv_order($file_name, $orders, true);

    }

  }

  public function getListingOrdersFromSeriesAndSpot($series_id, $spot_id)
  {

    $series = DeliverySerie::findOrFail($series_id);
    $spot = DeliverySpot::find($spot_id);
    
    // Find orders
    $orders = Order::notCanceledOrders()->where('delivery_serie_id', $series->id)->where('take_away', true)->where('delivery_spot_id', '=', $spot->id)->get();

    $html = view('masterbox.admin.deliveries.listing_orders_from_series_and_spot')->with(compact(
      'orders',
      'series',
      'spot'
    ));
    
    return generate_pdf($html, null, false, false);


  }

  /**
   * We make a CSV out of the orders only from the spots
   * @return void
   */
  public function getDownloadCsvSpotsOrdersFromSeries($series_id)
  {

    $series = DeliverySerie::find($series_id);

    if ($series != NULL) {

      $file_name = "orders-spots-from-series-".$series->delivery."-".time().".csv";
      $orders = Order::notCanceledOrders()->where('delivery_serie_id', $series->id)->where('take_away', true)->get();

      return generate_csv_order($file_name, $orders, true);

    }

  }

  public function all_payments_graph_config()
  {

    $graph_data = array();

    $grouped_payments = Payment::select('id', 'paid', 'amount', 'created_at')
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->created_at)->format('Y/m/d');
  
    });

    foreach ($grouped_payments as $payments) {

        // Big stats about everything here
        $payments_counter = 0;
        $refund_counter = 0;
        $total_amount = 0;
        $failures = 0;
        $total_failures = 0;

        // We will loop everything to make the statistics possible
        foreach ($payments as $payment) {

          // If it's effective (no fail)
          if ($payment->paid) {

            if ($payment->amount >= 0) {
              $payments_counter++;
            } else {
              $refund_counter++;
            }

            $total_amount += $payment->amount;

          } else {

            $failures++;
            $total_failures += $payment->amount;
          }

        }

        array_push($graph_data, [

          'date' => $payments[0]->created_at->format('Y-m-d'), 
          'payments' => $payments_counter,
          'refund' => $refund_counter,
          'amount' => $total_amount,
          'failures' => $failures,
          'total_failures' => $total_failures

        ]);

    }

    $config_graph = [

          'id' => 'graph-series-payments',
          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => ['payments', 'refund', 'amount', 'failures', 'total_failures'],
          'labels' => ['Paiements confirmés', 'Remboursements confirmés', 'Total transféré', 'Echecs', 'Total échecs'],

          "xLabels" => 'week',

          'lineColors' => convert_to_graph_colors(['blue', 'black', 'green', 'purple', 'red']),

        ];

    return $config_graph;


  }

  public function all_orders_graph_config()
  {

    $graph_data = array();

    $grouped_orders = Order::select('id', 'created_at')
    ->notCanceledOrders()
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->created_at)->format('Y/m/d');
  
    });

    foreach ($grouped_orders as $orders) {

        array_push($graph_data, [

        'date' => $orders[0]->created_at->format('Y-m-d'), 
        'orders' => count($orders)

          ]);

    }


    $config_graph = [

          'id' => 'graph-series-orders',
          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => ['orders'],
          'labels' => ['Commandes'],

          "xLabels" => 'week',

          'lineColors' => convert_to_graph_colors(['blue']),

        ];

    return $config_graph;


  }

  public function series_orders_graph_config($series)
  {

    $graph_data = array();

    $grouped_orders = $series->orders()->select('id', 'created_at')
    ->notCanceledOrders()
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->created_at)->format('Y/m/d'); // grouping by day
        //return Carbon::parse($date->created_at)->format('m'); // grouping by months
        //
    });

    foreach ($grouped_orders as $orders) {

      //dd($orders[0]->created_at->format('Y/m/d')); //->toDateTimeString());

        array_push($graph_data, [

        'date' => $orders[0]->created_at->format('Y-m-d'), 
        'orders' => count($orders)

          ]);

    }

    //dd($graph_data);

    $config_graph = [

          'id' => 'graph-series-orders',
          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => ['orders'],
          'labels' => ['Commandes'],

          "xLabels" => 'week',
          //"continuousLine" => true,

          'lineColors' => convert_to_graph_colors(['blue']),

        ];

    return $config_graph;


  }

}