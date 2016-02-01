@extends('masterbox.layouts.master')

@section('content')
  
<div 
  id="gotham"
  data-controller="masterbox.customer.purchase.choose-spot"
  data-form-errors="{{ $errors->has() }}"
  data-form-errors-text="Tu dois choisir le point relais que tu préfères"
></div>

<div class="container">
  
  {{-- Pipeline --}}
  @include('masterbox.partials.pipeline', ['step' => 2])

  {{-- Section --}}
  <div class="grid-9 grid-centered">
    <div class="section">
      <h2 class="section__title --choose-frequency">Choisis ton point relais</h2>
      <p class="section__description --choose-frequency">
        Où veux-tu que la box soit déposée ?
      </p>
    </div>
  </div>
  
  <div class="+spacer"></div>

  <div class="grid-7 grid-centered labelauty-choose-frequency">

    {!! Form::open() !!}
    
    @foreach ($delivery_spots as $delivery_spot)

      DISTANCE {{ in_kilometers($delivery_spot->getDistanceFromCoordinate($order_building->destination_coordinate()->first())) }}

      {!! Form::radio('chosen_spot', $delivery_spot->id, ($chosen_delivery_spot == $delivery_spot->id) ? true : Request::old($delivery_spot->id), ['id' => $delivery_spot->id, 'data-labelauty' => Html::getTextCheckboxSpot($delivery_spot)]) !!}
 
      <div class="+spacer-extra-small"></div>

      <a id="gmap-{{ $delivery_spot->id }}" href="{{ $delivery_spot->getUrlGoogleMap() }}" target="_blank" class="button button__google-map +hidden">Voir sur Google map</a>

    @endforeach

    <div class="+spacer-extra-small"></div>

    <button class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>
    

    {!! Form::close()  !!}
  </div>

</div>

  <?php /*
  <div id="js-page-spot"></div>

  {!! View::make('masterbox.partials.pipeline')->with('step', 3) !!}

  <div class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Choisis ton point relais</h1>
        <p>
          Où veux-tu que la box soit envoyée ?
        </p>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

  <div class="container">
  	{!! Form::open(['class' => 'form-component']) !!}

  	@foreach ($delivery_spots as $delivery_spot)

  		<div class="col-md-6 col-md-offset-3" id="spot-{{ $delivery_spot->id }}">

  			{!! Form::label($delivery_spot->id, $delivery_spot->readableSpot(), ['class' => 'hidden']) !!}
  			{!! Form::radio('chosen_spot', $delivery_spot->id, ($chosen_delivery_spot == $delivery_spot->id) ? true : Request::old($delivery_spot->id), array('id' => $delivery_spot->id, 'class' => 'choose-spot')) !!}

        <div id="{{ 'gm-' . $delivery_spot->id }}" class="google-maps hidden">
          <a target="_blank" href="http://maps.google.com/?q={{ $delivery_spot->googleMaps() }}" class="spyro-btn spyro-btn-inverse spyro-btn-lg spyro-btn-block"><i class="fa fa-google"></i> Voir sur Google Maps</a>
        </div>

  		</div>

  	@endforeach



  	<div class="col-md-6 col-md-offset-3">

    	<button type="submit"><i class="fa fa-check"></i> Valider</button>
      <nav>
        <ul class="pager">
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getDeliveryMode') }}">&larr; Retour au mode de livraison</a></li>
        </ul>
      </nav>
    </div>

    <div class="clearfix"></div>
  	{!! Form::close() !!}
  </div>


  <div class="spacer50"></div>
  {!! View::make('masterbox.partials.front.footer') !!}
  */ ?>
@stop