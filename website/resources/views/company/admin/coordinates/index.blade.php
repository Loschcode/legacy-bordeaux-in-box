@extends('company.layouts.admin')

@section('content')
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section"><i class="fa fa-location-arrow"></i> Coordonnées</h1>
    </div>
  </div>

  <div class="divider divider__section"></div>

  {!! Html::info('Gestion des coordonnées dans le système') !!}

  <table class="js-datatable-simple">

    <thead>

      <tr>
        <th></th>
        <th>ID</th>
        <th>Liaisons</th>
        <th>Adresse</th>
        <th>Complément d'adresse</th>
        <th>Code postal</th>
        <th>Ville</th>
        <th>Pays</th>
        <th>Google Place ID</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Création</th>
        <th>Dernière édition</th>
        <th>Action</th>
      </tr>

    </thead>

    <tbody>

      @foreach ($coordinates as $coordinate)

        <tr
          data-stripe-customer="" 
          data-stripe-event="" 
          data-stripe-charge="" 
          data-stripe-card=""
        >
          <th class="js-more"><a href="#" class="button button__table"><i class="fa fa-plus-square-o"></i></a></th>

          <th>{{$coordinate->id}}</th>
          <th>

          @foreach ($coordinate->company_billings()->get() as $company_billing)
            <a target="_blank" href="{{ action('Company\Guest\BillingController@getWatch', ['encrypted_access' => $company_billing->encrypted_access]) }}">Facture #{{$company_billing->id}}</a> |
          @endforeach

          @foreach ($coordinate->customers()->get() as $customer)
          <a target="_blank" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id])}}">Client #{{$customer->id}}</a>
          @endforeach


          @foreach ($coordinate->customer_order_buildings()->get() as $customer_order_building)
          <a href="#">Building #{{$customer_order_building->id}}</a>
          @endforeach

          @foreach ($coordinate->delivery_spots()->get() as $delivery_spot)
          <a target="_blank" href="{{ action('MasterBox\Admin\SpotsController@getEdit', ['id' => $delivery_spot->id])}}">Point relais #{{$delivery_spot->id}}</a>
          @endforeach

          @foreach ($coordinate->order_billings()->get() as $order_billing)
          <a target="_blank" href="#">Facturation #{{$order_billing->id}}</a>
          @endforeach

          @foreach ($coordinate->order_destinations()->get() as $order_destination)
          <a target="_blank" href="#">Destination #{{$order_destination->id}}</a>
          @endforeach

          </th>
          <th>{{$coordinate->address}}</th>
          <th>{{$coordinate->address_detail}}</th>
          <th>{{$coordinate->zip}}</th>
          <th>{{$coordinate->city}}</th>
          <th>{{$coordinate->country}}</th>
          <th>{{$coordinate->place_id}}</th>
          <th>{{$coordinate->latitude}}</th>
          <th>{{$coordinate->longitude}}</th>

          <th>{{ Html::dateFrench($coordinate->created_at, true) }}</th>
          <th>{{ Html::dateFrench($coordinate->updated_at, true) }}</th>

          <th>

          <a class="button button__default --blue --table" href="{{ action('Company\Admin\CoordinatesController@getEdit', ['id' => $coordinate->id]) }}"><i class="fa fa-pencil"></i></a>

          </th>

        </tr>

      @endforeach

      </tbody>

    </table>
@stop
