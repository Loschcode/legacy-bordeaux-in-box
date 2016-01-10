<?php namespace App\Http\Controllers\MasterBox\Easygo;

use App\Http\Controllers\MasterBox\BaseController;
use Request;

use App\Models\DeliverySerie;
use App\Models\DeliverySpot;
use App\Models\Order;

class HomeController extends BaseController {

  public function __construct()
  {

    $this->beforeMethod();
    $this->middleware('is.admin');
    $this->middleware('is.not.serie.ready', ['except' => 'getLocked']);
    $this->middleware('is.serie.ready', ['only' => 'getLocked']);
    $this->middleware('still.unpaid.orders.with.fail.card', ['except' => array('getLocked', 'getUnpaidOrders')]);
    $this->middleware('skip.unpaid.orders.with.fail.card', ['only' => 'getUnpaidOrders']);
  }

  private function _fetch_boxes_ordered($orders)
  {
    $boxes = [];

    foreach ($orders as $order)
    {
      if ( ! in_array($order->box_id, $boxes))
      {
        array_push($boxes, $order->box_id);
      }
    }

    return $boxes;
  }

  private function _fetch_sponsors($orders)
  {

    $sponsors = ['sponsors' => 0, 'has_sponsors' => 0];

    foreach ($orders as $order)
    {
      if ($order->customer_profile()->first()->isSponsor())
      {
        $sponsors['sponsors']++;
      }

      if ($order->customer_profile()->first()->hasSponsor())
      {
        $sponsors['has_sponsors']++;
      }
    }

    return $sponsors;
  }

  private function _fetch_birthdays($orders)
  {
    $birthdays = 0;

    foreach ($orders as $order)
    {
      if (Html::isBirthday($order->customer_profile()->first()->getAnswer('birthday')))
      {
        $birthdays++;
      }
    }

    return $birthdays;
  }

  private function _fetch_spots($orders)
  {
    $spots = [];

    foreach ($orders as $order)
    {
      if ( ! empty($order->delivery_spot_id))
      {
        if ( ! in_array($order->delivery_spot_id, $spots))
        {
          array_push($spots, $order->delivery_spot_id);
        }
      }
    }

    return $spots;
  }

  public function getLocked()
  {

    // Serie
    $serie = DeliverySerie::nextOpenSeries()->first();

    // Fetch all orders
    $orders = DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->get();

    // Count all orders
    $count_orders = count($orders);

    // Unpaid orders
    $unpaid = DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->where('already_paid', 0)->get();

    // Count unpaid orders
    $count_unpaid = count($unpaid);

    // All boxes ordered
    $boxes = $this->_fetch_boxes_ordered($orders);

    // Count sponsors / has sponsors
    $count_sponsors = $this->_fetch_sponsors($orders);

    // Count birthdays
    $count_birthdays = $this->_fetch_birthdays($orders);

    // Count orders not in a spot
    $count_not_take_away = DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->where('take_away', false)->count();

    // Count orders not in a spot and oustide of 33 zip
    $count_not_take_away_not_33 = DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->join('order_destinations', 'order_destinations.order_id', '=', 'orders.id')->whereNested(function($query) {

      $query->where('order_destinations.zip', 'NOT LIKE', '33%');
			$query->where('orders.take_away', '=', false);

		})->count();

    // Spots (array with ID of spots for the orders)
    $spots = $this->_fetch_spots($orders);

    // Render view
    return view('master-box.easygo.locked')->with(compact(

      'serie',
      'orders',
      'count_orders',
      'unpaid',
      'count_unpaid',
      'boxes',
      'count_sponsors',
      'count_birthdays',
      'count_not_take_away',
      'count_not_take_away_not_33',
      'spots'

    ));

  }

  public function getUnpaidOrders()
  {
    // Fetch unpaid orders
    $unpaid = Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->where('already_paid', 0)->get();

    $this->layout->content = view()->make('easygo.unpaid')->with(compact(

      'unpaid'

    ));

  }

  public function getIndex()
  {

    // Fetch all orders (with no constraints)
    $raw_orders =  Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->orderBy('box_id', 'ASC')->get();

    // Fetch all kind of boxes from raw
    $kind_boxes = $this->_fetch_boxes_ordered($raw_orders);

    $orders_completed = Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->whereNotNull('date_completed')->orderBy('box_id', 'ASC')->get();

    $orders_not_completed = Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->whereNull('date_completed')->orderBy('box_id', 'ASC')->get();

    // Fetch all spots based on the orders (Return an array with all the spots id)
    $spots = $this->_fetch_spots($orders_not_completed);

    if (Request::has('spot'))
    {
      // Fetch orders based on the filter spot
      $orders_filtered = Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->where('take_away', true)->where('delivery_spot_id', Request::get('spot'))->whereNull('date_completed')->orderBy('box_id', 'ASC')->get();
    }
    elseif (Request::has('to_send'))
    {
      $orders_filtered = Order::with('customer_profile', 'user', 'box')->LockedOrdersWithoutOrder()->notCanceledOrders()->where('take_away', false)->whereNull('date_completed')->orderBy('box_id', 'ASC')->get();
    }
    else
    {
      // Fetch orders
      $orders_filtered = $orders_not_completed;
    }

    // Fetch only unpaid orders (at that step we already filtered the problems with a fail card)
    $unpaid = Order::LockedOrdersWithoutOrder()->notCanceledOrders()->where('already_paid', 0)->get();

    $current_query = Request::query();

    return view('master-box.easygo.index')->with(compact(

      'raw_orders',
      'orders_completed',
      'orders_filtered',
      'spots',
      'unpaid',
      'current_query',
      'kind_boxes'

    ));
  }


}
