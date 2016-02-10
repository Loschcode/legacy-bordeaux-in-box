@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_deliveries_focus')
@stop

@section('content')
<div class="row">
  <div class="grid-12">
    <h1 class="title title__section">Série {{ Html::dateFrench($series->delivery, true) }} (#{{$series->id}})</h1>
    <h3 class="title title__subsection">Points relais</h3>
  </div>
</div>

<div class="divider divider__section"></div>

{!! Html::info("Veuillez vérifier que les commandes actives reliées sont le même nombre que celles déjà livrées sur le point relais avant d'envoyer un email de confirmation. Le système se base sur les commandes effectivement reliées pour envoyer cette confirmation.") !!}

<table class="js-datatable-simple">

  <thead>

    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Commandes actives reliées (pour la série)</th>
      <th>Commandes livrées reliées (pour la série)</th>
      <th>Téléchargements</th>
      <th>Action</th>
    </tr>

  </thead>

  <tbody>

    @foreach ($spots as $spot)

    <tr>
      <th>{{$spot->id}}</th>
      <th>{{$spot->name}}</th>
      <th>{{$spot->getSeriesOrders($series)->count()}}</th>
      <th>{{$spot->getDeliveredSeriesOrders($series)->count()}}</th>
      <th>
        <a class="button button__link" href="{{ url('/admin/deliveries/download-csv-orders-from-series-and-spot/' . $series->id . '/' . $spot->id) }}">Commandes (CSV)</a>
      </th>
      <th>
        <a title="Affiche le listing des commandes pour ce spot" class="button button__table js-tooltip" href="{{ action('MasterBox\Admin\DeliveriesController@getListingOrdersFromSeriesAndSpot', ['series_id' => $series->id, 'spot_id' => $spot->id]) }}"><i class="fa fa-eye"></i></a>
        <a title="Envoyer les emails pour confirmer les livraisons au point relais ?" class="button button__table js-tooltip" href="{{ action('MasterBox\Admin\EmailManagerController@getSendEmailToSeriesSpotOrders', ['series_id' => $series->id, 'spot_id' => $spot->id]) }}"><i class="fa fa-envelope"></i></a>

      </th>
    </tr>

    @endforeach

  </tbody>

</table>
@stop