<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Paiement N°{{$payment->id}}</h4>
</div>
<div class="modal-body">


  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-user"></i> Utilisateur &amp; Abonnement</div>

    <div class="panel-body">

    Rien

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-exchange"></i> Stripe</div>

    <div class="panel-body">

      Customer : {{$payment->stripe_customer}}<br />
      Event : {{$payment->stripe_event}}<br />
      Charge : {{$payment->stripe_charge}}</br>
      Card : {{$payment->stripe_card}}</br>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-bank"></i> Informations bancaires</div>

    <div class="panel-body">

      Type de paiement : {!! Form::getReadablePaymentType($payment->type) !!}<br />
      Quantité : {{$payment->amount}} €</br>
      Statut : {!! Form::getReadablePaymentStatus($payment->paid) !!}</br>
      Derniers chiffres de carte : {{$payment->last4}}</br>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-link"></i> Liaisons internes &amp; dates</div>

    <div class="panel-body">

      {!! Form::open(array('action' => 'AdminPaymentsController@postUpdatePaymentOrder', 'class' => 'form-inline')) !!}

      {!! Form::hidden("payment_id", $payment->id) !!}

      {!! Form::label("order_id", "Série ") !!}
      {!! Form::select('order_id', $order_series_list, (Input::old("order_id")) ? Input::old("order_id") : $payment_order_id) !!}

      {!! Form::submit("Mettre à jour", ['class' => 'spyro-btn spyro-btn-success spyr-btn-sm']) !!}

      {!! Form::close() !!}

      <br />

      Date création : {{$payment->created_at}}<br />

    </div>
  </div>

  

  <div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-cog"></i> Actions diverses</div>

    <div class="panel-body">

      @if ($payment->paid)
      <a class="spyro-btn spyro-btn-warning" href="{{url('/admin/payments/make-fail/'.$payment->id)}}">Forcer l'échec</a>
      @else
      <a class="spro-btn spyro-btn-success" href="{{url('/admin/payments/make-success/'.$payment->id)}}">Considérer comme payé</a>
      @endif

      @if ($payment->order()->first() == NULL)

      <a class="spyro-btn spyro-btn-primary" href="{{url('/admin/payments/link-payment-to-next-series/'.$payment->id)}}">Relier à la prochaine série planifiée</a>

      @endif

      <a class="spyro-btn spyro-btn-danger" href="{{url('/admin/payments/delete/'.$payment->id)}}">Archiver</a>

    </div>
  </div>

</div>
<div class="modal-footer">
  <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
</div>


