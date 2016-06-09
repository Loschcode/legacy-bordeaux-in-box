@extends('masterbox.layouts.master')

@section('header-divider')
@stop

@section('content')

<div class="hero">
  <div class="hero__container" style="background-image: url('{{ url('images/box-may/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content">
      <h2 class="hero__title --big">#Let's go party!</h2>
      <h2 class="hero__title --medium">La box du mois de mai</h2>
      <p class="hero__description">Sortez faire la fête grâce à la box de mai !</p>
      <div class="+spacer-small"></div>
      <a href="{{ action('MasterBox\Guest\HomeController@getBox', ['month' => 'may', 'year' => '2016']) }}" class="button__hero">Découvrir la box</a>

    </div>
  </div>
</div>

<div class="hero">
  <div class="hero__container" style="background-image: url('{{ url('images/box-april/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Gourmandise</h2>
      <h2 class="hero__title --medium">La box du mois d'avril</h2>
      <p class="hero__description">En Avril, la box était gourmande et remplie de bonnes choses à craquer !</p>
      <div class="+spacer-small"></div>
      <a href="{{ action('MasterBox\Guest\HomeController@getBox', ['month' => 'april', 'year' => '2016']) }}" class="button__hero">Découvrir la box</a>
    </div>
    <div class="hero__partner">
      <a target="_blank" href="http://myfrenchbakery.fr/2016/04/15/charlotte-a-lananas/">
        <img src="{{ url('images/box-april/partner.png') }}" />
        <p>My French Bakery a aimé <i class="fa fa-heart-o"></i></p>
      </a>
    </div>
  </div>
</div>

<div class="hero">
  <div class="hero__container --no-margin" style="background-image: url('{{ url('images/box-march/cover.jpg') }}');">
    <div class="hero__overlay" style="opacity: 0.5"></div>


    <div class="hero__content"> 
      <h2 class="hero__title --big">#Colorful</h2>
      <h2 class="hero__title --medium">La box du mois de mars</h2>
      <p class="hero__description">Pour lutter contre l’hiver, on avait glissé de la couleur en pagaille dans la box.</p>
      <div class="+spacer-small"></div>
      <a href="{{ action('MasterBox\Guest\HomeController@getBox', ['month' => 'march', 'year' => '2016']) }}" class="button__hero">Découvrir la box</a>
    </div>
    <div class="hero__partner">
      <a href="http://www.princesse-aux-bidouilles.com/bordeaux-in-box-colourful/">
        <img src="{{ url('images/box-march/partner.jpg') }}" />
        <p>La Princesse aux Bidouilles a aimé <i class="fa fa-heart-o"></i></p>
      </a>
    </div>
  </div>
</div>

@stop