@extends('layouts.master')
@section('content')

  @if ($orders->count() > 0)

    <div class="container">

      <div class="text-center">
        <h1>Easy Go - Série {{ date('d F Y', strtotime(Order::LockedOrders()->first()->delivery_serie()->first()->delivery)) }}</h1>
        <a href="{{ url('admin') }}" class="btn btn-primary">Revenir à l'administration</a>
        <a id="hide" href="#" class="btn btn-inverse">Afficher les résumés</a>
      </div>
      
      <div id="resumes" class="row">

      <br />

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Résumé des boîtes</h3></div>
            <div class="panel-body">
              <div class="text-center"><h1>Boîtes</h1></div>
            </div>
              <ul class="list-group">

                @foreach ($boxes as $box)

                  <li class="list-group-item"><span class="badge">{{ Order::LockedOrders()->where('box_id', $box->id)->notCanceledOrders()->count() }}</span>{{ $box->title }}</li>

                @endforeach

                <li class="list-group-item"><span class="badge">{{$num_sponsors}}</span>Marraines</li>

                <li class="list-group-item"><span class="badge">{{$num_got_sponsors}}</span>Filleules</li>

              </ul>
          </div>
        </div>
        

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Résumé des dépôts</h3></div>
            <div class="panel-body">
              <div class="text-center"><h1>Dépôts</h1></div>
            </div>
              <ul class="list-group">

                @foreach ($spots as $spot)

                  <li class="list-group-item"><span class="badge">{{ Order::LockedOrders()->where('delivery_spot_id', $spot->id)->notCanceledOrders()->count() }}</span>{{ $spot->name }}</li>

                @endforeach

                <li class="list-group-item"><span class="badge badge-info">{{ Order::LockedOrders()->notCanceledOrders()->whereNull('delivery_spot_id')->count() }}</span>Aucun dépôt</li>

              </ul>
          </div>
        </div>


        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Résumé des régions</h3></div>
            <div class="panel-body">
              <div class="text-center"><h1>Régions</h1></div>
            </div>
              <ul class="list-group">
                <li class="list-group-item"><span class="badge">{{ $regional_orders }}</span>Commandes régionales</li>

                <li class="list-group-item"><span class="badge badge-info">{{ $non_regional_orders }}</span>Commandes hors régionales</li>

              </ul>
          </div>
        </div>


      </div>
      

      <div class="text-center">
        {!! $orders->fragment('result')->links() !!}
      </div>

      @foreach ($orders as $order)

        <? $profile = $order->user_profile()->first(); ?>
        <? $serie = Order::LockedOrders()->first()->delivery_serie()->first(); ?>
        <? $profile_products = $profile->getSeriesProfileProduct($serie->id); ?>
        <? $box = $profile->box()->first(); ?>

        <div class="panel panel-success" id="result">
          <div class="panel-heading">

            <strong>Commande {{ $orders->getCurrentPage() }} / {{ $orders->getTotal() }}</strong>

          </div>
          <div class="panel-body">


            <div class="text-center">
              <img class="img-rounded" src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( $order->user_profile()->first()->user()->first()->email ) )) }}" /> 
              <h3>{{ ucwords($order->user_profile()->first()->user()->first()->getFullName()) }}</h3>
              
              <div class="col-md-6 col-md-offset-3">
                @if ($order->already_paid == 0)
                  <div class="alert alert-danger"><h4><i class="fa fa-credit-card"></i> <strong>A payé {{$order->already_paid}} € sur un total de {{$order->unity_and_fees_price}} €</strong></h4></div>
                @else
                  <div class="alert alert-success"><h4><i class="fa fa-credit-card"></i> <strong>A payé {{$order->already_paid}} € sur un total de {{$order->unity_and_fees_price}} €</strong></h4></div>
                @endif
              </div>
              <div class="clearfix"></div>

              @if ($box == NULL)

                <h3>La box désirée n'a pas été renseigné ... houston on à un problème !!</h3>

              @else

                <strong><span class="badge badge-default">Box {{$box->title}}</span></strong>
              @endif

              <span class="badge badge-default"><i class="fa fa-gift"></i> {{HTML::boolYesOrNo($order->gift)}}</span>
              <br/><br/>
            </div>

            <div class="row">

              <div class="col-md-6">
                <h4>Produits</h4>

                  @foreach ($profile_products as $profile_product)

                  <? $partner_product = $profile_product->partner_product()->first() ?>
                  
                  {{$partner_product->name}} ({{$profile_product->accuracy}}%)

                  @if ($profile_product->birthday)
                  <font color='green'>ANNIVERSAIRE</font>
                  @endif

                  @if ($profile_product->sponsor)
                  <font color='green'>PARRAIN</font>
                  @endif

                  <br />

                  @endforeach

              </div>

              <div class="col-md-6">
                <h4>Questionnaire</h4>

                  @if ($box == NULL)

                    Pas de question

                  @else

                    {{HTML::displayQuizz($box, $profile)}}

                  @endif
              </div>
              <div class="col-md-6">
                <div>
                  <h4>Détails</h4>
                  <div class="well">
                    <strong>Anniversaire :</strong> {{ $order->user_profile()->first()->getAnswer('birthday') }} <br/>
                    <strong>Age :</strong> {{ HTML::getAge($order->user_profile()->first()->getAnswer('birthday')) }} <br/>
                    <strong>Est ce que c'est son anniversaire ? :</strong> 
                    @if (HTML::isBirthday($order->user_profile()->first()->getAnswer('birthday')))
                      Oui
                    @else
                      Non
                    @endif
                  </div>
                </div>
                <div>
                  <h4>Contact</h4>
                  <div class="well">
                    <strong>Adresse :</strong> {{ $order->user_profile()->first()->user()->first()->getFullAddress()}} <br/>
                    <strong>Téléphone :</strong> {{ $order->user_profile()->first()->user()->first()->phone}}<br/>
                    <strong>Email :</strong> <a href="mailto:{{ $order->user_profile()->first()->user()->first()->email}}">{{ $order->user_profile()->first()->user()->first()->email}}</a>
                  </div>
                </div>
                <div>
                  <h4>Lieu de livraison</h4>
                  
                  <div class="well">
                    @if (strpos(HTML::getOrderSpotOrDestinationZip($order), '33') !== 0)
                    <font color="red"><strong>ATTENTION CLIENTE NON REGIONALE</strong></font>
                    <br />
                    @endif
                    {!! HTML::getOrderSpotOrDestination($order) !!}
                  </div>
                </div>
              </div>
                
            </div>


          </div>
        </div>


      @endforeach
      
      <div class="text-center">
        {!! $orders->fragment('result')->links() !!}
      </div>

      @if ($orders->getCurrentPage() == $orders->getLastPage())

        <div class="text-center">
          <a class="btn btn-success btn-lg" href="{{ url('admin/orders/everything-is-ready') }}">Tout est prêt</a>
          <a class="btn btn-warning btn-lg" href="{{ url('admin/orders/everything-has-been-sent') }}">Tout est envoyé</a>
        </div>

      @endif

      <br/>

    </div>

  @else
    
    <div class="container">
      <div class="text-center">
        <h1>Easy Go</h1>
        <h4>Aucune série bloqué pour le moment</h4>
        <a href="{{ url('admin') }}" class="btn btn-primary">Revenir à l'administration</a>
        <br/><br/>


        <a class="spyro-btn spyro-btn-success" href="{{url('/admin/deliveries/lock/'.$next_open_series->id)}}">
        Cliquer ici pour bloquer la série prochaine série du {{ $next_open_series->delivery }} et commencer la préparation
        </a>

      </div>
    </div>
  @endif
@stop
