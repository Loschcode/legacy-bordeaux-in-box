<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

use Carbon\Carbon;

class StatisticsController extends BaseController {

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
   * The layout that should be used for responses.
   */
  protected $layout = 'layouts.admin';

  /**
   * Get the listing page
   * @return void
   */
  public function getIndex()
  {

    $series = DeliverySerie::orderBy('delivery', 'desc')->get();
    View::share('series', $series);

    $config_graph_unfinished_profiles_steps = $this->unfinished_profiles_graph_config();
    View::share('config_graph_unfinished_profiles_steps', $config_graph_unfinished_profiles_steps);

    $config_graph_subscriptions_versus_unfinished = $this->subscriptions_versus_unfinished_graph_config();
    View::share('config_graph_subscriptions_versus_unfinished', $config_graph_subscriptions_versus_unfinished);

    $this->layout->content = View::make('admin.statistics.index');

  }

  public function getUnfinishedProfiles($id)
  {

    $user_order_buildings = UserOrderBuilding::where('delivery_serie_id', '=', $id)->orderBy('updated_at', 'desc')->get();
    View::share('user_order_buildings', $user_order_buildings);

    $series = DeliverySerie::find($id);
    View::share('series', $series);

    $config_graph_unfinished_profiles_focus = $this->unfinished_profiles_focus_graph_config($series);
    View::share('config_graph_unfinished_profiles_focus', $config_graph_unfinished_profiles_focus);

    $this->layout->content = View::make('admin.statistics.unfinished_profiles');



  }

  public function unfinished_profiles_focus_graph_config($series)
  {

    $graph_data = array();

    $grouped_order_buildings = $series->user_order_buildings()->select('id', 'updated_at')
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->updated_at)->format('Y/m/d'); // grouping by day

    });

    $grouped_orders = $series->orders()->notCanceledOrders()->select('id', 'created_at')
    ->get()
    ->groupBy(function($date) {

        return Carbon::parse($date->created_at)->format('Y/m/d'); // grouping by day

    });

    $groupments = [];

    foreach ($grouped_order_buildings as $order_buildings) {

        $date = $order_buildings[0]->updated_at->format('Y-m-d');
        $data = count($order_buildings);

        $groupments[$date]['abandonment'] = $data;
        $groupments[$date]['orders'] = 0;

    }

    foreach ($grouped_orders as $orders) {

        $date = $orders[0]->created_at->format('Y-m-d');
        $data = count($orders);

        // We are favoriting the `abandonment` data, we will forget about all the orders
        // That were made before it was the series time
        if (isset($groupments[$date]['abandonment'])) {

          $groupments[$date]['orders'] = $data;

        }

    }

    foreach ($groupments as $key => $groupment) {

        array_push($graph_data, [

        'date' => $key, 
        'abandonment' => $groupment['abandonment'],
        'orders' => $groupment['orders']

          ]);

    }

    $config_graph = [

          'id' => 'graph-series-abandonment',
          'data' => $graph_data,

          'xkey' => 'date',
          'ykeys' => ['abandonment', 'orders'],
          'labels' => ['Abandons', 'Commandes'],

          "xLabels" => 'week',

          'lineColors' => convert_to_graph_colors(['red', 'green']),

        ];

    return $config_graph;

  }

  public function subscriptions_versus_unfinished_graph_config()
  {

    $graph_data = [];

    foreach (DeliverySerie::orderBy('delivery', 'asc')->get() as $serie) {

      if ($serie->user_order_buildings()->count() > 0) {

        $graph_data[] = [

            'series' => $serie->delivery,
            'subscriptions' => $serie->orders()->notCanceledOrders()->count(),
            'unfinished' => $serie->user_order_buildings()->count()

          ];

        }

    }

    $config_graph = [

          'id' => 'graph-subscriptions-versus-unfinished-profiles',
          'data' => $graph_data,

          'xkey' => 'series',
          'ykeys' => ['subscriptions', 'unfinished'],
          'labels' => ['Abonnements', 'Abandons'],

          'barColors' => convert_to_graph_colors(['blue', 'red']),
    
        ];

    return $config_graph;



  }

  public function unfinished_profiles_graph_config()
  {

    $graph_unfinished_profiles_steps_data = [];

    foreach (DeliverySerie::orderBy('delivery', 'asc')->get() as $serie) {

      if ($serie->user_order_buildings()->count() > 0) {

        $graph_unfinished_profiles_steps_data[] = [

          'series' => $serie->delivery, 
          'choose-box' => $serie->user_order_buildings()->where('step', '=', 'choose-box')->count(), 
          'box-form' => $serie->user_order_buildings()->where('step', '=', 'box-form')->count(),
          'choose-frequency' => $serie->user_order_buildings()->where('step', '=', 'choose-frequency')->count(),
          'billing-address' => $serie->user_order_buildings()->where('step', '=', 'billing-address')->count(),
          'delivery-mode' => $serie->user_order_buildings()->where('step', '=', 'delivery-mode')->count(),
          'choose-spot' => $serie->user_order_buildings()->where('step', '=', 'choose-spot')->count(),
          'payment' => $serie->user_order_buildings()->where('step', '=', 'payment')->count(),

          ];

        }

    }

    $config_graph_unfinished_profiles_steps = [

          'id' => 'graph-unfinished-profiles',
          'data' => $graph_unfinished_profiles_steps_data,

          'xkey' => 'series',
          'ykeys' => ['choose-box', 'box-form', 'choose-frequency', 'billing-address', 'delivery-mode', 'choose-spot', 'payment'],
          'labels' => ['Choix de la box', 'Personnalise ta box', 'Fréquence de livraison', 'Adresse de facturation', 'Mode de livraison', 'Choix du point relais', 'Paiement'],

          'lineColors' => convert_to_graph_colors(['green', 'blue', 'red', 'purple', 'black', 'brown', 'orange']),
    
        ];

    return $config_graph_unfinished_profiles_steps;

  }

}