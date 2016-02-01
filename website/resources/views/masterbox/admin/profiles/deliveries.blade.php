@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_profiles')
@stop

@section('content')
    
  @include('masterbox.admin.partials.navbar_profiles')


  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Abonnements</h1>
      <h2 class="title title__subsection">Livraisons</h2>
    </div>
  </div>
  
  <div class="divider divider__section"></div>
  
  @if ($profile->orders()->first() != NULL)
    <table class="js-datatable-simple">

      <thead>

        <tr>
          <th>ID</th>
          <th>Série</th>
          <th>Mode de livraison</th>
          <th>Statut</th>
          <th>Montant déjà payé</th>
          <th>Prix total</th>
          <th>A offrir</th>
          <th>Etat de la commande</th>
          <th>Date complétée</th>
          <th>Date créée</th>
          <th>Action</th>
        </tr>

      </thead>

      <tbody>

        @foreach ($profile->orders()->get() as $order)

          <tr>

            <th>{{$order->id}}</th>
            <th>{{ Html::dateFrench($order->delivery_serie()->first()->delivery, true) }}</th>
            <th>
              @if ($order->take_away)
                Point relais ({{$order->delivery_spot()->first()->name}})
              @else
                @if ($order->destination()->first() == NULL)
                En livraison (destination inconnue)
                @else
                En livraison ({{$order->destination()->first()->city}})
                @endif
              @endif
            </th>
            <th>
              {!! Html::getReadableOrderStatus($order->status) !!}
            </th>
            <th>{{ Html::euros($order->already_paid) }}
            @if ($order->payment_way != NULL)
              <?php $ways = Config::get('bdxnbx.payment_ways'); ?>
              ({{$ways[$order->payment_way]}})
            @endif
            </th>
            <th>{{ Html::euros($order->unity_and_fees_price) }}</th>
            <th>
              {!! Html::boolYesOrNo($order->gift) !!}
            </th>
            <th>
              {!! Html::getReadableOrderLocked($order->locked) !!}
            </th>
            <th>{{ Html::getReadableEmpty(Html::dateFrench($order->date_completed, true)) }}</th>
            <th>{{ Html::getReadableEmpty(Html::dateFrench($order->created_at, true)) }}</th>

            <th>

            <a href="{{ action('MasterBox\Admin\OrdersController@getEdit', ['id' => $order->id]) }}" class="button button__table"><i class="fa fa-eye"></i></button>

            @if ($order->status != 'canceled')
              <a class="button button__table js-tooltip" title="Annuler" href="{{ action('MasterBox\Admin\OrdersController@getConfirmCancel', ['id' => $order->id]) }}"><i class="fa fa-gavel"></i></a>
            @endif

            <a class="button button__table js-confirm-delete" href="{{ action('MasterBox\Admin\OrdersController@getDelete', ['id' => $order->id]) }}"><i class="fa fa-trash"></i></a>

            </th>

          </tr>

        @endforeach

        </tbody>

      </table>
  @else
    <p>Aucune livraison</p>
  @endif
  
  <div class="+spacer-small"></div>

  <div class="panel">
    <div class="panel__wrapper">
      <div class="panel__header">
        <h3 class="panel__title">Adresse de facturation (actuelle)</h3>
      </div>
      <div class="panel__content typography">

        <div class="spyro-well">

          <strong>{{$customer->last_name}} {{$customer->first_name}}</strong><br />
          @if ($customer->city == NULL && $customer->zip == NULL && $customer->address == NULL)
            Aucun détails sur l'adresse de facturation.<br/>
          @else
            {{$customer->city}}, {{$customer->zip}}<br />
            {{$customer->address}}<br />
          @endif
          <a class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id]) }}">Editer</a><br />

        </div>

        <div class="spacer40"></div>

        {!! Html::info("Un changement de région implique un changement de prix de livraison, le formulaire côté administrateur a été laissé libre (celui côté utilisateur est bloqué) ; il faudra manuellement donner ou récupérer la différence avec l'utilisateur via Stripe.") !!}
      </div>
    </div>
  </div>

@stop