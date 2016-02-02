@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.admin.profiles.payments'
  ]) !!}
@stop

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('content')
      
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Abonnement #{{ $profile->id }}

    @if ($profile->status === 'expired')
    ({!! Html::getReadableProfileStatus($profile->status) !!})
    @endif
    
      </h1>
      <h2 class="title title__subsection">Historique de paiements</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les abonnements</a>
      </div>
    </div>
  </div>
  <div class="divider divider__section"></div>


  <table>

    <thead>

      <tr>
        <th></th>
        <th>ID</th>
        <th>Série</th>
        <th>Type</th>
        <th>Prix</th>
        <th>Statut</th>
        <th>Derniers chiffres de carte</th>
        <th>Date</th>
        <th>Action</th>
      </tr>

    </thead>

    <tbody>

      @foreach ($payments as $payment)

        <tr
          data-stripe-customer="{{$payment->stripe_customer}}" 
          data-stripe-event="{{$payment->stripe_event}}" 
          data-stripe-charge="{{$payment->stripe_charge}}" 
          data-stripe-card="{{$payment->stripe_card}}"
        >
          <th class="js-more"><a href="#" class="button button__table"><i class="fa fa-plus-square-o"></i></a></th>
          <th>{{$payment->id}}</th>
          <th>
          @foreach ($payment->orders()->get() as $order)
          
          <a class="button button__link" href="{{ action('MasterBox\Admin\DeliveriesController@getFocus', ['id' => $order->delivery_serie()->first()->id]) }}"> 

          {{ Html::dateFrench($order->delivery_serie()->first()->delivery, true) }}

          </a>

          @endforeach
          
          </th>
          <th>{!! Html::getReadablePaymentType($payment->type) !!}</th>
          <th>{{ Html::euros($payment->amount) }}</th>
          <th>{!! Html::getReadablePaymentStatus($payment->paid) !!}</th>
          <th>{{$payment->last4}}</th>
          <th>{{ Html::dateFrench($payment->created_at, true) }}</th>
          <th>

            <a class="button button__table" href="{{ url('/admin/payments/focus/' . $payment->id) }}"><i class="fa fa-eye"></i></a>

          </th>

        </tr>

      @endforeach

      </tbody>

    </table>
      
    <div class="+spacer"></div>

    <div class="row">
      <div class="grid-6">
        <div class="panel">
          <div class="panel__wrapper">
            <div class="panel__header">
              <h3 class="panel__title">Réinitialiser l'abonnement (et forcer un paiement)</h3>
            </div>
            <div class="panel__content">
              {!! Html::info("Annule l'abonnement stripe, le recréer à l'identique et l'applique à l'abonnement") !!}
              <a class="button button__default" href="{{ action('MasterBox\Admin\ProfilesController@getResetSubscriptionAndPay', ['id' => $profile->id]) }}">Réinitialiser l'abonnement</a>
            </div>
          </div>
        </div>
      </div>
      <div class="grid-6">
        <div class="panel">
          <div class="panel__wrapper">
            <div class="panel__header">
              <h3 class="panel__title">Forcer un paiement</h3>
            </div>
            <div class="panel__content">
              {!! Html::info("Prélève sur la carte de l'abonnement un montant du prix de l'abonnement (type transfert unique)") !!}
              <a class="button button__default" href="{{ action('MasterBox\Admin\ProfilesController@getForcePay', ['id' => $profile->id]) }}">Forcer le paiement</a>
            </div>
          </div>
        </div>
      </div>
    </div>



@stop