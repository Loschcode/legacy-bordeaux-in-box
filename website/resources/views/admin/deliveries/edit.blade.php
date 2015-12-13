@extends('layouts.admin')

@section('page')
  <i class="fa fa-map-marker"></i> Edition la série du {{$series->delivery}}
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  {!! Form::open() !!}

  
  {!! Form::hidden('delivery_series_id', $series->id) !!}

  <div class="form-group @if ($errors->first('delivery')) has-error has-feedback @endif">
      {{ Form::label("delivery", "Date de livraison", ['class' => 'sr-only']) }}
      {{ Form::text("delivery", Input::old("delivery") ? Input::old("delivery") : $series->delivery, ['class' => 'form-control', 'placeholder' => 'Date de livraison']) }}
  </div>

  <!-- Counter -->
  <div class="form-group @if ($errors->first('goal')) has-error has-feedback @endif">
    {!! Form::label("goal", "Objectif", ['class' => 'sr-only']) !!}
    {!! Form::text("goal", Input::old("goal") ? Input::old("goal") : $series->goal, ['class' => 'form-control', 'placeholder' => 'Objectif']) !!}
  </div>

  {!! Form::submit("Editer cette série", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

@stop