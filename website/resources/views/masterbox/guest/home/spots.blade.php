@extends('masterbox.layouts.master')

@section('gotham')
  
  {!! Html::gotham([
    'controller' => 'masterbox.guest.home.spots'
  ]) !!}

@stop

@section('content')
  
  <div class="grid-8 grid-centered grid-11@xs">
    <div class="section section__wrapper">
      <h1 class="section__title --page">Nos points relais partenaires</h1>
      <div class="section__description">
            Besoin d'informations sur les points relais ? Envie de remettre tes boxes vides car elles prennent de la place ? Voici la liste de nos points relais partenaires ...
      </div>
    </div>
  </div>
  
  <div class="+spacer-small"></div>

  <div class="container">

    <div class="grid-7 grid-centered grid-11@xs">
      @foreach ($delivery_spots as $delivery_spot)

      <div class="labelauty-choose-spot">

          {!! Form::radio('chosen_spot', $delivery_spot->id, '', ['id' => $delivery_spot->id, 'data-labelauty' => Html::getTextCheckboxSpot($delivery_spot)]) !!}
          
          <div class="+spacer-extra-small"></div>

          <a id="gmap-{{ $delivery_spot->id }}" href="{{ gmap_link_simple($delivery_spot->getFullAddress()) }}" target="_blank" class="button button__google-map +hidden">Voir sur Google map</a>

      </div>
      

      @endforeach
    </div>

  </div>

@stop
