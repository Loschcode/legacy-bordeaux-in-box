@extends('layouts.master')

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

            <?php ( ! Input::has('spot') && ! Input::has('to_send')) ? $class = '--primary' : $class = '' ?>
            <a class="button --default --lg --block center {{ $class }}" href="{{ URL::route('easygo', array_merge($current_query, ['spot' => '', 'to_send' => ''])) }}">Tout</a>

            <div class="spacer --xs"></div>


            @foreach ($spots as $spot)
              <?php (Input::has('spot') && Input::get('spot') == $spot) ? $class = '--primary' : $class = '' ?>
              <a class="button --default --lg --block center {{ $class }}" href="{{ URL::route('easygo', array_merge($current_query, ['spot' => $spot, 'to_send' => ''])) }}">{{ DeliverySpot::find($spot)->name }}</a>
              <div class="spacer --xs"></div>
            @endforeach

            <?php (Input::has('to_send')) ? $class = '--primary' : $class = '' ?>
            <a class="button --default --lg --block center {{ $class }}" href="{{ URL::route('easygo', array_merge($current_query, ['to_send' => 'true', 'spot' => ''])) }}">La Poste</a><br/>

          </div>
          <div class="col-md-9">

            <?php ( ! Input::has('show') || (Input::has('show') && Input::get('show') == 'list')) ? $class = '--primary' : $class = '' ?>
            <a href="{{ URL::route('easygo', array_merge($current_query, ['show' => 'list'])) }}" class="button --default --xl {{ $class }}"><i class="fa fa-list"></i> Résumé</a>

            <?php (Input::has('show') && Input::get('show') == 'grid') ? $class = '--primary' : $class = '' ?>
            <a href="{{ URL::route('easygo', array_merge($current_query, ['show' => 'grid'])) }}" class="button --default --xl {{ $class }}"><i class="fa fa-th-large"></i> Détails</a>

            <div class="spacer --sm"></div>

            <h1 class="title">{{ count($orders_filtered) }} Commandes</h1>

            <p>
              @foreach ($kind_boxes as $box)

                <a class="button --disabled --default --sm" style="margin-right: 5px">
                  {{ Box::find($box)->title }}:

                  {{ $orders_filtered->filter(function($item) use($box) {
                    return $item->box_id == $box;
                  })->count() }}&nbsp;&nbsp;
                </a>

              @endforeach

            </p>

            <!-- Ready button-->
            @if (count($orders_filtered) > 0)
              <div>
                <div class="spacer"></div>

                @if (Input::has('spot'))
                  <a href="{{ url('admin/orders/ready-spot/' . Input::get('spot')) }}" class="button --success --xl">Tout est prêt pour {{ DeliverySpot::find(Input::get('spot'))->name }}</a>
                @endif

                @if (Input::has('to_send'))
                  <a href="{{ url('admin/orders/ready-no-take-away') }}" class="button --success --xl">Tout est prêt pour La Poste</a>
                @endif

                @if ( ! Input::has('to_send') && ! Input::has('spot'))
                  <a href="{{ url('admin/orders/everything-is-ready') }}" class="button --success --xl">Tout est prêt (les dépôts et la poste)</a>

                @endif
                <div class="spacer"></div>
              </div>
            @endif

            @if (Input::has('show') && Input::get('show') == 'list')
              @include('easygo.partials.list', array('orders_filtered' => $orders_filtered))
            @else
              @include('easygo.partials.grid', array('orders_filtered' => $orders_filtered))
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
