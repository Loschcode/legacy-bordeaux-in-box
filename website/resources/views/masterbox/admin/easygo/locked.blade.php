@extends('masterbox.layouts.admin')

@section('content')


  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">EasyGo</h1>
      <h3 class="title title__subsection">Série {{ Html::dateFrench($serie->delivery, true) }} (#{{ $serie->id }})</h3>
    </div>
    <div class="grid-4 +text-right">
      <a href="{{ action('MasterBox\Admin\DeliveriesController@getLock', ['id' => $serie->id]) }}" class="button button__section --blue">Bloquer la série et commencer l'emballage</a>
    </div>
  </div>
  
  <div class="divider divider__section"></div>



  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h3 class="panel__title">Statistiques</h3>
    </div>
    <div class="panel__content">
      <div class="typography">
        <strong>Nombre de commandes:</strong> {{ $count_total_orders }} / {{ $serie->goal }} d'objectif<br/>
        <strong>Anniversaires:</strong> {{ $count_birthdays }}<br/>
        <strong>En livraison:</strong> {{ $count_not_take_away }}<br/>
        <strong>En point relais:</strong> {{ $count_total_orders - $count_not_take_away }}
      </div>

      <table class="js-datatable-simple">
        <thead>
          <tr>
            <th>Id</th>
            <th>Lieu de livraison</th>
            <th>Nombre de commandes</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($spots as $spot)

            <?php $count = App\Models\DeliverySerie::nextOpenSeries()->first()->orders()->notCanceledOrders()->where('take_away', true)->where('delivery_spot_id', $spot)->count() ?>
            <tr>
              <td>{{ $spot }}</td>
              <td>
                {{ App\Models\DeliverySpot::find($spot)->name}}
              </td>
              <td>
                {{ $count }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>

  <div class="+spacer-small"></div>
    @if (count($unpaid) > 0)
      
      <div class="panel panel__wrapper">
        <div class="panel__header">
          <h3 class="panel__title">Commandes non payées pour le moment: {{ count($unpaid) }}</h3>
        </div>
        <div class="panel__content">
          <table class="js-datatable-simple">
            <thead>
              <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="listing__content">
              @foreach ($unpaid as $order)
                <tr>
                  <td>{{ $order->customer()->first()->id }}</td>
                  <td>{{ $order->customer()->first()->getFullName() }}</td>
                  <td>{{ $order->customer()->first()->phone }}</td>
                  <td>{{ $order->customer()->first()->email }}</td>
                  <td><a target="_blank" class="button button__default --green --table" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $order->customer_profile()->first()->id]) }}">En savoir plus</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  
@stop
