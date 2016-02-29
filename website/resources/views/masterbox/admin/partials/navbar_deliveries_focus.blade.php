  {!! Html::addButtonNavbar('<i class="fa fa-truck"></i> Commandes (' . $series->orders()->notCanceledOrders()->count() . ')', action('MasterBox\Admin\DeliveriesController@getFocus', ['id' => $series->id])) !!}

  {!! Html::addButtonNavbar('<i class="fa fa-map-marker"></i> Points Relais (' . App\Models\Order::where('delivery_serie_id', '=', $series->id)->where('take_away', '=', true)->notCanceledOrders()->count() . ')', action('MasterBox\Admin\DeliveriesController@getSpots', ['id' => $series->id])) !!}

  {!! Html::addButtonNavbar('<i class="fa fa-question-circle"></i> Questionnaire', action('MasterBox\Admin\DeliveriesController@getQuestionsAnswers', ['id' => $series->id])) !!}

  {!! Html::addButtonNavbar('<i class="fa fa-envelope"></i> Listing des emails', action('MasterBox\Admin\DeliveriesController@getListingEmails', ['id' => $series->id])) !!}

  {!! Html::addButtonNavbar('<i class="fa fa-area-chart"></i> Statistiques', action('MasterBox\Admin\DeliveriesController@getStatistics', ['id' => $series->id])) !!}
