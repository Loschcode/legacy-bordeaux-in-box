@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.purchase.billing-address'
  ]) !!}
@stop

@section('content')
  
  <div class="container">
    
    {{-- Pipeline --}}
    @include('masterbox.partials.pipeline', ['step' => 2])

    {{-- Section --}}
    <div class="grid-9 grid-centered grid-11@xs">
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

        {{-- If the user already filled an address and it's not his first order --}}
        @if ($customer->hasBillingAddress() && ($customer->profiles()->count() > 1))

          {!! Form::hidden("billing_city", $customer->city, ['id' => 'billing_city']) !!}
          {!! Form::hidden("billing_zip", $customer->zip, ['id' => 'billing_zip']) !!}
          {!! Form::hidden("billing_address", $customer->address, ['id' => 'billing_address']) !!}

        @endif

        <div class="grid-8 grid-centered grid-11@xs">
            
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
                  
                  {!! Form::text("destination_first_name", ($destination->first_name) ? $destination->first_name : Request::old("destination_first_name"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_first_name', $errors) !!}

                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_last_name', 'Nom de famille') !!}
                  </div>

                  {!! Form::text("destination_last_name", ($destination->last_name) ? $destination->last_name : Request::old("destination_last_name"), ['class' => 'billing__input']) !!}

                  {!! Html::checkError('destination_last_name', $errors) !!}

                </div>
              </div>

              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('destination_city', 'Ville') !!}
                  </div>
                  
                  {!! Form::text("destination_city", ($destination->city) ? $destination->city : Request::old("destination_city"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_city', $errors) !!}


                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_zip', 'Code postal') !!}
                  </div>
                  {!! Form::text("destination_zip", ($destination->zip) ? $destination->zip : Request::old("destination_zip"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_zip', $errors) !!}
                </div>
              </div>
              
              <div class="row billing__container">
                <div class="billing__label">
                  {!! Form::label('destination_address', 'Adresse') !!}
                </div>
                {!! Form::textarea("destination_address", ($destination->address) ? $destination->address : Request::old("destination_address"), ['class' => 'billing__input --textarea']) !!}


                {!! Form::text("destination_address_detail", ($destination->address_detail) ? $destination->address_detail : Request::old("destination_address_detail"), ['class' => 'billing__input --address-details', 'placeholder' => 'Complément d\'adresse si nécessaire (Numéro d\'appartement ...)']) !!}

              </div>

                <div class="+spacer-small"></div>

                {!! Html::checkError('destination_address', $errors) !!}
                {!! Html::checkError('destination_address_detail', $errors) !!}
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
              
              <div class="row billing__container">
                <div class="billing__label">
                  {!! Form::label('billing_address', 'Adresse') !!}
                </div>
                {!! Form::textarea("billing_address", $customer->address, ['class' => 'billing__input --textarea --disabled', 'disabled' => 'disabled']) !!}

                {!! Form::text("billing_address_detail", ($customer->address_detail) ? $customer->address_detail : Request::old("billing_address_detail"), ['class' => 'billing__input --address-details --disabled', 'disabled' => 'disabled']) !!}

              </div>

              <div class="+spacer-small"></div>

            </div>
            <div class="panel__footer">
              <button type="submit" class="button button__submit --panel"><i class="fa fa-check"></i> Valider</button>
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
                    
                    {!! Form::text("billing_city", (Request::old("billing_city")) ? Request::old("billing_city") : $customer->city, ['class' => 'billing__input']) !!}
                    {!! Html::checkError('billing_city', $errors) !!}


                  </div>
                  <div class="grid-6">
                    <div class="billing__label">
                      {!! Form::label('billing_zip', 'Code postal') !!}
                    </div>
                    {!! Form::text("billing_zip", (Request::old("billing_zip")) ? Request::old("billing_zip") : $customer->zip, ['class' => 'billing__input']) !!}
                    {!! Html::checkError('billing_zip', $errors) !!}

                  </div>
                </div>
                
                @if (empty($customer->phone))
                  <div class="billing__label">
                    {!! Form::label('customer_phone', 'Numéro de téléphone') !!}
                  </div>
                  {!! Form::text("customer_phone", Request::old("customer_phone"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('customer_phone', $errors) !!}
                @endif

                <div class="+spacer-extra-small"></div>
                
                <div class="row billing__container">
                  <div class="billing__label">
                    {!! Form::label('billing_address', 'Adresse') !!}
                  </div>
                  {!! Form::textarea("billing_address", (Request::old("billing_address")) ? Request::old("billing_address") : $customer->address, ['class' => 'billing__input --textarea']) !!}

                  {!! Form::text("billing_address_detail", ($customer->address_detail) ? $customer->address_detail : Request::old("billing_address_detail"), ['class' => 'billing__input --address-details', 'placeholder' => 'Complément d\'adresse si nécessaire (Numéro d\'appartement ...)']) !!}

                </div>

                <div class="+spacer-small"></div>
                
                {!! Html::checkError('billing_address', $errors) !!}
                {!! Html::checkError('billing_address_detail', $errors) !!}

              </div>


              <button id="test-commit" type="submit" class="button button__submit --panel"><i class="fa fa-check"></i> Valider</button>
            </div>

          @endif


        </div>
      {!! Form::close() !!}
    </div>
    

  </div>
  
@stop