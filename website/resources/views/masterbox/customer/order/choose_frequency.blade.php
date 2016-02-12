@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.purchase.choose-frequency',
    'form-errors-text' => 'Tu dois choisir l\'offre que tu souhaites'
  ]) !!}
@stop

@section('content')

<div class="container">
  
  {{-- Pipeline --}}
  @include('masterbox.partials.pipeline', ['step' => 1])

  {{-- Section --}}
  <div class="grid-9 grid-centered grid-11@xs">
    <div class="section">
      <h2 class="section__title --choose-frequency">Fréquence de livraison</h2>
      <p id="section" class="section__description --choose-frequency">

        @if ($is_gift)
          Envie de faire plaisir sur la durée ?
        @else
          Envie de recevoir une jolie box chaque mois ? Ou juste faire un test ?
        @endif
        
      </p>
    </div>
  </div>
  
  <div class="+spacer"></div>

  {{-- Choices --}}
  <div class="grid-7 grid-centered grid-11@xs">
    {!! Form::open() !!}
      @if ($is_gift)
  
        @foreach ($delivery_prices as $key => $delivery_price)

          <div class="{{ $delivery_price->getLabelautyFocusClass() }}">

            @if ($order_preference === NULL)

              {!! Form::radio('delivery_price', $delivery_price->id, Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencyGiftText()]) !!}

            @else

              {!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencyGiftText()]) !!}

            @endif


          </div>
          <div class="+spacer-extra-small"></div>

        @endforeach

      @else

        @foreach ($delivery_prices as $key => $delivery_price)
          <div class="{{ $delivery_price->getLabelautyFocusClass() }}">

            @if ($order_preference === NULL)

              {!! Form::radio('delivery_price', $delivery_price->id, Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencySubscriptionText()]) !!}

            @else

              {!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencySubscriptionText()]) !!}

            @endif

          </div>
          <div class="+spacer-extra-small"></div>

        @endforeach

      @endif

      <button id="commit" class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>

    {!! Form::close() !!}

  </div>

</div>

<div class="+spacer"></div>

@stop