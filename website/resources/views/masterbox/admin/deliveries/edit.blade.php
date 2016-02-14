<div class="dialog">
  <h4 class="dialog__title">Edition série {{ Html::dateFrench($series->delivery, true) }} (#{{ $series->id }})</h4>
  <div class="dialog__divider"></div>
</div>

<div class="panel panel__wrapper">
  <div class="panel__header">
    <h3 class="panel__title">Edition</h3>
  </div>

  <div class="panel__content">
    {!! Form::open() !!}

    {!! Form::hidden('delivery_series_id', $series->id) !!}

    {{ Form::label("delivery", "Date de livraison", ['class' => 'form__label']) }}
    {{ Form::text("delivery", Request::old("delivery") ? Request::old("delivery") : $series->delivery, ['class' => 'form__input', 'placeholder' => 'Date de livraison']) }}

    {!! Form::label("goal", "Objectif", ['class' => 'form__label']) !!}
    {!! Form::text("goal", Request::old("goal") ? Request::old("goal") : $series->goal, ['class' => 'form__input', 'placeholder' => 'Objectif']) !!}

    <div class="+spacer-small"></div>

    {!! Form::submit("Editer cette série", ['class' => 'button button__default']) !!}

  </div>
</div>


