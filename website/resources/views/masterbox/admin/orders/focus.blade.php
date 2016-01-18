<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Livraison N°{{$order->id}}</h4>
</div>
<div class="modal-body">


  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-user"></i> Utilisateur &amp; Abonnement</div>

    <div class="panel-body">

    Rien

    </div>
  </div>


  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-bank"></i> Informations détaillées</div>

    <div class="panel-body">

      Statut : {{ readable_order_status($order->status) }}</br>
      Déjà payé : {{ $order->already_paid}} €</br>
      Prix à l'unité avec frais : {{$order->unity_and_fees_price}} €</br>
      @if (isset(config('bdxnbx.payment_ways')[$order->payment_way]))
      Moyen de paiement : {{config('bdxnbx.payment_ways')[$order->payment_way]}}<br />
      @endif
      A offrir : {!!Html::boolYesOrNo($order->gift)!!}<br />
      Bloqué : {!!Html::boolYesOrNo($order->locked)!!}<br />
      A emporter : {!!Html::boolYesOrNo($order->take_away)!!}<br />
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-link"></i> Données &amp; dates</div>

    <div class="panel-body">

      {!! Form::open(array('action' => 'MasterBox\Admin\OrdersController@postUpdateOrderStatus', 'class' => 'form-inline')) !!}

      {!! Form::hidden("order_id", $order->id) !!}

      {!! Form::label("order_status", "Statut ") !!}
      {!! Form::select('order_status', $order_status_list, (Request::old("order_status")) ? Request::old("order_status") : $order->status) !!}

      {!! Form::submit("Mettre à jour", ['class' => 'spyro-btn spyro-btn-success spyr-btn-sm']) !!}

      {!! Form::close() !!}

      <br />

      Date création : {{$order->created_at}}<br />
      Date complétée : {{$order->date_completed}}<br />
      Date envoyée : {{$order->date_sent}}<br />

    </div>
  </div>

  

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-cog"></i> Actions diverses</div>

    <div class="panel-body">

      <a class="spyro-btn spyro-btn-danger" href="{{url('/admin/orders/delete/'.$order->id)}}">Archiver</a>

    </div>
  </div>

</div>
<div class="modal-footer">
  <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
</div>


