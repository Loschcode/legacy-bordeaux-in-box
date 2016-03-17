@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')

<div class="hero hide@xs">
  <div class="hero__container" style="background-image: url('{{ url('images/box-february/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Colorful</h2>
      <h2 class="hero__title --medium">La box du mois de mars</h2>
      <p class="hero__description">Pour lutter conte l’hiver, on avait glissé de la couleur en pagaille dans la box.</p>
    </div>
  </div>
</div>


<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Quelques photos de la box</h1>

    <div class="row">
      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/jewerly.jpg') }}">
        </div>
        <span class="concept__picture-details">Boucles d’oreille de Mazurka Bijoux</span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/jock.jpg') }}">
        </div>
        <span class="concept__picture-details">Préparation pour un mug cake au chocolat </span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/beauty.jpg') }}">
        </div>
        <span class="concept__picture-details">Vernis pailleté de L’Onglerie</span>

      </div>
    </div>
    

    <div class="row">
      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/diy.jpg') }}">
        </div>
        <span class="concept__picture-details">Kit pour fabriquer un col vintage de Blue Madone</span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/clean.jpg') }}">
        </div>
        <span class="concept__picture-details">Dissolvant sans acétone parfumé à la pêche de L’Onglerie</span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-february/global.jpg') }}">
          <span class="concept__picture-details">Vue générale de la box</span>
        </div>
      </div>
    </div>



  </div>
</div>



<div class="+spacer-extra-small"></div>

<div class="+text-center">
  <a class="button__hero" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}">Je m'abonne pour 24.90&euro; par mois</a>
</div>
@stop