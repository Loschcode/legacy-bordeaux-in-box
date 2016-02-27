@extends('masterbox.layouts.admin')

@section('navbar')
@include('masterbox.admin.partials.navbar_deliveries_focus')
@stop

@section('content')
<div class="row">
  <div class="grid-12">
    <h1 class="title title__section">Série {{ Html::dateFrench($series->delivery, true) }} (#{{$series->id}})</h1>
    <h3 class="title title__subsection">Statistiques</h3>
  </div>
</div>

<div class="divider divider__section"></div>

{!! Html::info("Statistiques détaillées pour la série") !!}

<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Jour</th>
      <th>Création de compte avec les non finalisés</th>
      <th>Création de compte suivi de commandes uniquement</th>
      <th>Commandes non annulées</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($daily_statistics as $day => $daily_statistic)

      <tr>
        <th><strong>{{$day}}</strong></th>

          <th>
          
          {{$daily_statistic['account_creation_with_unfinished']}}

          </th>

          <th>
          
          {{$daily_statistic['account_creation_only_finished']}}

          </th>


          <th>
          
          {{$daily_statistic['not_canceled_orders']}}

          </th>

      </tr>

    @endforeach

  </tbody>

</table>

@stop