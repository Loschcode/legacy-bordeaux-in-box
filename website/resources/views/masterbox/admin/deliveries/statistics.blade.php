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


<!-- Daily statistics -->
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
          
          @if (!isset($daily_statistic['new_customers']))
          N/A
          @else
          {{$daily_statistic['new_customers']}}
          @endif

          </th>

          <th>

          @if (!isset($daily_statistic['order_buildings']))
          N/A
          @else
          {{$daily_statistic['order_buildings']}}
          @endif

          </th>

          <th>
          
          @if (!isset($daily_statistic['new_subscriptions']))
          N/A
          @else
          {{$daily_statistic['new_subscriptions']}}
          @endif

          </th>

      </tr>

    @endforeach

  </tbody>

</table>

<!-- Hourly statistics -->
<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Heure</th>
      <th>Nouveaux inscrits</th>
      <th>Commandes engagées</th>
      <th>Commandes finalisées</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($hourly_statistics as $hour => $hourly_statistic)

      <tr>
        <th><strong>{{$hour}} heure(s)</strong></th>

          <th>
          
          @if (!isset($hourly_statistic['new_customers']))
          0
          @else
          {{$hourly_statistic['new_customers']}}
          @endif

          </th>

          <th>

          @if (!isset($hourly_statistic['order_buildings']))
          0
          @else
          {{$hourly_statistic['order_buildings']}}
          @endif

          </th>

          <th>
          
          @if (!isset($hourly_statistic['new_subscriptions']))
          0
          @else
          {{$hourly_statistic['new_subscriptions']}}
          @endif

          </th>

      </tr>

    @endforeach

  </tbody>

</table>

<!-- Price statistics -->
<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Prix</th>
      <th>Nombre de commandes</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($price_statistics as $price => $price_statistic)

      <tr>
        <th><strong>{{euros($price)}}</strong></th>

          <th>
          
          @if (!isset($price_statistic))
          0
          @else
          {{$price_statistic}}
          @endif

          </th>

      </tr>

    @endforeach

  </tbody>

</table>


<!-- Geo statistics -->
<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Zone</th>
      <th>Nombre de commandes</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($geo_statistics as $geo => $geo_statistic)

      <tr>
        <th><strong>{{$geo}}</strong></th>

          <th>
          
          @if (!isset($geo_statistic))
          0
          @else
          {{$geo_statistic}}
          @endif

          </th>

      </tr>

    @endforeach

  </tbody>

</table>
@stop