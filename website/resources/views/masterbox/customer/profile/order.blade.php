@extends('masterbox.layouts.master')

@section('stripe')
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')


<div
  id="gotham"
  data-controller="masterbox.customer.profile.order"
  data-card-last-digits="{{$payment_profile->last4}}"
  data-form-errors="{{ $errors->has() }} {{ session()->has('error') }}"
></div>

<div class="container">
  <div class="row">
    <div class="grid-2">
      @include('masterbox.partials.sidebar_profile')
    </div>
    <div class="grid-9">
      <div class="profile profile__wrapper">
        <div class="profile__section">
          <h3 class="profile__title">Commandes Planifiées</h3>
          <div class="+spacer-extra-small"></div>
          <table class="table table__wrapper">

            <thead class="table__head">

              <tr class="table__head-items">
                <th>Livraison</th>
                <th>Statut</th>
                <th>Date prévue</th>
                <th>Adresse de livraison</th>
              </tr>

            </thead>

            <tbody class="table__body">

              @foreach ($orders as $order)

                <tr class="table__body-items">

                  <th>N°{{$order->id}}</th>
                  <th>
                  {!! Html::getReadableOrderStatus($order->status) !!}

                  @if ($order->status == 'delivered')
                    ({{ Html::dateFrench($order->date_sent, true)}})
                  @endif

                  </th>
                  <th>{{ Html::dateFrench($order->delivery_serie()->first()->delivery, true) }}</th>
                  <th>{!! Html::getOrderSpotOrDestination($order) !!}</th>
                  

                </tr>

              @endforeach

            </tbody>

          </table>
        </div>

        <div class="profile__section">
          <h3 class="profile__title">Paiements</h3>

          @if ($payments->count() > 0)

            <table class="table table__wrapper">

              <thead class="table__head">

                <tr class="table__head-items">
                  <th>Transaction</th>
                  <th>Montant</th>
                  <th>Date</th>
                  <th>Facture(s)</th>
                </tr>

              </thead>

              <tbody class="table__body">

                @foreach ($payments as $payment)

                  <tr class="table__body-items">

                    <th>N°{{$payment->id}}</th>
                    <th>{{ number_format($payment->amount, 2) }}&euro;</th>
                    <th>{{ Html::dateFrench($payment->created_at, true) }}</th>
                    <th>
                    @if ($payment->amount > 0)

                      @foreach ($payment->getCompanyBillings() as $company_billing)
                        
                        <a class="button button__table" data-jq-dropdown="#jq-dropdown-{{ $company_billing->id }}" href="#">{{ $company_billing->bill_id }} <i class="fa fa-angle-down"></i></a>
                        <div id="jq-dropdown-{{ $company_billing->id }}" class="jq-dropdown jq-dropdown-tip">
                            <ul class="jq-dropdown-menu">
                                <li><a href="{{ action('Company\Guest\BillingController@getWatch', ['encrypted_access' => $company_billing->encrypted_access]) }}">Voir la facture</a></li>
                                <li><a href="{{ action('Company\Guest\BillingController@getDownload', ['encrypted_access' => $company_billing->encrypted_access]) }}">Télécharger la facture</a></li>
                            </ul>
                        </div>
                      <br />

                      

                      @endforeach


                    @endif
                    </th>

                  </tr>

                @endforeach

              </tbody>

            </table>

          @else
            <p>Aucun paiement effectué pour le moment</p>
          @endif
        </div>

        <div id="credit-card" class="profile__section">

          <h3 class="profile__title">Carte bancaire associée</h3>
          <p>Tu as la possibilité de changer la carte bancaire utilisée pour cet abonnement.</p>
          
          <div class="+spacer-extra-small"></div>
          
          {{-- Card widget --}}
          <div class="card"></div>

          {!! Form::open(['action' => 'MasterBox\Customer\ProfileController@postChangeCard', 'id' => 'form-edit-card']) !!}

          {!! Form::hidden('stripeToken', null, ['id' => 'stripe-token']) !!}
          {!! Form::hidden('profile_id', $profile->id) !!}
          {!! Form::hidden('old_password') !!}

          {!! Form::label('card', 'Numéro de carte', ['class' => 'form__label']) !!}
          {!! Form::text('card', null, ['id' => 'card', 'autocomplete' => 'off', 'class' => 'form__input']) !!}
          {!! Html::checkError('card', $errors) !!}
          <div id="errors-card" class="form__error"></div>

          <div class="row">
            <div class="grid-6">
              {!! Form::label('expiration', 'Date d\'expiration', ['class' => 'form__label']) !!}
              {!! Form::text('expiration', null, ['id' => 'expiration', 'placeholder' => 'Format : MM/AA', 'autocomplete' => 'off', 'class' => 'form__input']) !!}
                {!! Html::checkError('expiration', $errors) !!}
                <div id="errors-expiration" class="form__error"></div>
            </div>
            <div class="grid-6">
              {!! Form::label('ccv', 'CVV', ['class' => 'form__label']) !!}
              {!! Form::text('ccv', null, ['id' => 'cvc', 'placeholder' => 'Exemple : 585', 'autocomplete' => 'off', 'class' => 'form__input']) !!}
              {!! Html::checkError('ccv', $errors) !!}
              <div id="errors-ccv" class="form__error"></div>
            </div>
          </div>
          
          @if (session()->has('error'))
            <div class="form__error">{{ session()->get('error') }}</div>
          @endif
          {!! Html::checkError('old_password', $errors) !!}
          <div id="error-stripe" class="form__error"></div>

          <div class="+spacer-extra-small"></div>
          <button id="commit" type="submit" class="button button__submit">Mettre à jour</button>

          {!! Form::close() !!}
        </div>

      </div>
    </div>
  </div>
