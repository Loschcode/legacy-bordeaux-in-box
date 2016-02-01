@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.purchase.choose-spot',
    'form-errors-text' => 'Tu dois choisir le point relais que tu préfères'
  ]) !!}
@stop

@section('content')

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

      DISTANCE {{ display_distance($delivery_spot->getDistanceFromCoordinate($order_building->destination_coordinate()->first())) }}

      {!! Form::radio('chosen_spot', $delivery_spot->id, ($chosen_delivery_spot == $delivery_spot->id) ? true : Request::old($delivery_spot->id), ['id' => $delivery_spot->id, 'data-labelauty' => Html::getTextCheckboxSpot($delivery_spot)]) !!}
 
      <div class="+spacer-extra-small"></div>

      <a id="gmap-{{ $delivery_spot->id }}" href="{{ gmap_link($order_building->getFullDestinationAddress(), $delivery_spot->getFullAddress()) }}" target="_blank" class="button button__google-map +hidden">Voir sur Google map</a>

    @endforeach

    <div class="+spacer-extra-small"></div>

    <button class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>
    

    {!! Form::close()  !!}
  </div>

</div>

@stop