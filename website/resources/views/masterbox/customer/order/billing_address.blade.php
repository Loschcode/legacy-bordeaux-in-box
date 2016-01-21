@extends('masterbox.layouts.master')

@section('content')
  
  <div 
    id="gotham"
    data-controller="masterbox.customer.purchase.billing-address"
    data-form-errors="{{ $errors->has() }}"
  ></div>

  <div class="container">
    
    {{-- Pipeline --}}
    @include('masterbox.partials.pipeline', ['step' => 2])

    {{-- Section --}}
    <div class="grid-9 grid-centered">
      <div class="section">
        <h2 class="section__title --choose-frequency">Livraison / Facturation</h2>
        <p class="section__description --choose-frequency">
          @if ($order_preference->isGift())
            Quelques détails pour savoir où envoyer la box ...
          @else
            Quelques détails pour savoir où envoyer ta box ...
          @endif
        </p>
      </div>
    </div>
  
    <div class="billing">
      <div class="+spacer-small"></div>

      {{-- Form --}}
      {!! Form::open() !!}

        {{-- Already filled when the user created his account --}}        
        {!! Form::hidden("billing_first_name", $customer->first_name) !!}
        {!! Form::hidden("billing_last_name", $customer->last_name) !!}

        <!-- If the user already filled an address and it's not his first order -->
        @if ($customer->hasBillingAddress() && ($customer->profiles()->count() > 1))

          {!! Form::hidden("billing_city", $customer->city, ['id' => 'billing_city']) !!}
          {!! Form::hidden("billing_zip", $customer->zip, ['id' => 'billing_zip']) !!}
          {!! Form::hidden("billing_address", $customer->address, ['id' => 'billing_address']) !!}

        @endif

        <div class="grid-8 grid-centered">
            
          {{-- Delivery informations --}}
          <div class="panel">
            <div class="panel__heading">
              <h2 class="panel__title">Informations de livraison</h2>
            </div>
            <div class="panel__content --white">


              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('destination_first_name', 'Prénom') !!}
                  </div>
                  
                  {!! Form::text("destination_first_name", ($order_building->destination_first_name) ? $order_building->destination_first_name : Request::old("destination_first_name"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_first_name', $errors) !!}

                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_last_name', 'Nom de famille') !!}
                  </div>
                  {!! Form::text("destination_last_name", ($order_building->destination_last_name) ? $order_building->destination_last_name : Request::old("destination_last_name"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_last_name', $errors) !!}

                </div>
              </div>

              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('destination_city', 'Ville') !!}
                  </div>
                  
                  {!! Form::text("destination_city", ($order_building->destination_city) ? $order_building->destination_city : Request::old("destination_city"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_city', $errors) !!}


                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_zip', 'Code postal') !!}
                  </div>
                  {!! Form::text("destination_zip", ($order_building->destination_zip) ? $order_building->destination_zip : Request::old("destination_zip"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_zip', $errors) !!}
                </div>
              </div>

              <div class="billing__label">
                {!! Form::label('destination_address', 'Adresse') !!}
              </div>
              {!! Form::textarea("destination_address", ($order_building->destination_address) ? $order_building->destination_address : Request::old("destination_address"), ['class' => 'billing__input --textarea']) !!}
              {!! Html::checkError('destination_address', $errors) !!}
            </div>
          </div>
          
          <div class="+spacer-small"></div>
          
          <!-- If the user already filled an address and it's not his first order -->
          @if ($customer->hasBillingAddress() && ($customer->profiles()->count() > 1))
            
            {{-- Billing informations (address given) --}}
            <div class="panel">
            <div class="panel__heading">
              <h2 class="panel__title">Informations de facturation</h2>
            </div>
            <div class="panel__content --white">
            
              <div class="row billing__container">

                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('fake_billing_first_name', 'Prénom') !!}
                  </div>
                  
                  {!! Form::text("fake_billing_first_name", $customer->first_name, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}


                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('fake_billing_last_name', 'Nom de famille') !!}
                  </div>
                  {!! Form::text("fake_billing_last_name", $customer->last_name, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}

                </div>
              </div>

              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('billing_city', 'Ville') !!}
                  </div>
                  
                  {!! Form::text("fake_billing_city", $customer->city, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}


                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('billing_zip', 'Code postal') !!}
                  </div>
                  {!! Form::text("billing_zip", $customer->zip, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}
                </div>
              </div>

              <div class="billing__label">
                {!! Form::label('billing_address', 'Adresse') !!}
              </div>
              {!! Form::textarea("billing_address", $customer->address, ['class' => 'billing__input --textarea --disabled', 'disabled' => 'disabled']) !!}

            </div>
            <div class="panel__footer">
              <button type="submit" class="button button__submit --panel"><i class="fa fa-check"></i> Passer à l'étape de paiement</button>
            </div>
            </div>
          @else

            {{-- Billing informations (address not given yet) --}}
            <div class="panel">
              <div class="panel__heading">
                <h2 class="panel__title">Informations de facturation</h2>
              </div>
              <div class="panel__content --white --small-padding-top">
              
                <div class="+text-center">
                  <a id="copy" class="button button__copy-delivery"><i class="fa fa-copy"></i> Copier les informations de livraison</a>
                </div>

                <div class="row billing__container">

                  <div class="grid-6">
                    
                    <div class="billing__label">
                      {!! Form::label('fake_billing_first_name', 'Prénom') !!}
                    </div>
                    
                    {!! Form::text("fake_billing_first_name", $customer->first_name, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}


                  </div>
                  <div class="grid-6">
                    <div class="billing__label">
                      {!! Form::label('fake_billing_last_name', 'Nom de famille') !!}
                    </div>
                    {!! Form::text("fake_billing_last_name", $customer->last_name, ['class' => 'billing__input --disabled', 'disabled' => 'disabled']) !!}

                  </div>
                </div>

                <div class="row billing__container">
                  <div class="grid-6">
                    
                    <div class="billing__label">
                      {!! Form::label('billing_city', 'Ville') !!}
                    </div>
                    
                    {!! Form::text("billing_city", ($order_building->billing_city) ? $order_building->billing_city : Request::old("billing_city"), ['class' => 'billing__input']) !!}
                    {!! Html::checkError('billing_city', $errors) !!}


                  </div>
                  <div class="grid-6">
                    <div class="billing__label">
                      {!! Form::label('billing_zip', 'Code postal') !!}
                    </div>
                    {!! Form::text("billing_zip", ($order_building->billing_zip) ? $order_building->billing_zip : Request::old("billing_zip"), ['class' => 'billing__input']) !!}
                    {!! Html::checkError('billing_zip', $errors) !!}

                  </div>
                </div>

                <div class="billing__label">
                  {!! Form::label('billing_address', 'Adresse') !!}
                </div>
                {!! Form::textarea("billing_address", ($order_building->billing_address) ? $order_building->billing_address : Request::old("billing_address"), ['class' => 'billing__input --textarea']) !!}
                {!! Html::checkError('billing_address', $errors) !!}

              </div>
              <div class="panel__footer">
                <button type="submit" class="button button__submit --panel"><i class="fa fa-check"></i> Passer à l'étape de paiement</button>
              </div>
            </div>

          @endif



        </div>
      {!! Form::close() !!}
    </div>
  
  </div>

  <?php /*
  <div id="js-page-billing-address"></div>

  @if (session()->has('flag-billing-address'))
    <div id="js-flag-billing-address"></div>
  @endif

  {!! View::make('masterbox.partials.pipeline')->with('step', 3) !!}

  <div class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Facturation / Livraison</h1>
        @if ($order_preference->gift == TRUE)
          <p>
            Quelques détails pour savoir où envoyer la box ...
          </p>
        @else
          <p>
            Quelques détails pour savoir où envoyer ta box ...
          </p>
        @endif
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

  <div class="container billing">
      {!! Form::open(['class' => 'form-component', 'id' => 'form-billing']) !!}

      @if (session()->has('message'))
      <div>{{ session()->get('message') }}</div>
      @endif


      <div class="row">

        <div class="col-md-6 col-md-offset-3">


              <h2 class="title-info-billing">Informations de facturation</h3><br/>

              {!! Form::hidden("billing_first_name", $customer->first_name, ['id' => 'billing_first_name']) !!}
              {!! Form::hidden("billing_last_name", $customer->last_name, ['id' => 'billing_last_name']) !!}

              <!-- If the user already filled an address and it's not his first order -->
              @if ($customer->hasBillingAddress() && ($customer->profiles()->count() > 1))

                {!! Form::hidden("billing_city", $customer->city, ['id' => 'billing_city']) !!}
                {!! Form::hidden("billing_zip", $customer->zip, ['id' => 'billing_zip']) !!}
                {!! Form::hidden("billing_address", $customer->address, ['id' => 'billing_address']) !!}

                <div class="row">

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Prénom</h3><br/>
                      </div>
                      <div class="panel-body">

                        {!! Form::text("fake_firstname", $customer->first_name, ['disabled' => 'disabled']) !!}

                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Nom de famille</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("fake_lastname", $customer->last_name, ['disabled' => 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Ville</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("fake_city", $customer->city, ['disabled' => 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Code postal</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("fake_zip", $customer->zip, ['disabled' => 'disabled']) !!}
                      </div>
                    </div>
                  </div>
                </div>


                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Adresse</h3><br/>
                  </div>
                  <div class="panel-body">
                    {!! Form::textarea("fake_address",  $customer->address, ['disabled' => 'disabled']) !!}
                  </div>
                </div>
         

              @else

                <div class="row">

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Prénom</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("fake_firstname", $customer->first_name, ['disabled' => 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Nom de famille</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("fake_lastname", $customer->last_name, ['disabled' => 'disabled']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Ville</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("billing_city", (Request::old("billing_city")) ? Request::old("billing_city") : $customer->city, ['id' => 'billing_city']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Code postal</h3><br/>
                      </div>
                      <div class="panel-body">
                        {!! Form::text("billing_zip", (Request::old("billing_zip")) ? Request::old("billing_zip") : $customer->zip, ['id' => 'billing_zip']) !!}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Adresse</h3><br/>
                  </div>
                  <div class="panel-body">
                    {!! Form::textarea("billing_address", (Request::old("billing_address")) ? Request::old("billing_address") : $customer->address, ['id' => 'billing_address']) !!}
                  </div>
                </div>

              @endif
            </div>
      
    <div class="clearfix"></div>

      <div class="col-md-6 col-md-offset-3">

        @if ($order_preference->gift == TRUE)
          <h2 class="title-info">Où habite la personne ?</h2>
          <div id="gift" data-value="true"></div>
        @else
          <h2 class="title-info">Informations de livraison</h2>
          <div id="gift" data-value="false"></div>
        @endif

        <a href="#" class="copy-button" id="copy-billing"><i class="fa fa-copy"></i> Copier les informations de facturation</a><br /><br />

        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Prénom</h3><br/>
              </div>
              <div class="panel-body">

                {!! Form::text("destination_first_name", ($order_building->destination_first_name) ? $order_building->destination_first_name : Request::old("destination_first_name"), ['id' => 'destination_first_name']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Nom de famille</h3><br/>
              </div>
              <div class="panel-body">
                {!! Form::text("destination_last_name", ($order_building->destination_last_name) ? $order_building->destination_last_name : Request::old("destination_last_name"), ['id' => 'destination_last_name']) !!}
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Ville</h3><br/>
              </div>
              <div class="panel-body">
                {!! Form::text("destination_city", ($order_building->destination_city) ? $order_building->destination_city : Request::old("destination_city"), ['id' => 'destination_city']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Code Postal</h3><br/>
              </div>
              <div class="panel-body">
                {!! Form::text("destination_zip", ($order_building->destination_zip) ? $order_building->destination_zip : Request::old("destination_zip"), ['id' => 'destination_zip']) !!}
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Adresse</h3><br/>
          </div>
          <div class="panel-body">

            {!! Form::textarea("destination_address", ($order_building->destination_address) ? $order_building->destination_address : Request::old("destination_address"), ['id' => 'destination_address']) !!}
          </div>
        </div>
      </div>

      <div class="col-md-6 col-md-offset-3">
        <button type="submit"><i class="fa fa-check"></i> Valider</button>
      </div>

      {!! Form::close() !!}
    </div>
          <nav>
        <ul class="pager">
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getChooseFrequency') }}">&larr; Retour au choix de la fréquence</a></li>
        </ul>
      </nav>
</div>


<div class="spacer50"></div>
{!! View::make('masterbox.partials.front.footer') !!}
*/ ?>
@stop