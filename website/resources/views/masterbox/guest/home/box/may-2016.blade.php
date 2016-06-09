@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')

<div class="hero hide@xs">
  <div class="hero__container" style="background-image: url('{{ url('images/box-may/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Let's go party!</h2>
      <h2 class="hero__title --medium">La box du mois de mai</h2>
      <p class="hero__description">Sortez faire la fête grâce à la box de mai !</p>
    </div>
  </div>
</div>


<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Quelques photos de la box</h1>

    <div class="row">
      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-may/zephire.jpg') }}">
        </div>
        <span class="concept__picture-details">Bracelet Zephire By Les Petites Gazelles</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/zephirebylespetitesgazelles/?fref=ts">Découvrir</a></div>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-may/doyouspeak.jpg') }}">
        </div>
        <span class="concept__picture-details">Tote Bag Do you Speak français ?</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/doyouspeakfrancais/?fref=ts">Découvrir</a></div>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-may/atelier.jpg') }}">
        </div>
        <span class="concept__picture-details">Pochette Lin &amp; Cuir de L'Atelier des Jolies Choses</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/AtelierJoliesChoses/?fref=ts">Découvrir</a></div>
      </div>
    </div>
    

    <div class="row">
      <div class="grid-8 push-2 grid-11@xs push-0@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-may/vin.jpg') }}">
        </div>
        <span class="concept__picture-details">Bouteille de vin blanc d'Entre-deux-Mers du Château Galouchey Pesquey</span>
      </div>

    </div>



  </div>
</div>



<div class="+spacer-extra-small"></div>

<div class="+text-center">
  <a class="button__hero --responsive" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}">Je m'abonne pour 24.90&euro; par mois</a>
</div>
@stop