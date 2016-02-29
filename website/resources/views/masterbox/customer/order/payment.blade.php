@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.purchase.payment',
    'form-errors' => $errors->first('stripeToken'),
    'customer-email' => $customer->email,
    'amount' => $order_preference->totalPricePerMonthInCents()

  ]) !!}
@stop

@section('navbar-links')
  @include('masterbox.partials.pipeline', ['step' => 3])
@stop


@section('content')

  {{-- Form to submit payment --}}
  {!! Form::open(['id' => 'payment-form', 'data-price' => $order_preference->totalPricePerMonthInCents()]) !!}
    {!! Form::hidden('stripeToken', null, ['id' => 'stripe-token']) !!}
    {!! Form::hidden('email', $customer->email) !!}
  {!! Form::close() !!}


  <div class="container">
    
    {{-- Section --}}
    <div class="grid-9 grid-centered grid-11@xs">
      <div class="section">
        <h2 class="section__title --choose-frequency">Paiement</h2>
        <p class="section__description --choose-frequency">
          Plus qu'à valider ta commande et c'est terminé !
        </p>
      </div>
    </div>

    <div class="+spacer-small"></div>
    
    <div class="payment">
      <div class="row">
        <div class="grid-6 grid-11@xs gr-centered@xs">
          <div class="payment__container">
            <h3 class="payment__title">Résumé commande</h3>

            <div class="row">
              <div class="grid-4">
                <div class="payment__picture-container">
                  <img class="payment__picture" src="{{ url('images/macaron-masterbox.png') }}" />
                </div>
              </div>
              <div class="grid-8">
                <p class="payment__description">
                  Bordeaux in Box<br/>
                  
                  Type: 
                  @if ($order_preference->isGift()) 
                    Cadeau pour {{$order_building->destination_first_name}} 
                  @else 
                    Commande pour soi 
                  @endif

                  <br/>

                  Fréquence: 

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
                    @endif<br/>
                </p>
              </div>
            </div>
          </div>
          <div class="payment__container --no-border-top">
            <h4 class="payment__amount">Montant à payer: {{$order_preference->totalPricePerMonth()}}&euro;</h4>
          </div>
          <div class="payment__container --no-border-top">
            <button id="trigger-payment" class="button button__payment"><i class="fa fa-credit-card"></i> Procéder au paiement sécurisé</button>
          </div>
        </div>
        <div class="+spacer-small show@xs hide"></div>
        <div class="grid-6 grid-11@xs gr-centered@xs">
          <div class="payment__container">
            <h3 class="payment__title">Facturation</h3>
            <p class="payment__description">
              Nom / Prénom : {{$customer->last_name}} {{$customer->first_name}}<br/>
              Ville : {{$customer->city}}, {{$customer->zip}}<br/>
              Adresse : {{$customer->address}}

              @if (!empty($customer->address_detail))

              , {{$customer->address_detail}}

              @endif

              <br/>

            </p>
          </div>

          <div class="payment__container --no-border-top">
            <h3 class="payment__title">Livraison</h3>
            <p class="payment__description">
              @if ($order_preference->take_away)
                A emporter dans un point relais<br/>
              @else
                A une adresse spécifique<br/>
              @endif

              @if ($order_preference->take_away)

                Point relais : {{$delivery_spot->name}}<br/>
                Ville : {{$delivery_spot->city}}, {{$delivery_spot->zip}}<br/>
                Adresse : {{$delivery_spot->address}}<br/>
              
              @else

                Nom / Prénom : {{$order_building->destination_last_name}} {{$order_building->destination_first_name}}<br/>
                Ville : {{$order_building->destination_city}}, {{$order_building->destination_zip}}<br/>
                Adresse : {{$order_building->destination_address}}

                @if (!empty($order_building->destination_address_detail))

                , {{$order_building->destination_address_detail}}

                @endif

                <br />

              @endif
            </p>
          </div>
          
        </div>
      </div>
    </div>
   

  </div>

  <div class="+spacer-large"></div>
  <?php /*
  <!-- Flag to run controller js -->
  <div id="js-page-payment" data-email="{{ $customer->email }}"></div>

  {!! View::make('masterbox.partials.pipeline')->with('step', 3) !!}

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

          <button id="trigger-payment" href="#"><i class="fa fa-credit-card"></i> Procéder au paiement sécurisé</button>

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
  */ ?>
@stop

@section('stripe-checkout')
  <!-- Checkout js stripe -->
  <script src="https://checkout.stripe.com/checkout.js"></script>
@stop