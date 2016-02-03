<div class="dialog">
  <h4 class="dialog__title">Paiement N°{{$payment->id}}</h4>
  <div class="dialog__divider"></div>
</div>

  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title"><i class="fa fa-exchange"></i> Stripe</h3>
    </div>

    <div class="panel__content">

      Customer : {{$payment->stripe_customer}}<br />
      Event : {{$payment->stripe_event}}<br />
      Charge : {{$payment->stripe_charge}}</br>
      Card : {{$payment->stripe_card}}</br>

    </div>
  </div>

  <div class="+spacer-small"></div>

  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title"><i class="fa fa-bank"></i> Informations bancaires</h3>
    </div>

    <div class="panel__content">
      Type de paiement : {!! Html::getReadablePaymentType($payment->type) !!}<br />
      Prix : {{ Html::euros($payment->amount) }}</br>
      Statut : {!! Html::getReadablePaymentStatus($payment->paid) !!}</br>
      Derniers chiffres de carte : {{$payment->last4}}</br>
    </div>
  </div>

  <div class="+spacer-small"></div>

  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title"><i class="fa fa-link"></i> Liaisons internes &amp; dates</h3>
    </div>

    <div class="panel__content">

      {!! Form::open(['action' => 'MasterBox\Admin\PaymentsController@postUpdatePaymentOrder']) !!}

      {!! Form::hidden("payment_id", $payment->id) !!}

      {!! Form::label("order_id", "Série ") !!}
      {!! Form::select('order_id', $order_series_list, (Request::old("order_id")) ? Request::old("order_id") : $payment_order_id) !!}

      {!! Form::submit("Mettre à jour") !!}

      {!! Form::close() !!}

      Date création : {{$payment->created_at}}<br />
    </div>
  </div>

  <div class="+spacer-small"></div>


  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title"><i class="fa fa-cog"></i> Actions diverses</h3>
    </div>

    <div class="panel__content">

      @if ($payment->paid)
        <a class="button button__default --red" href="{{url('/admin/payments/make-fail/'.$payment->id)}}">Forcer l'échec</a>
      @else
        <a class="button button__default" href="{{url('/admin/payments/make-success/'.$payment->id)}}">Considérer comme payé</a>
      @endif

      @if ($payment->orders()->first() == NULL)

      <a class="button button__default" href="{{url('/admin/payments/link-payment-to-next-series/'.$payment->id)}}">Relier à la prochaine série planifiée</a>

      @endif

      <a class="button button__default" href="{{url('/admin/payments/delete/'.$payment->id)}}">Archiver</a>

    </div>
  </div>

</div>


