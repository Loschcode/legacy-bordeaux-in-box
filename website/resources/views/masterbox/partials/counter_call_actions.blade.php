@if ($next_series->first() !== NULL)
@if ($next_series->first()->getCounter() !== 0 || $next_series->first()->getCounter() === FALSE)

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
