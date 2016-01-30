@extends('masterbox.layouts.master')

@section('content')
  <div class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h1 class="header__logo">EasyGo</h1>
          <h2 class="header__title">Série {{ $serie->delivery }}</h2>
        </div>
        <div class="col-md-8 right">
          <span class="header__stats"><i class="fa fa-gift"></i> {{ $count_orders }} / {{ $serie->goal }} d'objectif</span>
          <a href="{{ url('/admin/deliveries/lock/' . $serie->id) }}" class="button --primary --lg">Bloquer la série et commencer l'emballage</a>

        </div>
      </div>
    </div>
  </div>

  <div class="container">

    <div class="spacer"></div>

    @if (count($unpaid) > 0)

      <h1 class="title">Commandes Non payées ({{ count($unpaid) }})</h1>

      <table class="listing">
        <thead>
          <tr class="listing__heading">
            <th>Nom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th></th>
          </tr>
        </thead>
        <tbody class="listing__content">
          @foreach ($unpaid as $order)
            <tr>
              <td>{{ $order->customer()->first()->getFullName() }}</td>
              <td>{{ $order->customer()->first()->phone }}</td>
              <td>{{ $order->customer()->first()->email }}</td>
              <td><a target="_blank" class="button --sm --default" href="{{ action('MasterBox\Admin\ProfilesController@getEdit', ['id' => $order->customer_profile()->first()->id]) }}">En savoir plus</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="spacer"></div>

    @endif

    <div class="row">

      <div class="col-md-6">
        <h1 class="title">Boxes</h1>

        <table class="listing">
          <thead>
            <tr class="listing__heading">
              <th>Nombre de commandes</th>
            </tr>
          </thead>
          <tbody class="listing__content">
          @foreach ($boxes as $box)
            <tr>
              <td>{{ App\Models\DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->count() }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <div class="col-md-6">
        <h1 class="title">Divers</h1>

        <table class="listing">
          <thead>
            <tr class="listing__heading">
              <th>Label</th>
              <th>Nombre</th>
            </tr>
          </thead>
          <tbody class="listing__content">
            <tr>
              <td>Marraines</td>
              <td>{{ $count_sponsors['sponsors'] }}</td>
            </tr>
            <tr>
              <td>Filleules</td>
              <td>{{ $count_sponsors['has_sponsors'] }}</td>
            </tr>
            <tr>
              <td>Anniversaire</td>
              <td>{{ $count_birthdays }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="spacer"></div>

    <h1 class="title">Divers</h1>

    <table class="listing">
      <thead>
        <tr class="listing__heading">
          <th>Lieu de livraison</th>
          <th>Nombre</th>
        </tr>
      </thead>
      <tbody class="listing__content">
        <?php $i = 0 ?>

        @foreach ($spots as $spot)

          <?php $count = App\Models\DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->where('take_away', true)->where('delivery_spot_id', $spot)->count() ?>
          <?php $i = $i + $count ?>
          <tr>
            <td>
              {{ App\Models\DeliverySpot::find($spot)->name}}
            </td>
            <td>
              {{ $count }}
            </td>
          </tr>
        @endforeach
        <tr>
          <td>En envoi</td>
          <td>{{ $count_not_take_away }}</td>
        </tr>
        <tr>
          <td><em>Dont hors 33</em></td>
          <td><em>{{ $count_not_take_away_not_33 }}</em></td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td><strong><?php echo $i + $count_not_take_away ?></strong></td>
        </tr>
      </tbody>
    </table>

    </div>

    <div class="spacer"></div>

  </div>
@stop
