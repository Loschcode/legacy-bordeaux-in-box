@extends('masterbox.layouts.master')

@section('content')

<div 
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
  data-form-errors-text="Tu dois choisir l'offre que tu souhaites"
></div>

<div class="container">
  
  {{-- Pipeline --}}
  @include('masterbox.partials.pipeline', ['step' => 1])

  {{-- Section --}}
  <div class="grid-9 grid-centered">
    <div class="section">
      <h2 class="section__title --choose-frequency">Fréquence de livraison</h2>
      <p class="section__description --choose-frequency">
        @if ($order_preference->isGift())
          Envie de faire plaisir sur la durée ?
        @else
          Envie de recevoir une jolie box chaque mois ? Ou juste faire un test ?
        @endif
      </p>
    </div>
  </div>
  
  <div class="+spacer"></div>

  {{-- Choices --}}
  <div class="grid-7 grid-centered labelauty-choose-frequency">
    {!! Form::open() !!}
      @if ($order_preference->isGift())

        @foreach ($delivery_prices as $key => $delivery_price)
          {!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencyGiftText()]) !!}
          <div class="+spacer-extra-small"></div>
        @endforeach

      @else

        @foreach ($delivery_prices as $key => $delivery_price)

          {!! Form::radio('delivery_price', $delivery_price->id, ($order_preference->frequency == $delivery_price->frequency) ? true : Request::old($delivery_price->id), ['id' => $delivery_price->id, 'data-labelauty' => $delivery_price->getCheckboxFrequencySubscriptionText()]) !!}
          <div class="+spacer-extra-small"></div>

        @endforeach

      @endif

      <button class="button button__submit --big" type="submit"><i class="fa fa-check"></i> Valider</button>

    {!! Form::close() !!}

  </div>

</div>

<div class="+spacer-large"></div>

@include('masterbox.partials.footer')
@stop