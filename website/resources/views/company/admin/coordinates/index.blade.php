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

        <tr>

          <th>{{$coordinate->id}}</th>
          <th>

          <a data-modal class="button button__default --green --table" href="{{ action('Company\Admin\CoordinatesController@getLinks', ['id' => $coordinate->id]) }}">
          {{$coordinate->getLinks()}}</a>
          
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
