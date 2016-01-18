@extends('masterbox.layouts.master')

@section('content')

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

@stop