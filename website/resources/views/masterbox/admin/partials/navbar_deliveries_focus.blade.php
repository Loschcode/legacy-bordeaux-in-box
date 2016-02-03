  {!! Html::addButtonNavbar('Commandes (' . $series->orders()->notCanceledOrders()->count() . ')', action('MasterBox\Admin\DeliveriesController@getFocus')) !!}

  {!! Html::addButtonNavbar('Points Relais (' . App\Models\Order::where('delivery_serie_id', '=', $series->id)->where('take_away', '=', true)->notCanceledOrders()->count() . ')', action('MasterBox\Admin\DeliveriesController@getSpots', ['id' => $series->id])) !!}

  {!! Html::addButtonNavbar('Questionnaire', action('MasterBox\Admin\DeliveriesController@getFocus')) !!}

  {!! Html::addButtonNavbar('Listing des emails', action('MasterBox\Admin\DeliveriesController@getFocus')) !!}
