@extends('masterbox.layouts.admin')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.admin.profiles.deliveries'
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
    <h2 class="title title__subsection">Livraisons</h2>
  </div>
  <div class="grid-4">
    <div class="+text-right">
      <a class="button button__section" href="{{ action('MasterBox\Admin\ProfilesController@getAddDelivery', ['id' => $profile->id]) }}"><i class="fa fa-plus"></i> Ajouter une livraison</a>
    </div>
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
      <th>Type de paiement</th>
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
      <th>
      @if ($order->delivery_serie()->first() !== NULL)
      {{ Html::dateFrench($order->delivery_serie()->first()->delivery, true) }}
      @else
      Non assigné
      @endif
      </th>
      <th>
        @if ($order->take_away)
        Point relais (

        @if ($order->delivery_spot()->first() !== NULL)
        {{$order->delivery_spot()->first()->name}}
        @else
        Inconnu
        @endif

        )
        @else
        @if ($order->destination()->first() == NULL)
        En livraison (destination inconnue)
        @else
        En livraison ({{$order->destination()->first()->city}})
        @endif
        @endif
      </th>
      <th data-order-id="{{ $order->id }}" data-token="{{ csrf_token() }}">
        {{ Form::select('order_status', generate_status_form(), $order->status, ['class' => 'js-chosen', 'data-width' => '150px']) }} 
        <div class="js-loader"></div>
      </th>
      <th>

      {{ Html::euros($order->already_paid) }}

      @if ($order->already_paid < $order->unity_and_fees_price)

      <a class="button button__default --green --table js-tooltip js-confirm" data-confirm-text="Considérer comme payé" title="Payé" href="{{ action('MasterBox\Admin\OrdersController@getFulfilAlreadyPaid', ['id' => $order->id]) }}"><i class="fa fa-arrow-up"></i></a>

      @else

      <a class="button button__default --red --table js-tooltip js-confirm" data-confirm-text="Artificiellement vider le paiement" title="Impayé" href="{{ action('MasterBox\Admin\OrdersController@getEmptyAlreadyPaid', ['id' => $order->id]) }}"><i class="fa fa-arrow-down"></i></a>
      
      @endif

      </th>
      <th>{{ Html::euros($order->unity_and_fees_price) }}</th>
      <th>

        @if ($order->payment_way === NULL)
        N/A
        @else
          
          <div data-order-id="{{ $order->id }}" data-token="{{ csrf_token() }}">
            {{ Form::select('order_payment_way', Config::get('bdxnbx.payment_ways'), $order->payment_way, ['class' => 'js-chosen', 'data-width' => '130px']) }}
            <div class="js-loader"></div>
          </div>

        @endif

      </th>
      <th>
        {!! Html::boolYesOrNo($order->gift) !!}
      </th>
      <th>
        {!! Html::getReadableOrderLocked($order->locked) !!}
      </th>
      <th>{{ Html::getReadableEmpty(Html::dateFrench($order->date_completed, true)) }}</th>
      <th>{{ Html::getReadableEmpty(Html::dateFrench($order->created_at, true)) }}</th>

      <th>

          @if ($order->status != 'canceled')
          <a class="button button__default --red --table js-tooltip js-confirm" data-confirm-text="Cette livraison va être annulée" title="Annuler" href="{{ action('MasterBox\Admin\OrdersController@getConfirmCancel', ['id' => $order->id]) }}"><i class="fa fa-gavel"></i></a>
          @endif

          <a class="button button__default --red --table js-confirm-delete" href="{{ action('MasterBox\Admin\OrdersController@getDelete', ['id' => $order->id]) }}"><i class="fa fa-trash"></i></a>

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
      <div class="panel__content">
        <div class="typography">
          <strong>{{$customer->last_name}} {{$customer->first_name}}</strong><br />
          @if ($customer->city == NULL && $customer->zip == NULL && $customer->address == NULL)
          Aucun détails sur l'adresse de facturation.<br/>
          @else
          {{$customer->city}}, {{$customer->zip}}<br />
          {{$customer->address}}
          @endif
        </div>

        <a class="button button__default --green" href="{{ action('MasterBox\Admin\CustomersController@getEdit', ['id' => $customer->id]) }}"><i class="fa fa-pencil"></i> Editer</a>

      </div>
    </div>
  </div>
  
  <div class="+spacer"></div>

  <div class="panel">
    <div class="panel__wrapper">
      <div class="panel__header">
        <h3 class="panel__title">Adresse de livraison (actuelle)</h3>
      </div>
      <div class="panel__content">
        @if ($order_destination != NULL)

          <div class="form">

          {!! Form::open(['action' => 'MasterBox\Admin\ProfilesController@postEditDelivery']) !!}
          
            {!! Form::hidden('customer_profile_id', $profile->id) !!}
            
              {!! Form::label("destination_first_name", "Prénom", ['class' => 'form__label']) !!}
              {!! Form::text("destination_first_name", ($order_destination->first_name) ? $order_destination->first_name : Request::old("destination_first_name"), ['class' => 'form__input']) !!}<br/>
              {!! Html::checkError('destination_first_name', $errors) !!}
            
              {!! Form::label("destination_last_name", "Nom", ['class' => 'form__label']) !!}
              {!! Form::text("destination_last_name", ($order_destination->last_name) ? $order_destination->last_name : Request::old("destination_last_name"), ['class' => 'form__input']) !!}<br />
              {!! Html::checkError('destination_last_name', $errors) !!}

               
              {!! Form::label("destination_city", "Ville") !!}
              {!! Form::text("destination_city", ($order_destination->city) ? $order_destination->city : Request::old("destination_city"), ['class' => 'form__input']) !!}<br/>
              {!! Html::checkError('destination_city', $errors) !!}


              {!! Form::label("destination_zip", "Code postal", ['class' => 'form__label']) !!}
              {!! Form::text("destination_zip", ($order_destination->zip) ? $order_destination->zip : Request::old("destination_zip"), ['class' => 'form__input']) !!}<br />
              {!! Html::checkError('destination_zip', $errors) !!}

          
              {!! Form::label("destination_address", "Adresse", ['class' => 'form__label']) !!}
              {!! Form::textarea("destination_address", ($order_destination->address) ? $order_destination->address : Request::old("destination_address"), ['class' => 'form__input']) !!}<br />
              {!! Html::checkError('destination_address', $errors) !!}

              {!! Form::label("destination_address_detail", "Complément d'adresse", ['class' => 'form__label']) !!}
              {!! Form::textarea("destination_address_detail", ($order_destination->address_detail) ? $order_destination->address_detail : Request::old("destination_address_detail"), ['class' => 'form__input']) !!}<br />
              {!! Html::checkError('destination_address_detail', $errors) !!}

            
            <div class="spacer20"></div>

            {!! Form::submit("Valider", ['class' => 'button button__default']) !!}

          {!! Form::close() !!}

          </div>

        @else
          <div class="typography">
            Aucun détails disponible
          </div>
          <div class="+spacer-extra-small"></div>
          <a class="button button__default" href="{{ action('MasterBox\Admin\ProfilesController@getGenerateDeliveryAddress', ['profile_id' => $profile->id]) }}">Générer l'adresse de livraison depuis la facturation</a>
        @endif
        </div>
    </div>
  </div>

  <div class="+spacer"></div>
  
  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title">Point relais (actuel)</h3>
    </div>
    <div class="panel__content">
      
      <div class="labelauty-default-small">

        @if ($order_delivery_spot != NULL)

        {!! Form::open(['action' => 'MasterBox\Admin\ProfilesController@postEditSpot']) !!}
      
          {!! Form::hidden('customer_profile_id', $profile->id) !!}

          @foreach ($delivery_spots as $delivery_spot)

            @if ($delivery_spot->id == $order_delivery_spot->id)
              {!! Form::radio('selected_spot', $delivery_spot->id, true, ['id' => $delivery_spot->id, 'data-labelauty' => $delivery_spot->name]) !!}
            @else
              {!! Form::radio('selected_spot', $delivery_spot->id, false, ['id' => $delivery_spot->id, 'data-labelauty' => $delivery_spot->name]) !!}
            @endif
            
          @endforeach

          <br />

          {!! Form::submit("Valider", ['class' => 'button button__default']) !!}

        {!! Form::close() !!}


        @else
          
          <strong>Aucun point relais n'est encore défini.</strong><br />

          {!! Form::open(['action' => 'MasterBox\Admin\ProfilesController@postEditSpot']) !!}
        
          @foreach ($delivery_spots as $delivery_spot)

            {!! Form::hidden('customer_profile_id', $profile->id) !!}

                {!! Form::radio('selected_spot', $delivery_spot->id, false, ['id' => $delivery_spot->id, 'data-labelauty' => $delivery_spot->name]) !!}


          @endforeach

          {!! Form::submit("Valider", ['class' => 'button button__default']) !!}
  
          {!! Form::close() !!}

        @endif
      </div>
    </div>
  </div>

  @stop