@extends('masterbox.layouts.admin')

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">EasyGo</h1>
      <h3 class="title title__subsection">Système d'emballage</h3>
    </div>
    <div class="grid-4 +text-right">
      <div class="easygo__counter">{{ count($orders_completed) }} Préparées / {{ count($raw_orders) }} Commandes</div>
    </div>
  </div>

  <div class="divider divider__section"></div>

  @if (count($orders_completed) != count($raw_orders))
      @if (count($unpaid) > 0)

      <div class="easygo__unpaid">
        Attention ! Il reste encore {{ count($unpaid) }} commandes non payées.
        Il se peut que ce soit des commandes offertes, un retard de prélèvement, etc ...<br/>
        Ces commandes sont mises en valeur et sont prises en compte dans l'emballage.
      </div>

      @endif

        <div class="row">
          <div class="grid-3">
            
            <div class="panel panel__wrapper">
              <div class="panel__header">
                <h1 class="panel__title">Dépôts</h1>
              </div>
              <div class="panel__content">
                
                <?php ( ! Request::has('spot') && ! Request::has('to_send')) ? $class = '--active' : $class = '' ?>
                <a class="easygo__link {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['spot' => '', 'to_send' => ''])) }}">Tout</a>


                @foreach ($spots as $spot)
                  <?php (Request::has('spot') && Request::get('spot') == $spot) ? $class = '--active' : $class = '' ?>
                  <a class="easygo__link {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['spot' => $spot, 'to_send' => ''])) }}">{{ App\Models\DeliverySpot::find($spot)->name }}</a>
                @endforeach

                <?php (Request::has('to_send')) ? $class = '--active' : $class = '' ?>
                <a class="easygo__link {{ $class }}" href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['to_send' => 'true', 'spot' => ''])) }}">La Poste</a><br/>
              </div>
            </div>

          </div>
          <div class="grid-9">
            

            
            <div class="row">
              <div class="grid-3">
                <?php (Request::has('show') && Request::get('show') == 'list') ? $class = '--active' : $class = '' ?>
                <a href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['show' => 'list'])) }}" class="button__default --green {{ $class }}"><i class="fa fa-list"></i> Résumé</a>

                <?php (! Request::has('show') || Request::has('show') && Request::get('show') == 'grid') ? $class = '--active' : $class = '' ?>
                <a href="{{ action('MasterBox\Admin\EasyGoController@getIndex', array_merge($current_query, ['show' => 'grid'])) }}" class="button__default --green {{ $class }}"><i class="fa fa-th-large"></i> Détails</a>
  
              </div>
              <div class="grid-9 +text-right">

                <!-- Ready button-->
                @if (count($orders_filtered) > 0)
                  <div>
                    <div class="spacer"></div>

                    @if (Request::has('spot'))
                      <a data-confirm-text="Les commandes seront considérées comme prêtes pour {{ App\Models\DeliverySpot::find(Request::get('spot'))->name }}" href="{{ url('admin/orders/ready-spot/' . Request::get('spot')) }}" class="button button__default --blue js-confirm">Tout est prêt pour {{ App\Models\DeliverySpot::find(Request::get('spot'))->name }}</a>
                    @endif

                    @if (Request::has('to_send'))
                      <a data-confirm-text="Les commandes seront considérées comme prêtes pour la poste" href="{{ url('admin/orders/ready-no-take-away') }}" class="button__default --blue js-confirm">Tout est prêt pour La Poste</a>
                    @endif

                    @if ( ! Request::has('to_send') && ! Request::has('spot'))
                      <a data-confirm-text="Toutes les commandes seront considérées comme prêtes" href="{{ url('admin/orders/everything-is-ready') }}" class="button__default --blue js-confirm">Tout est prêt (les dépôts et la poste)</a>

                    @endif
                  </div>
                @endif
              </div>
            </div>
            
            <div class="+spacer-extra-small"></div>
            <div class="divider divider__section"></div>
            
            <div class="+text-center">
              <h1 class="easygo__title">{{ count($orders_filtered) }} commandes à préparer</h1>
            </div>
            <div class="+spacer-extra-small"></div>

            @if (Request::has('show') && Request::get('show') == 'list')
              @include('masterbox.admin.easygo.partials.list', array('orders_filtered' => $orders_filtered))
            @else
              @include('masterbox.admin.easygo.partials.grid', array('orders_filtered' => $orders_filtered))
            @endif



          </div>
        </div>
  @else

    <div class="+text-center">
      <div class="+spacer-small"></div>
      <h1 class="easygo__success">BRAVO ! Toutes les boxs sont préparées, il suffit de cliquer sur le lien en dessous pour mettre à jour le statut des commandes. Le système n'envoie pas d'emails par sécurité, il faut le faire dans la section Série.</h1>

      <a href="{{ url('admin/orders/everything-has-been-sent') }}" class="button__default --blue">Tout est envoyé !</a>
    </div>

  @endif


@stop
