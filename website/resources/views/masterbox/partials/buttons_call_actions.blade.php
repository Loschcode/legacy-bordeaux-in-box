{{-- Buttons to order --}}
@if ($next_series->first() !== NULL)
  @if ($next_series->first()->getCounter() !== 0 || $next_series->first()->getCounter() === FALSE)
    
    <div class="row row-align-center@xs">
      <div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
        <a id="test-pick-gift" class="button {{ $button }}" href="{{ action('MasterBox\Customer\PurchaseController@getGift') }}"><i class="fa fa-gift"></i>L'offrir</a>
      </div>
      <div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
        <a id="test-pick-classic" class="button {{ $button }} --active" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}"><i class="fa fa-shopping-cart"></i> La recevoir</a>
      </div>
    </div>
    
    @if ($counter)
      <div class="grid-11@xs gr-centered@xs">
        <div class="counter">
          <div class="counter__content">
            Il reste moins de 10 boxs et {{ str_replace('dans', '', strtolower(Html::diffHumans($next_series->first()->delivery, 5
            ))) }} pour commander la box de {!! Html::convertMonth($next_series->first()->delivery) !!}
          </div>
        </div>
      </div>
    @endif


  @endif
@endif

{{-- No more boxes to order --}}
@if ($next_series->first() === NULL or $next_series->first()->getCounter() === 0)

  <div class="row">
    <div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
      <a class="button {{ $button }} js-no-boxes" href="#"><i class="fa fa-gift"></i>L'offrir</a>
    </div>
    <div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
      <a class="button {{ $button }} --active js-no-boxes" href="#"><i class="fa fa-shopping-cart"></i> La recevoir</a>
    </div>
  </div>
@endif