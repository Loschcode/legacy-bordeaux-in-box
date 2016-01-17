@extends('masterbox.layouts.master')

@section('content')
  
  <!-- Flag to run controller js -->
  <div id="js-page-payment"></div>

  {!! View::make('masterbox.partials.pipeline')->with('step', 5) !!}

  <div class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Paiement</h1>
        <p>
          Plus qu'à valider ta commande et c'est fini !
        </p>

        @if ($errors->has('stripeToken'))
          <div class="spyro-alert spyro-alert-danger">{{ $errors->first('stripeToken')}}</div>
        @endif
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer100"></div>

  <div class="container">

    <!-- Cart -->
    <div class="col-md-6">
      <div class="cart-component">

        <h2>Résumé commande</h2>

        <div class="spacer10"></div>
        <div class="row">
          <div class="col-md-4">
            <p><img class="avatar" src="{{ url('images/macaron-masterbox.png') }}" /></p>
          </div>
          <div class="col-md-8">
            <div class="spacer10"></div>
            <p>Bordeaux in Box</p>
            <p>@if ($order_preference->gift) Cadeau pour {{$order_building->destination_first_name}} @else Commande pour soi @endif</p>
            <p>Fréquence : 

            @if ($order_preference->frequency == 0)

              1 box par mois

            @else
              
              1 box par mois pendant {{$order_preference->frequency}} mois

            @endif

              @if ($order_preference->gift)
              pour {{$order_preference->totalPricePerMonth()}}€ au total
              @else
              pour {{$order_preference->totalPricePerMonth()}}€ par mois
              @endif

              @if (!$order_preference->take_away)
              ({{$order_preference->delivery_fees}}€ de frais de port inclus)
              @endif
            </p>
          </div>
        </div>
        <div class="cart-spacer"></div>
        <div class="cart-amount">
          <p>Montant à payer : {{$order_preference->totalPricePerMonth()}} &euro;</p>
        </div>
        <div class="cart-buy">

          <a id="trigger-payment" href="#" data-text="<i class='fa fa-credit-card'></i> Procéder au paiement sécurisé"><i class="fa fa-credit-card"></i> Procéder au paiement sécurisé</a>

        </div>
      </div>
    </div>


    <!-- Resume -->
    <div class="col-md-6">
      <div class="resume-component">
        <div class="resume-content">
          <h2 class="resume-title">
            Facturation
          </h2>

          <div class="resume-line">
            <p>Nom / Prénom : {{$customer->last_name}} {{$customer->first_name}}</p>
          </div>

          <div class="resume-line">
            <p>Ville : {{$customer->city}}, {{$customer->zip}}</p>
          </div>

          <div class="resume-line">
            <p>Adresse : {{$customer->address}}</p>
          </div>

          <div class="spacer10"></div>

        </div>

        <div class="resume-content resume-no-border-top">

          <h2 class="resume-title">
            Livraison
          </h2>

          <div class="resume-line">
            <p> 
              @if ($order_preference->take_away)
                A emporter dans un point relais
              @else
                A une adresse spécifique
              @endif
            </p>
          </div>

          @if ($order_preference->take_away)

            <div class="resume-line">
              <p>Point relais : {{$delivery_spot->name}}</p>
            </div>

            <div class="resume-line">
              <p>Ville : {{$delivery_spot->city}}, {{$delivery_spot->zip}}</p>
            </div>

            <div class="resume-line">
              <p>Adresse : {{$delivery_spot->address}}</p>
            </div>

          @else

            <div class="resume-line">
              <p>Nom / Prénom : {{$order_building->destination_last_name}} {{$order_building->destination_first_name}}</p>
            </div>

            <div class="resume-line">
              <p>Ville : {{$order_building->destination_city}}, {{$order_building->destination_zip}}</p>
            </div>

            <div class="resume-line">
              <p>Adresse : {{$order_building->destination_address}}</p>
            </div>

          @endif


        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="spacer50"></div>
    <nav>
      <ul class="pager">
        <li><a href="{{ action('MasterBox\Customer\PurchaseController@getBillingAddress') }}">&larr; Retour aux détails de livraison</a></li>
      </ul>
    </nav>
  </div>

  {!! Form::open(['id' => 'payment-form', 'data-price' => $order_preference->totalPricePerMonthInCents()]) !!}
    {!! Form::hidden('stripeToken', null, ['id' => 'stripe-token']) !!}
    {!! Form::hidden('email', $customer->email) !!}
  {!! Form::close() !!}
      
  <div class="clearfix"></div>
  <div class="spacer100"></div>

  {!! View::make('masterbox.partials.front.footer') !!}
  
  <!-- Checkout js stripe -->
  <script src="https://checkout.stripe.com/checkout.js"></script>

  <!-- Facebook Conversion Code for Paiements -->
  <script>(function() {
    var _fbq = window._fbq || (window._fbq = []);
    if (!_fbq.loaded) {
      var fbds = document.createElement('script');
      fbds.async = true;
      fbds.src = '//connect.facebook.net/en_US/fbds.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(fbds, s);
      _fbq.loaded = true;
    }
  })();
  window._fbq = window._fbq || [];
  window._fbq.push(['track', '6022362413870', {'value':'0.00','currency':'EUR'}]);
  </script>
  <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6022362413870&amp;cd[value]=0.00&amp;cd[currency]=EUR&amp;noscript=1" /></noscript>

@stop