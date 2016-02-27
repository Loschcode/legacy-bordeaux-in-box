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
      <th>Nouveaux inscrits</th>
      <th>Commandes engagées</th>
      <th>Commandes finalisées</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($daily_statistics as $day => $daily_statistic)

      <tr>
        <th><strong>{{$day}}</strong></th>

          <th>
          
          {{$daily_statistic['new_customers']}}

          </th>

          <th>
          
          {{$daily_statistic['order_buildings']}}

          </th>

          <th>
          
          {{$daily_statistic['new_subscriptions']}}

          </th>

      </tr>

    @endforeach

  </tbody>

</table>

@stop