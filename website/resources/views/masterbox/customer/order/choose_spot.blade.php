@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.purchase.choose-spot',
    'form-errors-text' => 'Tu dois choisir le point relais que tu préfères'
  ]) !!}
@stop

@section('navbar-links')
  @include('masterbox.partials.pipeline', ['step' => 2])
@stop

@section('content')

<div class="container">
  
  {{-- Section --}}
  <div class="grid-9 grid-11@xs grid-centered">
    <div class="section">
      <h2 class="section__title --choose-frequency">Choisis ton point relais</h2>
      <p class="section__description --choose-frequency">
        Où veux-tu que la box soit déposée ?
      </p>
    </div>
  </div>
  
  <div class="+spacer"></div>

  <div class="grid-7 grid-11@xs grid-centered labelauty-choose-spot">

    {!! Form::open() !!}
    
    @foreach ($delivery_spots as $delivery_spot)

      {!! Form::radio('chosen_spot', $delivery_spot->id, ($chosen_delivery_spot == $delivery_spot->id) ? true : Request::old($delivery_spot->id), ['id' => $delivery_spot->id, 'data-labelauty' => Html::getTextCheckboxSpot($delivery_spot, $order_building)]) !!}
 
      <div class="+spacer-extra-small"></div>

      <a id="gmap-{{ $delivery_spot->id }}" href="{{ gmap_link($order_building->getFullDestinationAddress(), $delivery_spot->getFullAddress()) }}" target="_blank" class="button button__google-map +hidden"><i class="fa fa-google"></i> Voir sur Google maps</a>

    @endforeach

    <div class="+spacer-extra-small"></div>

    <button class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>
    

    {!! Form::close()  !!}
    
    <div class="+spacer-small"></div>
      <div class="+text-center">
        <a class="button button__step" href="{{ action('MasterBox\Customer\PurchaseController@getDeliveryMode') }}"><i class="fa  fa-arrow-circle-o-left"></i> Revenir au choix du mode de livraison</a>
      </div>
  </div>

</div>

@stop