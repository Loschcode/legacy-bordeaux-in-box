<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Carbon\Carbon; 

use App\Models\Payment;
use App\Models\Box;
use App\Models\DeliverySerie;
use App\Models\DeliverySetting;
use App\Models\DeliveryPrice;
use App\Models\DeliverySpot;
use App\Models\Order;

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
      $this->middleware('isAdmin');

  }
    
  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {
    
    # Payment part
    $payments = Payment::orderBy('created_at', 'desc')->get();
    $boxes = Box::orderBy('created_at', 'desc')->get();

    $series = DeliverySerie::orderBy('delivery', 'asc')->get();
    $prices = DeliveryPrice::orderBy('unity_price')->get();
    $settings = DeliverySetting::first();

    $config_graph_all_orders = $this->all_orders_graph_config($series);
    $config_graph_all_payments = $this->all_payments_graph_config($series);
    $config_graph_box_orders = $this->box_orders_graph_config();

    return view('admin.deliveries.index')->with(compact(
      'payments',
      'boxes',
      'series',
      'prices',
      'settings',
      'config_graph_all_orders',
      'config_graph_all_payments',
      'config_graph_box_orders'
    ));

  }

  public function getFocus($id)
  {

    $series = DeliverySerie::find($id);
    $spots = DeliverySpot::get();
    $boxes = Box::get();

    $form_stats = $series->getFormStats();

    $config_graph_series_orders = $this->series_orders_graph_config($series);

    $series_email_listing = get_email_listing_from_orders($series->orders()->notCanceledOrders()->get());
    $series_unfinished_email_listing = get_email_listing_from_unfinished_profiles($series);

    return view('admin.deliveries.focus')->with(compact(
      'series',
      'spots',
      'form_stats',
      'boxes',
      'config_graph_series_orders',
      'series_email_listing',
      'series_unfinished_email_listing'
    ));

  }

  public function getFocusBox($id)
  {

    $box = Box::find($id);

    $config_graph_box_orders = $this->box_orders_graph_config($box);

    $box_email_listing = get_email_listing_from_orders($box->orders()->notCanceledOrders()->get());

    return view('admin.deliveries.focus_box')->with(compact(
      'box',
      'config_graph_box_orders',
      'box_email_listing'
    ));

  }

  /**
   * Get the edit page
   * @return void
   */
  public function getEdit($id)
  {
    $series = DeliverySerie::find($id);

    return view('admin.deliveries.edit')->with(compact(
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


    $fields = Input::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $serie = DeliverySerie::find($fields['delivery_series_id']);

      if ($serie !== NULL) {

        $serie->delivery = $fields['delivery'];

        if ($fields['goal']) $serie->goal = $fields['goal'];
        else $serie->goal = NULL;

        $serie->save();

        Session::flash('message', "La série a bien été mise à jour");
        return Redirect::to('/admin/deliveries');

    }

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }


  }


  /**
   * We remove the profile
   */
  public function getDelete($id)
  {

    $profile = DeliverySerie::find($id);

    if ($profile !== NULL)
    {

      $profile->delete();

      Session::flash('message', "La série a bien été supprimé");
      return Redirect::back();


    }

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


    $fields = Input::all();

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

      Session::flash('message', "Cette offre a bien été ajoutée");
      return Redirect::back();

      }

      // We return the same page with the error and saving the input datas
      return Redirect::back()
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


    $fields = Input::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $delivery_price = DeliveryPrice::find($fields['delivery_price_id']);

      if ($delivery_price != NULL) {

      $delivery_price->frequency = $fields['frequency'];
      $delivery_price->unity_price = $fields['unity_price'];
      $delivery_price->title = $fields['title'];
      $delivery_price->save();

      Session::flash('message', "Cette offre a bien été modifiée");

      }

      return Redirect::back();

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
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

    $fields = Input::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $settings = DeliverySetting::first();

      $settings->regional_delivery_fees = $fields['regional_delivery_fees'];
      $settings->national_delivery_fees = $fields['national_delivery_fees'];
      $settings->save();

      Session::flash('message', "Les coûts de livraison ont bien été mis à jour");

      return Redirect::back();

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
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
      'goal' => 'integer',

      ];


    $fields = Input::all();

    $validator = Validator::make($fields, $rules);

    // The form validation was good
    if ($validator->passes()) {

      $serie = new DeliverySerie;

      $serie->delivery = $fields['delivery'];

      if ($fields['goal']) $serie->goal = $fields['goal'];
      else $serie->goal = NULL;

      $serie->save();

      Session::flash('message', "La série a bien été ajoutée");
      return Redirect::back();

    } else {

      // We return the same page with the error and saving the input datas
      return Redirect::back()
      ->withInput()
      ->withErrors($validator);

    }


  }

  /**
   * We lock the delivery
   */
  public function getLock($id)
  {

    $serie = DeliverySerie::find($id);

    if ($serie !== NULL)
    {

      $serie->closed = date('Y-m-d');

      $orders = $serie->orders()->notCanceledOrders()->get();

      foreach ($orders as $order) {

      	$order->status = 'packing';
        $order->locked = TRUE;
        $order->save();

      }

      $serie->save();

      Session::flash('message', "La série a bien été bloquée");
      return Redirect::back();

    }

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

      Session::flash('message', "La série a bien été débloquée");
      return Redirect::back();

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

      Session::flash('message', "L'offre a été correctement supprimée");
      return Redirect::to('admin/deliveries#offers');


    }

  }

  /**
   * We make a CSV out of the orders from a specific box
   * @return void
   */
  public function getDownloadCsvOrdersFromBox($box_id)
  {

    $box = Box::find($box_id);

    if ($box != NULL) {

      $slug = $box->slug;
      $file_name = "orders-from-box-".$slug."-".time().".csv";
      $orders = Order::where('box_id', $box->id)->get();

      return generate_csv_order($file_name, $orders);

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

  public function box_orders_graph_config($focused_box=false)
  {

    $graph_data = array();

    if ($focused_box) $boxes = Box::where('id', '=', $focused_box->id)->get();
    else $boxes = Box::get();

    if ($focused_box) {

      $grouped_orders = $boxes->first()->orders()->notCanceledOrders()->get()
      ->groupBy(function($date) {

          return Carbon::parse($date->created_at)->format('Y/m/d');
    
      });

    } else {

      $grouped_orders = Order::notCanceledOrders()->get()
      ->groupBy(function($date) {

          return Carbon::parse($date->created_at)->format('Y/m/d');
    
      });

    }

    // We prepare the dynamic box lines
    $array_box_ykeys = [];
    $array_box_labels = [];

    $array_box_random_color = [];
    $possible_colors = ['purple', 'green', 'blue', 'red', 'black', 'orange', 'brown'];

    foreach ($boxes as $box) { 

      $slug = $box->slug;

      $array_box_ykeys[] = $slug;
      $array_box_labels[] = $box->title;

      $rand = array_rand($possible_colors);

      /**
       * This will search a matching color from the configuration file
       * For the matching slug (e.g. `super-mamoune` will have `orange` color from the config)
       */
      
      $arr_check = Config::get('bdxnbx.box_color');
      if (isset($arr_check[$slug])) $rand = array_search($arr_check[$slug], $possible_colors);

      $array_box_random_color[] = $possible_colors[$rand];
      unset($possible_colors[$rand]); // To avoid repeat

      $array_boxes_counter[$slug] = 0;

    }

    foreach ($grouped_orders as $orders) {

      foreach ($orders as $order) {

        $slug = Str::slug(Box::find($order->box_id)->title);

        $array_boxes_counter[$slug]++;

      }

      $current_date = $orders[0]->created_at->format('Y-m-d');
      $datas_to_push = array_merge(['date' => $current_date], $array_boxes_counter);

      array_push($graph_data, $datas_to_push);

      foreach ($boxes as $box) { 

        $array_boxes_counter[$slug] = 0;

      }


    }

    $config_graph = [

          'id' => 'graph-box-orders',

          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => $array_box_ykeys,
          'labels' =>  $array_box_labels,

          "xLabels" => 'week',

          'lineColors' => convert_to_graph_colors($array_box_random_color),

        ];

    //dd($config_graph);

    return $config_graph;

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