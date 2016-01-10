@extends('masterbox.layouts.master')

@section('content')

  <div id="js-page-box-frequency"></div>

  @include('masterbox.partials.pipeline', ['step' => 3])
  
  <div id="after-pipeline" class="block-description text-center">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="title-step">Fréquence de livraison</h1>

        @if ($order_preference->gift == TRUE)
          <p>
            Envie de faire plaisir sur la durée ?
          </p>
        @else
          <p>
            Envie de recevoir une jolie box chaque mois ? Ou juste faire un test sur un mois ?
          </p>
        @endif
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="spacer50"></div>

  <div class="container">
  	{!! Form::open(['class' => 'form-component']) !!}

  	<!-- Prochaine série : {{Html::diffHumans($next_series->first()->delivery)}} -->

  	@if ($order_preference->gift == TRUE)

  		@foreach ($delivery_prices as $key => $delivery_price)

        {{-- Guess the right input class --}}
        @if ($key > 2)
          <?php $class = 'big' ?>
        @else
          <?php $class = 'frequency-' . $key ?>
        @endif

  			<div class="col-md-6 col-md-offset-3">
          {!! Form::label($delivery_price->id, $delivery_price->title . '<br/><span class="offer-details">' . $delivery_price->readableFrequency() . ' ('. $delivery_price->unity_price.' €)</span>', ['class' => 'hidden']) !!}
	  			{!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), array('id' => $delivery_price->id, 'class' => $class)) !!}
	  		</div>
	  		<div class="clearfix"></div>

  		@endforeach

  	@else
  		
      <div class="col-md-6 col-md-offset-3">

  		<!-- Not a gift -->
  		@foreach ($delivery_prices as $key => $delivery_price)
  
        {{-- Guess the right input class --}}
        @if ($key > 2)
          <?php $class = 'big' ?>
        @else
          <?php $class = 'frequency-' . $key ?>
        @endif

          @if ($delivery_price->frequency == 0)
            {!! Form::label($delivery_price->id, '<span class="readable-price">'. $delivery_price->unity_price.'€ par mois</span>' . '<span class="readable-frequency">' . $delivery_price->readableFrequency() . '</span>' , ['class' => 'hidden']) !!}
	  			@elseif ($delivery_price->frequency == 1)
	  			{!! Form::label($delivery_price->id, '<span class="readable-price">'. $delivery_price->unity_price.'€</span>' . '<span class="readable-frequency">' . $delivery_price->readableFrequency() . '</span>' , ['class' => 'hidden']) !!}
	  			@else
	  			{!! Form::label($delivery_price->id, '<span class="readable-price">'. $delivery_price->unity_price.'€ par mois</span>' . '<span class="readable-frequency">' . $delivery_price->readableFrequency() . '</span>', ['class' => 'hidden']) !!}
	  			@endif

	  			{!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), array('id' => $delivery_price->id, 'class' => $class)) !!}


  		@endforeach

      <div class="clearfix"></div>

      </div>


  	@endif

  	<div class="col-md-6 col-md-offset-3">

    	<button type="submit"><i class="fa fa-check"></i> Valider</button>
      <nav>
        <ul class="pager">
          <li><a href="{{url('/order/box-form')}}">&larr; Retour à la personnalisation</a></li>
        </ul>
      </nav>
    </div>

    <div class="clearfix"></div>
  	{!! Form::close() !!}


  </div>


  <div class="spacer50"></div>
  {!! View::make('masterbox.partials.front.footer') !!}

@stop