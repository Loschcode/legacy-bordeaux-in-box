@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')


<div class="hero hide@xs">
  <div class="hero__container" style="background-image: url('{{ url('images/box-february/box.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0"></div>
  </div>
</div>

<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Une fois par mois, on glisse Bordeaux dans votre box</h1>
    <p class="section__description">Vous y croiserez peut-être ...</p>

    <div class="+spacer-small"></div>
  </div>

  <div class="row">
    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">
        <img class="concept__picture" src="{{ url('images/concept/wine.jpg') }}">
        <span class="concept__picture-description">De jolies découvertes vinicoles</span>
      </div>
    </div>

    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">
        <img class="concept__picture" src="{{ url('images/concept/mode.jpg') }}">
        <span class="concept__picture-description">Des accessoires de mode</span>
      </div>
    </div>

    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">
        <img class="concept__picture" src="{{ url('images/concept/beauty.jpg') }}">
        <span class="concept__picture-description">Des produits de beauté</span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">

        <img class="concept__picture" src="{{ url('images/concept/food.jpg') }}">
        <span class="concept__picture-description">De bonnes choses à déguster</span>
      </div>
    </div>

    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">

        <img class="concept__picture" src="{{ url('images/concept/handmade.jpg') }}">
        <span class="concept__picture-description">Des produits fait-main</span>
      </div>

    </div>

    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="concept__picture-container">

        <img class="concept__picture" src="{{ url('images/concept/surprise.jpg') }}">
        <span class="concept__picture-description">De quoi vous émerveiller</span>
      </div>

    </div>
  </div>

  <div class="+spacer-extra-small"></div>
  
  <div class="+text-center">
    <a class="button__hero" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}">Je m'abonne pour 24.90&euro; par mois</a>
  </div>

</div>

<div class="+spacer"></div>

<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Comment recevoir Bordeaux in Box</h1>

  </div>
</div>

<div class="container-static">
  <div class="row">
    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="step">
        <h2 class="step__title">Etape 1</h2>
        <div class="step__picture-container">
          <img class="step__picture --step2" src="{{ url('images/steps/step2.png') }}" />
        </div>
        <p class="step__description">
          Je m'abonne sur le site
        </p>
      </div>
    </div>
    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="step">
        <h2 class="step__title">Etape 2</h2>
        <div class="step__picture-container">
          <img class="step__picture --step3" src="{{ url('images/steps/step3.png') }}" />
        </div>
        <p class="step__description">
          Je la reçois chaque mois
        </p>
      </div>
    </div>
    <div class="grid-4 grid-11@xs gr-centered@xs">
      <div class="step">
        <h2 class="step__title">Etape 3</h2>
        <div class="step__picture-container">
          <img class="step__picture --step1" src="{{ url('images/steps/step1.png') }}" />
        </div>
        <p class="step__description">
          Je donne mon avis sur les produits reçus
        </p>
      </div>
    </div>
  </div>
</div>

<div class="+spacer"></div>

<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Jeter un oeil à nos dernières boxs</h1>



  </div>
</div>

<div class="grid-8 grid-centered">
  <div class="concept__picture-container">

  <img class="concept__picture" src="{{ url('images/box-february/cover.jpg') }}">
    <span class="concept__picture-description">Box Mars 2016</span>
  </div>

  <div class="+spacer-small"></div>
  
  <div class="+text-center">
    <a class="button__hero" href="{{ action('MasterBox\Guest\HomeController@getBox', ['month' => 'march', 'year' => 2016]) }}">Découvrir la box de mars 2016</a>
  </div>

</div>
@stop