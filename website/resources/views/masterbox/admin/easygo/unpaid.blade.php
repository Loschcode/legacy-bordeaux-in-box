@extends('masterbox.layouts.admin')

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">EasyGo</h1>
    <h3 class="title title__subsection">Commandes non payées série {{ Html::dateFrench($serie->delivery, true) }} (#{{ $serie->id }})</h3>
  </div>
</div>

<div class="divider divider__section"></div>

{!! Html::info('Attention, il reste des commandes non payées !') !!}

  <table class="js-datatable-simple">
      <thead>
        <tr>
          <th>Client</th>
          <th>Téléphone</th>
          <th>Paiement</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($orders as $order)
            
            @if ($order->hasProblemPayment())
              <tr>
                <td>{{ $order->customer()->first()->getFullName() }}</td>
                <td>{{ $order->customer()->first()->phone }}</td>
                <td>{{ $order->already_paid }}&euro; / {{ $order->unity_and_fees_price }}&euro; <br/> {{ $order->payments()->count() }} tentative(s) de paiement</td>
                <td>
                  <a target="_blank" class="button button__default --table --green" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $order->customer_profile()->first()->id]) }}"><i class="fa fa-external-link"></i> En savoir plus</a>
                  <a data-confirm-text="La commande sera annulée" class="button button__default --table --red js-confirm" href="{{ url('/admin/orders/confirm-cancel/' . $order->id) }}">Annuler Commande</a>
                </td>
              </tr>
            @endif

        @endforeach
      </tbody>
    </table>
</div>

@stop

<?php /*
@extends('masterbox.layouts.easygo')

@section('content')

  <div class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-10">
          <h1 class="header__logo">EasyGo</h1>
          <h2 class="header__title">Commandes non payées</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="spacer"></div>
    <div class="flash">
      <div class="row">
        <div class="col-md-1 right">
          <i class="fa fa-warning"></i>
        </div>
        <div class="col-md-11">
          Attention ! Il reste des commandes non payées.
          <div class="spacer --xs"></div>
          La liste ci-dessous affiche toutes les commandes pour lesquelles Stripe a tenté de prélever la carte sans succès. <br/>
          Merci de traiter la liste suivante avant de pouvoir commencer l'emballage.
        </div>
      </div>
    </div>

    <div class="spacer"></div>

    <table class="listing">
        <thead>
          <tr class="listing__heading">
            <th>Client</th>
            <th>Téléphone</th>
            <th>Paiement</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="listing__content">

          @foreach ($orders as $order)
              
              @if ($order->hasProblemPayment())
                <tr>
                  <td>{{ $order->customer()->first()->getFullName() }}</td>
                  <td>{{ $order->customer()->first()->phone }}</td>
                  <td>{{ $order->already_paid }}&euro; / {{ $order->unity_and_fees_price }}&euro; <br/> {{ $order->payments()->count() }} tentative(s) de paiement</td>
                  <td>
                    <a target="_blank" class="button --default --sm" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $order->customer_profile()->first()->id]) }}">En savoir plus</a>
                    <div class="spacer --sm"></div>
                    <a class="button --danger --sm" href="{{ url('/admin/orders/confirm-cancel/' . $order->id) }}">Annuler Commande</a>
                  </td>
                </tr>
              @endif

          @endforeach
        </tbody>
      </table>
  </div>


@stop

*/ ?>
