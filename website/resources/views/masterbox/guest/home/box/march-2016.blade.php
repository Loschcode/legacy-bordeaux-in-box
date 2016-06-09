@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')

<div class="hero hide@xs">
  <div class="hero__container" style="background-image: url('{{ url('images/box-march/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Colorful</h2>
      <h2 class="hero__title --medium">La box du mois de mars</h2>
      <p class="hero__description">Pour lutter contre l’hiver, on avait glissé de la couleur en pagaille dans la box.</p>
    </div>
    <div class="hero__partner">
      <a href="http://www.princesse-aux-bidouilles.com/bordeaux-in-box-colourful/">
        <img src="{{ url('images/box-march/partner.jpg') }}" />
        <p>La Princesse aux Bidouilles a aimé <i class="fa fa-heart-o"></i></p>
      </a>
    </div>
  </div>
</div>


<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Quelques photos de la box</h1>

    <div class="row">
      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-march/jewerly.jpg') }}">
        </div>
        <span class="concept__picture-details">Boucles d’oreille de Mazurka Bijoux</span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-march/jock.jpg') }}">
        </div>
        <span class="concept__picture-details">Préparation pour un mug cake au chocolat de Jock</span>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-march/beauty.jpg') }}">
        </div>
        <span class="concept__picture-details">Vernis pailleté de L’Onglerie</span>

      </div>
    </div>
    

    <div class="row">
      <div class="grid-4 push-2 grid-11@xs push-0@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-march/diy.jpg') }}">
        </div>
        <span class="concept__picture-details">Kit pour fabriquer un col vintage de Blue Madone</span>

      </div>

      <div class="grid-4 push-2 grid-11@xs push-0@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-march/clean.jpg') }}">
        </div>
        <span class="concept__picture-details">Dissolvant sans acétone parfumé à la pêche de L’Onglerie</span>

      </div>
    </div>



  </div>
</div>



<div class="+spacer-extra-small"></div>

<div class="+text-center">
  <a class="button__hero --responsive" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}">Je m'abonne pour 24.90&euro; par mois</a>
</div>
@stop