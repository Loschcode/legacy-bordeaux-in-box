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

        <button class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>
        
        <div class="+spacer-small"></div>
        <div class="+text-right typography">
          <p>* tarif  <em><a href="https://www.laposte.fr/particulier/content/download/23580/840376/version/3/file/Principaux%20tarifs%20au%20d%C3%A9part%20de%20France%20M%C3%A9tropolitaine%20%C3%A0%20compter%20du%201er%20janvier%202015.pdf" target="_blank">La Poste en vigueur depuis Janvier 2015</a></em></p>
        </div>

      {!! Form::close() !!}

    </div>

  </div>

  <?php /*
  @include('masterbox.partials.pipeline', ['step' => 3])
    
  <div id="js-page-delivery-mode"></div>
  
  <div id="after-pipeline" class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">En livraison ou à emporter ?</h1>
        @if ($order_preference->gift == TRUE)
          <?php $text = 'En livraison chez elle'; ?>
          <p>
            Tu préfères que le facteur s'occupe de tout ou que la personne aille chercher directement sa box dans un point relais ?
          </p>
        @else
          <?php $text = 'En livraison chez toi'; ?>
          <p>
            Tu préfères le facteur ou venir la chercher directement dans l'une de nos boutiques complices ?
          </p>
        @endif
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

   <div class="container">

   	{!! Form::open(['class' => 'form-component']) !!}

      <div class="col-md-6 col-md-offset-3">
        {!! Form::label('1', 'A emporter depuis un point relais (Gratuit)', ['class' => 'hidden']) !!}
        {!! Form::radio('take_away', 1, ($order_preference->take_away) ? true : Request::old(1), array('id' => 1, 'class' => 'big')) !!}
      </div>

      <div class="clearfix"></div>

   		<div class="col-md-6 col-md-offset-3">
	   		{!! Form::label('0', $text . ' (+'.App\Models\DeliverySetting::first()->regional_delivery_fees.'€ *)', ['class' => 'hidden']) !!}
	   		{!! Form::radio('take_away', 0, (!$order_preference->take_away) ? true : Request::old(0), array('id' => 0, 'class' => 'big')) !!}
	   	</div>

   	
   		<div class="col-md-6 col-md-offset-3">
     		<button type="submit"><i class="fa fa-check"></i> Valider</button>

            <p class="block-description" align="right">
            <em>
            <font color="grey">* tarif <a href="https://www.laposte.fr/particulier/content/download/23580/840376/version/3/file/Principaux%20tarifs%20au%20d%C3%A9part%20de%20France%20M%C3%A9tropolitaine%20%C3%A0%20compter%20du%201er%20janvier%202015.pdf" target="_blank">La Poste en vigueur depuis Janvier 2015</a>
            </font>
            </em>
            </p>


        <nav>
          <ul class="pager">
            <li><a href="{{ action('MasterBox\Customer\PurchaseController@getBillingAddress') }}">&larr; Retour aux informations de facturation</a></li>
          </ul>
        </nav>
     	</div>

   	{!! Form::close() !!}

   </div>


   <div class="spacer50"></div>
   @include('masterbox.partials.footer')
*/ ?>

@stop