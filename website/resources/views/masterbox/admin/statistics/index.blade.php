@extends('layouts.admin')

@section('page')
  <i class="fa fa-area-chart"></i> Statistiques
@stop

@section('buttons')
@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  <ul class="nav nav-tabs" role="tablist">
    <li class="active">
      <a href="#unfinished-profiles" role="tab" data-toggle="tab"><i class="fa fa-tasks"></i> Abonnement non terminés</a>
    </li>
    <li>
      <a href="#distribution-profiles" role="tab" data-toggle="tab"><i class="fa fa-pie-chart"></i> Répartition des abonnements</a>
    </li>
  </ul>

  <div class="tab-content">

    <div class="tab-pane active" id="unfinished-profiles">

      {!! Html::info("Cette section permet de connaître en détail à quel niveau se sont arrêtés les inscrits lors de leur achat. Les éléments sont classés par `Série` qui correspond à la série sur laquelle les utilisateurs se sont ajoutée pour composer la donnée. Si l'utilisateur reprend son inscription plus tard et la termine, cette donnée peut être théoriquement altérée (mais peu probable).") !!}

      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-area-chart"></i> Inscriptions / abandons à travers les différentes séries</div>
        <div class="panel-body">

          <!-- Multiple lines -->
          @include('admin.partials.graphs.bar_chart', ['config' => $config_graph_subscriptions_versus_unfinished])

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-area-chart"></i> Etapes &amp; abandons à travers le temps</div>
        <div class="panel-body">

          <!-- Multiple lines -->
          @include('admin.partials.graphs.line_chart', ['config' => $config_graph_unfinished_profiles_steps])

        </div>
      </div>

      <table class="js-datas">

        <thead>

          <tr>
    
            <th>ID</th>
            <th>Série</th>
            <th>Pourcentage d'inscriptions inachevées</th>
            <th>Inscriptions inachevées</th>
            <th>Choix de la box</th>
            <th>Personnalise ta box</th>
            <th>Fréquence de livraison</th>
            <th>Adresse de facturation</th>
            <th>Mode de livraison</th>
            <th>Choix du point relais</th>
            <th>Paiement</th>

            <th>Inscrits complet de cette série</th>
            <th>Objectif de cette série</th>

          </tr>

        </thead>

        <tbody>

          @foreach ($series as $serie)

            @if ($serie->user_order_buildings()->count() > 0)

            <tr>

              <th>{{$serie->id}}</th>
              <th>{{$serie->delivery}}</th>

              <th> {{get_percent_unfinished_buildings($serie)}}%</th>


               <th><a href="{{url('/admin/statistics/unfinished-profiles/'.$serie->id)}}">{{$serie->user_order_buildings()->count()}}</a></th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'choose-box')->count()}}</th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'box-form')->count()}}</th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'choose-frequency')->count()}}</th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'billing-address')->count()}}</th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'delivery-mode')->count()}}</th>

              <th>{{$serie->user_order_buildings()->where('step', '=', 'choose-spot')->count()}}</th>
              
              <th>{{$serie->user_order_buildings()->where('step', '=', 'payment')->count()}}</th>

              <th>

              <a href="{{url('/admin/deliveries/focus/'.$serie->id)}}">{{$serie->orders()->notCanceledOrders()->count()}}</a>

              </th>

              <th>
              @if ($serie->getCounter() === FALSE)
              N/A
              @else
              {{$serie->getCounter()}}
              @endif
              </th>

            </tr>

            @endif

          @endforeach

          </tbody>

        </table>
      </div>

    <div class="tab-pane" id="distribution-profiles">


      {!! Html::info("Cette section permet de connaître en détail la répartition des livraisons selon la fréquency et le type d'offre. Ceci ne correspond pas aux nombres de commandes créées lors de la série en cours mais l'accumulation / la diminution des livraisons prévues pour chaque type.") !!}

      <table class="js-datas">

        <thead>

          <tr>
    
            <th>ID</th>
            <th>Série</th>
            <th>Infini</th>
            <th>1 mois</th>
            <th>3 mois</th>
            <th>6 mois</th>
            <th>Ancien : 12 mois</th>
            <th>Offrir : 1 mois</th>
            <th>Offrir : 3 mois</th>
            <th>Offrir : 5 mois</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($series as $serie)

            @if ($serie->user_order_buildings()->count() > 0)

            <tr>

              <th>{{$serie->id}}</th>
              <th>{{$serie->delivery}}</th>

              <th>{{$serie->orders()->NotCanceledOrders()->NotGift()->ByFrequency(0)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->NotGift()->ByFrequency(1)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->NotGift()->ByFrequency(3)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->NotGift()->ByFrequency(6)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->NotGift()->ByFrequency(12)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->OnlyGift()->ByFrequency(1)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->OnlyGift()->ByFrequency(3)->count()}}</th>
              <th>{{$serie->orders()->NotCanceledOrders()->OnlyGift()->ByFrequency(5)->count()}}</th>

            </tr>

            @endif

          @endforeach

          </tbody>

        </table>

    </div>

    </div>

@stop