</div>
@stop

<?php /*
@section('content')
  
  <div id="js-page-card"></div>

  <div class="container profile-orders-section">

    <ul class="nav-tabs tabs col-md-2">
      <li class=""><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}#account"><i class="fa fa-cog"></i>Mon compte</a></li>

      <li><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}#contracts">
        <i class="fa fa-shopping-cart"></i> Abonnements
      </a></li>
            <li>
        <a href="{{ url('contact') }}"><i class="fa fa-envelope-o"></i> Contact</a>
      </li>
      <li>
        <a href="{{ action('MasterBox\Connect\CustomerController@getLogout') }}"><i class="fa fa-unlock"></i> Déconnexion</a>
      </li>
    </ul>

    <div class="col-md-10">


      @if (session()->has('message'))
        <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
      @endif

      @if (session()->has('error'))
        <div class="js-alert-remove spyro-alert spyro-alert-danger">{{ session()->get('error') }}</div>
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
              {!! Html::getReadableOrderStatus($order->status) !!}

              @if ($order->status == 'delivered')
                ({{$order->date_sent}})
              @endif

              </th>
              <th>{{$order->delivery_serie()->first()->delivery}}</th>
              <th>{!! Html::getOrderSpotOrDestination($order) !!}</th>
              

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
              <th>Facture(s)</th>
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

                  @foreach ($payment->getCompanyBillings() as $company_billing)

                  <a class="spyro-btn spyro-btn-primary upper spyro-btn-sm" href="{{ action('Company\Guest\BillingController@getWatch', ['encrypted_access' => $company_billing->encrypted_access]) }}" target="_blank">{{$company_billing->bill_id}}</a>
                  <a class="spyro-btn spyro-btn-green upper spyro-btn-sm" href="{{ action('Company\Guest\BillingController@getDownload', ['encrypted_access' => $company_billing->encrypted_access]) }}" target="_blank">Télécharger</a>

                  <br />

                  

                  @endforeach


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


      {!! Form::open(['action' => 'MasterBox\Customer\ProfileController@postChangeCard', 'id' => 'payment-form', 'class' => 'form-component']) !!}
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
    @include('masterbox.partials.footer')
  </div>
@stop
*/ ?>