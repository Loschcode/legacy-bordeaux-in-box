@extends('masterbox.layouts.admin')

@section('content')

  <div class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1 class="header__logo">EasyGo</h1>
          <h2 class="header__title">Système d'emballage</h2>
        </div>
        <div class="col-md-6 right">
          <span class="header__stats">{{ count($orders_completed) }} Préparées / {{ count($raw_orders) }} Commandes</span>
        </div>
      </div>
    </div>
  </div>

  @if (count($orders_completed) != count($raw_orders))
      @if (count($unpaid) > 0)
        <div class="container">

          <div class="spacer"></div>
          <div class="flash">
            <div class="row">
              <div class="col-md-1 right">
                <i class="fa fa-warning"></i>
              </div>
              <div class="col-md-11">
                Attention ! Il reste encore {{ count($unpaid) }} commandes non payées.
                <div class="spacer --xs"></div>
                Il se peut que ce soit des commandes offertes, un retard de prélèvement, etc ...<br/>
                Ces commandes sont mises en valeur et sont prises en compte dans l'emballage.
              </div>
            </div>
          </div>
        </div>

      @endif

      <div class="spacer"></div>

      <div class="container">
        <div class="row">
          <div class="col-md-3">

            <h1 class="title">Dépôts</h1>

            <?php ( ! Request::has('spot') && ! Request::has('to_send')) ? $class = '--primary' : $class = '' ?>
            <a class="button --default --lg --block center {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['spot' => '', 'to_send' => ''])) }}">Tout</a>

            <div class="spacer --xs"></div>


            @foreach ($spots as $spot)
              <?php (Request::has('spot') && Request::get('spot') == $spot) ? $class = '--primary' : $class = '' ?>
              <a class="button --default --lg --block center {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['spot' => $spot, 'to_send' => ''])) }}">{{ App\Models\DeliverySpot::find($spot)->name }}</a>
              <div class="spacer --xs"></div>
            @endforeach

            <?php (Request::has('to_send')) ? $class = '--primary' : $class = '' ?>
            <a class="button --default --lg --block center {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['to_send' => 'true', 'spot' => ''])) }}">La Poste</a><br/>

          </div>
          <div class="col-md-9">

            <?php ( ! Request::has('show') || (Request::has('show') && Request::get('show') == 'list')) ? $class = '--primary' : $class = '' ?>
            <a href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['show' => 'list'])) }}" class="button --default --xl {{ $class }}"><i class="fa fa-list"></i> Résumé</a>

            <?php (Request::has('show') && Request::get('show') == 'grid') ? $class = '--primary' : $class = '' ?>
            <a href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['show' => 'grid'])) }}" class="button --default --xl {{ $class }}"><i class="fa fa-th-large"></i> Détails</a>

            <div class="spacer --sm"></div>

            <h1 class="title">{{ count($orders_filtered) }} Commandes</h1>


            <!-- Ready button-->
            @if (count($orders_filtered) > 0)
              <div>
                <div class="spacer"></div>

                @if (Request::has('spot'))
                  <a href="{{ url('admin/orders/ready-spot/' . Request::get('spot')) }}" class="button --success --xl">Tout est prêt pour {{ App\Models\DeliverySpot::find(Request::get('spot'))->name }}</a>
                @endif

                @if (Request::has('to_send'))
                  <a href="{{ url('admin/orders/ready-no-take-away') }}" class="button --success --xl">Tout est prêt pour La Poste</a>
                @endif

                @if ( ! Request::has('to_send') && ! Request::has('spot'))
                  <a href="{{ url('admin/orders/everything-is-ready') }}" class="button --success --xl">Tout est prêt (les dépôts et la poste)</a>

                @endif
                <div class="spacer"></div>
              </div>
            @endif

            @if (Request::has('show') && Request::get('show') == 'list')
              @include('masterbox.admin.easygo.partials.list', array('orders_filtered' => $orders_filtered))
            @else
              @include('masterbox.admin.easygo.partials.grid', array('orders_filtered' => $orders_filtered))
            @endif



          </div>
        </div>
      </div>
  @else

    <div class="center">
      <div class="spacer"></div>
      <h1 class="title">BRAVO ! Il ne reste plus qu'a cliquer sur le lien en dessous</h1>

      <div class="spacer"></div>
      <a href="{{ url('admin/orders/everything-has-been-sent') }}" class="button --primary --lg">Tout est envoyé !</a>
    </div>
  @endif



@stop
