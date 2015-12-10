@section('content')
  
  <!-- Flag to run controller js -->
  <div id="js-page-payment"></div>

  {{ View::make('_includes.pipeline')->with('step', 5) }}

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
            <p><img class="avatar" src="{{ url($profile->box()->first()->image->full) }}" /></p>
          </div>
          <div class="col-md-8">
            <div class="spacer10"></div>
            <p>Box {{ strtolower($profile->box()->first()->title)}}</p>
            <p>Type : @if ($order_preference->gift) Cadeau @else Commande @endif</p>
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
          <a id="trigger-payment" href="#"><i class="fa fa-credit-card"></i> Procéder au paiement sécurisé</a>
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
            <p>Nom / Prénom : {{$user->last_name}} {{$user->first_name}}</p>
          </div>

          <div class="resume-line">
            <p>Ville : {{$user->city}}, {{$user->zip}}</p>
          </div>

          <div class="resume-line">
            <p>Adresse : {{$user->address}}</p>
          </div>

          <div class="spacer10"></div>

        </div>

        <div class="resume-content resume-no-border-top">

          <h2 class="resume-title">
            Livraison
          </h2>

          <div class="resume-line">
            <p>
              Type : 
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
        <li><a href="{{url('/order/billing-address')}}">&larr; Retour aux détails de livraison</a></li>
      </ul>
    </nav>
  </div>


    <!-- Modal -->
    <div class="modal fade stripe-component" data-backdrop="static" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

      {{ Form::open(['id' => 'payment-form'])}}
      {{ Form::hidden('stripeToken', null, ['id' => 'stripe-token'])}}
      {{ Form::hidden('email', $user->email)}}
      
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <a id="trigger-close" class="close" href="#"></a>
            <h4 class="modal-title" id="myModalLabel">Bordeaux in Box</h4>
            <h5 class="modal-subtitle">Stripe Payments Europe, Ltd</h5>
          </div>
          <div class="modal-body">

            <div class="content-form">

               <div class="bank">

                 <div class="form-group card">
                      <div class="icon"><i class="fa fa-credit-card"></i></div>
                      <input id="card" class="form-control" type="card" placeholder="Numéro de carte" data-stripe="number">
                  </div>

                 <div class="form-group calendar">
                      <div class="icon"><i class="fa fa-calendar"></i></div>
                      <input id="expiration" class="form-control" type="calendar" placeholder="MM / AA">
                  </div>

                  <div class="form-group ccv">
                       <div class="icon"><i class="fa fa-lock"></i></div>
                       <input id="cvc" class="form-control" type="ccv" placeholder="CCV">
                   </div>
                </div>

              <button id="trigger-pay" class="button" type="submit">Payer {{$order_preference->totalPricePerMonth()}} <i class="fa fa-euro"></i></button>

            </div>

          </div>
        </div>
      </div>
      {{ Form::close() }}
    </div>
    <!-- End modal -->


  <div class="clearfix"></div>
  <div class="spacer100"></div>
  {{ View::make('_includes.footer') }}


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
<?php 
/* Backup OLD FRONT
@section('content')
  
  <!-- Flag to run controller js -->
  <div id="js-page-payment"></div>

  {{ View::make('_includes.nav') }}
  {{ View::make('_includes.pipeline')->with('step', 5) }}

  <div class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Paiement</h1>
        <p>
          Loin, très loin, au delà des monts Mots, à mille lieues des pays Voyellie et Consonnia, demeurent les Bolos Bolos. Ils vivent en retrait, à Bourg-en-Lettres, sur les côtes de la Sémantique, un vaste océan de langues.
        </p>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer100"></div>

  <div class="container">


    <div class="col-md-6 col-md-offset-3">
      <div class="resume-component">
        <div class="resume-image">
          <img src="{{ url($profile->box()->first()->image->full) }}" />
        </div>
        <div class="resume-header">
        </div>
        <div class="resume-content">
          <h2 class="resume-title">
            Résumé commande
          </h2>
          <div class="resume-line">
            <p>Box : {{$profile->box()->first()->title}}</p>
          </div>
          <div class="resume-line">
            <p>
              Type : 
              @if ($order_preference->gift)
                Cadeau
              @else
                Commande
              @endif
            </p>
          </div>
          <div class="resume-line">
            <p>Fréquence : 1 box par mois pendant {{$order_preference->frequency}} mois</p>
          </div>

          <h2 class="resume-title">
            Facturation
          </h2>

          <div class="resume-line">
            <p>Nom / Prénom : {{$user->last_name}} {{$user->first_name}}</p>
          </div>

          <div class="resume-line">
            <p>Ville : {{$user->city}}, {{$user->zip}}</p>
          </div>

          <div class="resume-line">
            <p>Adresse : {{$user->address}}</p>
          </div>

          <h2 class="resume-title">
            Livraison
          </h2>

          <div class="resume-line">
            <p>
              Type : 
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

          <div class="resume-title">
            Total à payer
          </div>

          <div class="resume-amount">

            <span>
              {{$order_preference->totalPricePerMonth()}} &euro;
            </span>


          </div>


        </div>

        <div class="resume-buy">
          <a id="trigger-payment" href="#"><i class="fa fa-credit-card"></i> Procéder au paiement</a>
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

  </div>


    <!-- Modal -->
    <div class="modal fade stripe-component" data-backdrop="static" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

      {{ Form::open(['id' => 'payment-form'])}}
      {{ Form::hidden('stripeToken', null, ['id' => 'stripe-token'])}}
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <a id="trigger-close" class="close" href="#"></a>
            <h4 class="modal-title" id="myModalLabel">Bordeaux in Box</h4>
            <h5 class="modal-subtitle">La box bordelaise girly</h5>
          </div>
          <div class="modal-body">
            <div class="content-form">

              <div class="form-group">
                   <div class="icon"><i class="fa fa-envelope-o"></i></div>
                   <input id="email" class="form-control" type="test" placeholder="Email">
               </div>

               <div class="bank">

                 <div class="form-group card">
                      <div class="icon"><i class="fa fa-credit-card"></i></div>
                      <input id="card" class="form-control" type="card" placeholder="Numéro de carte" data-stripe="number">
                  </div>

                 <div class="form-group calendar">
                      <div class="icon"><i class="fa fa-calendar"></i></div>
                      <input id="expiration" class="form-control" type="calendar" placeholder="MM / AA">
                  </div>

                  <div class="form-group ccv">
                       <div class="icon"><i class="fa fa-lock"></i></div>
                       <input id="cvc" class="form-control" type="ccv" placeholder="CCV">
                   </div>
                </div>

              <button id="trigger-pay" class="button" type="submit">Payer {{$order_preference->totalPricePerMonth()}} <i class="fa fa-euro"></i></button>

            </div>
          </div>
        </div>
      </div>
      {{ Form::close() }}
    </div>
    <!-- End modal -->


  <div class="clearfix"></div>
  <div class="spacer100"></div>
  {{ View::make('_includes.footer') }}



@stop
?>

<?php
/* Backup PHP
@section('content')

<!-- The required Stripe lib -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
 
  <script type="text/javascript">
  
    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('pk_test_HNPpbWh3FV4Lw4RmIQqirqsj');
 
    var stripeResponseHandler = function(status, response) {
      var $form = $('#payment-form');
 
      if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // token contains id, last4, and card type
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and re-submit
        $form.get(0).submit();
      }
    };
 
    jQuery(function($) {
      $('#payment-form').submit(function(e) {
        var $form = $(this);
 
        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);
 
        Stripe.card.createToken($form, stripeResponseHandler);
 
        // Prevent the form from submitting with the default action
        return false;
      });
    });
  </script>

<br />

<h2>Résumé de ma commande</h2>

	Votre box personnalisée : {{$profile->box()->first()->title}}
	@if ($order_preference->gift)
	(A offrir)
	@endif

	<br /><br />

	Fréquence : 1 par mois pendant {{$order_preference->frequency}} mois 

  @if ($order_preference->gift)
  pour {{$order_preference->totalPricePerMonth()}}€ au total
  @else
  pour {{$order_preference->totalPricePerMonth()}}€ par mois
  @endif

	@if (!$order_preference->take_away)
	({{$order_preference->delivery_fees}}€ de frais de port inclus)
	@endif

	<br /><br />

	Adresse de facturation :<br />

	{{$user->first_name}} {{$user->last_name}}<br />
	{{$user->city}}, {{$user->zip}}<br />
	{{$user->address}}

	<br /><br />

	Livraison :
	@if ($order_preference->take_away)
	A emporter dans un point de rendez-vous
	@else
	A une adresse spécifique
	@endif

	<br /><br />

	Adresse :<br />

	@if ($order_preference->take_away)

		{{$delivery_spot->name}}<br />
		{{$delivery_spot->city}}, {{$delivery_spot->zip}}<br />
		{{$delivery_spot->address}}<br />

	@else

		{{$order_building->destination_first_name}} {{$order_building->destination_last_name}}<br />
		{{$order_building->destination_city}}, {{$order_building->destination_zip}}<br />
		{{$order_building->destination_address}}<br />

	@endif


</head>
<body>
<br />

	(FAIRE UNE LIGHTBOX POUR L'INSCRIPTION DE LA CARTE)
  <h1>Paiement de {{$order_preference->totalPricePerMonth()}}€</h1>
 
  @if ($errors->first('stripeToken'))
  {{{ $errors->first('stripeToken') }}}<br />
  @endif

 {{ Form::open(['id' => 'payment-form']) }}

  <!--<form action="" method="POST" id="payment-form">-->
    <span class="payment-errors"></span>
 
    <div class="form-row">
      <label>
        <span>Numéro de carte</span>
        <input type="text" size="20" data-stripe="number"/>
      </label>
    </div>
 
    <div class="form-row">
      <label>
        <span>CVC</span>
        <input type="text" size="4" data-stripe="cvc"/>
      </label>
    </div>
 
    <div class="form-row">
      <label>
        <span>Expiration (MM/AAAA)</span>
        <input type="text" size="2" data-stripe="exp-month"/>
      </label>
      <span> / </span>
      <input type="text" size="4" data-stripe="exp-year"/>
    </div>
 
    <button type="submit">Procéder au paiement</button>

{{ Form::close() }}

  <a href="{{url('/order/billing-address')}}">Retour aux détails de livraison</a>



@stop
*/ ?>