@extends('layouts.master')

@section('content')
  
  <div id="js-page-card"></div>

  <div class="container profile-orders-section">

    <ul class="nav-tabs tabs col-md-2">
      <li class=""><a href="{{ url('profile#account') }}"><i class="fa fa-cog"></i>Mon compte</a></li>

      <li><a href="{{ url('profile#contracts') }}">
        <i class="fa fa-shopping-cart"></i> Abonnements
      </a></li>
            <li>
        <a href="{{ url('contact') }}"><i class="fa fa-envelope-o"></i> Contact</a>
      </li>
      <li>
        <a href="{{ url('user/logout') }}"><i class="fa fa-unlock"></i> Déconnexion</a>
      </li>
    </ul>

    <div class="col-md-10">


      @if (Session::has('message'))
        <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
      @endif

      @if (Session::has('error'))
        <div class="js-alert-remove spyro-alert spyro-alert-danger">{{ Session::get('error') }}</div>
      @endif

      @if ($errors->any())
        <div class="js-alert-remove spyro-alert spyro-alert-danger">Des erreurs sont présentes dans le formulaire</div>
      @endif
      
      <h3>Mes commandes planifiées</h3>

      <table class="table">

        <thead>

          <tr>
            <th>Livraison</th>
            <th>Statut de la commande</th>
            <th>Date prévue</th>
            <th>Adresse de livraison</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($orders as $order)

            <tr>

              <th>N°{{$order->id}}</th>
              <th>
              {!! Form::getReadableOrderStatus($order->status) !!}

              @if ($order->status == 'delivered')
                ({{$order->date_sent}})
              @endif

              </th>
              <th>{{$order->delivery_serie()->first()->delivery}}</th>
              <th>{!! Form::getOrderSpotOrDestination($order) !!}</th>
              

            </tr>

          @endforeach

        </tbody>

      </table>

      <h3>Mes paiements</h3>

      @if ($payments->count() > 0)

        <table class="table">

          <thead>

            <tr>
              <th>Numéro de transaction</th>
              <th>Prélévement</th>
              <th>Date</th>
              <th>Facture</th>
            </tr>

          </thead>

          <tbody>

            @foreach ($payments as $payment)

              <tr>

                <th>N°{{$payment->id}}</th>
                <th>{{$payment->amount}}€</th>
                <th>{{$payment->created_at}}</th>
                <th>
                @if ($payment->amount > 0)
                  <a class="spyro-btn spyro-btn-primary upper spyro-btn-sm" href="{{url('/profile/bill/' . $payment->bill_id)}}" target="_blank">Accéder à ma facture</a>
                  <a class="spyro-btn spyro-btn-green upper spyro-btn-sm" href="{{url('/profile/download-bill/' . $payment->bill_id)}}" target="_blank">Télécharger ma facture</a>
                @endif
                </th>

              </tr>

            @endforeach

          </tbody>

        </table>

      @else
        <div class="spyro-alert spyro-alert-inverse">Aucun paiement effectué pour le moment</div>
      @endif
      
      <div class="spacer20"></div>

      <h3>Ma carte bancaire</h3>
    
      @if (!empty($payment_profile->last4))
        <div class="spyro-alert spyro-alert-default">Ma carte actuelle : **** **** **** {{$payment_profile->last4}}</div>
      @endif
      
      <div class="spyro-alert spyro-alert-default">
        Si vous souhaitez utiliser une autre carte bancaire pour cet abonnement, il vous suffit de remplir le formulaire ci-dessous.
      </div>

      <div id="errors"></div>


      {!! Form::open(['action' => 'ProfileController@postChangeCard', 'id' => 'payment-form', 'class' => 'form-component']) !!}
      {!! Form::hidden('stripeToken', null, ['id' => 'stripe-token']) !!}

      {!! Form::hidden('profile_id', $profile->id) !!}

      {!! Form::label('Numéro de carte') !!}
      {!! Form::text('card', null, ['id' => 'card', 'autocomplete' => 'off']) !!}

      {!! Form::label('Date d\'expiration') !!}
      {!! Form::text('expiration', null, ['id' => 'expiration', 'placeholder' => 'Format : MM/AA', 'autocomplete' => 'off']) !!}

      {!! Form::label('CCV') !!}
      {!! Form::text('ccv', null, ['id' => 'cvc', 'placeholder' => 'Exemple : 585', 'autocomplete' => 'off']) !!}

     

      {!! Form::label("old_password", "Mot de passe actuel") !!}
      {!! Form::password("old_password", ['id' => 'password', 'autocomplete' => 'off']) !!}

      @if ($errors->first('old_password'))
        <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('old_password') }}}</span>
      @endif

      <div class="spyro-well spyro-well-sm">Le mot de passe actuel est requis pour tout changement de carte bancaire</div>
      
      <button id="trigger-update" type="submit">Mettre à jour</button>

      {!! Form::close() !!}

    </div>

    <div class="clearfix"></div>
    <div class="spacer100"></div> 
  </div>
    @include('_includes.footer')
  </div>
@stop