@extends('masterbox.layouts.master')

@section('content')

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

@stop