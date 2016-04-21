@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')

<div class="hero hide@xs">
  <div class="hero__container" style="background-image: url('{{ url('images/box-april/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Gourmandise</h2>
      <h2 class="hero__title --medium">La box du mois d'avril</h2>
      <p class="hero__description">En Avril, la box était gourmande et remplie de bonnes choses à craquer !</p>
    </div>
  </div>
</div>


<div class="container">
  <div class="section">
    <h1 class="section__title --clear-space">Quelques photos de la box</h1>

    <div class="row">
      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-april/indiens.jpg') }}">
        </div>
        <span class="concept__picture-details">Boucles d’oreilles de Youh Youh les Indiens</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/YouhYouhLesIndiens/?fref=ts">Découvrir</a></div>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-april/claire.jpg') }}">
        </div>
        <span class="concept__picture-details">Pot de caramel au rhum ambré des Secrets de Claire</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/lessecretsdeclaire.fr/?fref=ts">Découvrir</a></div>

      </div>

      <div class="grid-4 grid-11@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-april/pignotte.jpg') }}">
        </div>
        <span class="concept__picture-details">Tablette de chocolat aux pignons de pins de La Pignotte du Bassin</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/laPignotteDuBassin/?fref=ts">Découvrir</a></div>
      </div>
    </div>
    

    <div class="row">
      <div class="grid-4 push-2 grid-11@xs push-0@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-april/jock.jpg') }}">
        </div>
        <span class="concept__picture-details">Préparation pour crèmes brûlées de Jock</span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/Jock-Bordeaux-Boutique-Jock-143294422370407/?fref=ts">Découvrir</a></div>

      </div>

      <div class="grid-4 push-2 grid-11@xs push-0@xs gr-centered@xs">
        <div class="concept__picture-container">
          <img class="concept__picture" src="{{ url('images/box-april/bakery.jpg') }}">
        </div>
        <span class="concept__picture-details">Recette spéciale Bordeaux in Box de la Charlotte à l’Ananas par My French Bakery </span>
        <div class="concept__discover"><a class="button__discover button" href="https://www.facebook.com/MyFrenchBakery/?fref=ts">Découvrir</a></div>

      </div>
    </div>



  </div>
</div>



<div class="+spacer-extra-small"></div>

<div class="+text-center">
  <a class="button__hero --responsive" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}">Je m'abonne pour 24.90&euro; par mois</a>
</div>
@stop