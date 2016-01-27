@extends('masterbox.layouts.master')

@section('content')
  
  <div 
    id="gotham"
    data-form-errors="{{ $errors->has() }}"
    data-form-errors-text="Tu dois choisir la méthode de livraison que tu préfères"
  ></div>

  <div class="container">
    
    {{-- Pipeline --}}
    @include('masterbox.partials.pipeline', ['step' => 2])

    {{-- Section --}}
    <div class="grid-9 grid-centered">
      <div class="section">
        <h2 class="section__title --choose-frequency">En livraison ou à emporter</h2>
        <p class="section__description --choose-frequency">
          @if ($order_preference->isGift())
            Tu préfères que le facteur s'occupe de tout ou que la personne aille chercher directement sa box dans un point relais ?
          @else
            Tu préfères le facteur ou venir la chercher directement dans l'une de nos boutiques complices ?
          @endif
        </p>
      </div>
    </div>
    
    <div class="+spacer"></div>
    
    <div class="grid-7 grid-centered labelauty-choose-frequency">
      {!! Form::open() !!}

        {!! Form::radio('take_away', 1, ($order_preference->take_away) ? true : Request::old(1), ['class' => 'big', 'data-labelauty' => '<span class="labelauty-title">A emporter en point relais (Gratuit)</span><span class="labelauty-description"></span>']) !!}
        
        <div class="+spacer-extra-small"></div>

        {!! Form::radio('take_away', 0, (!$order_preference->take_away) ? true : Request::old(0), ['class' => 'big', 'data-labelauty' => '<span class="labelauty-title">En livraison chez toi (+' . App\Models\DeliverySetting::first()->regional_delivery_fees . '&euro; *)</span><span class="labelauty-description"></span>']) !!}
        
        <div class="+spacer-extra-small"></div>

        <button id="test-commit" class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>
        
        <div class="+spacer-small"></div>
        <div class="+text-right typography">
          <p>* tarif  <em><a href="https://www.laposte.fr/particulier/content/download/23580/840376/version/3/file/Principaux%20tarifs%20au%20d%C3%A9part%20de%20France%20M%C3%A9tropolitaine%20%C3%A0%20compter%20du%201er%20janvier%202015.pdf" target="_blank">La Poste en vigueur depuis Janvier 2015</a></em></p>
        </div>

      {!! Form::close() !!}

    </div>

  </div>

@stop