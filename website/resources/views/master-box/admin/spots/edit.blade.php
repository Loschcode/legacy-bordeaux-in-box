@extends('layouts.admin')

@section('page')
  <i class="fa fa-map-marker"></i> Edition point relais #{{$spot->id}}
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')
  {!! Form::open() !!}
  {!! Form::hidden('delivery_spot_id', $spot->id) !!}

  <!-- Name -->
  <div class="form-group @if ($errors->first('name')) has-error has-feedback @endif">
    {!! Form::label("name", "Nom", ['class' => 'control-label']) !!}
    {!! Form::text("name", ($spot->name) ? $spot->name : Request::old("name"), ['class' => 'form-control']) !!}

    @if ($errors->first('name'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('name') }}</span>
    @endif
  </div>

  <!-- City -->
  <div class="form-group @if ($errors->first('city')) has-error has-feedback @endif">
    {!! Form::label("city", "Ville", ['class' => 'control-label']) !!}
    {!! Form::text("city", ($spot->city) ? $spot->city : Request::old("city"), ['class' => 'form-control']) !!}

    @if ($errors->first('city'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('city') }}</span>
    @endif
  </div>

  <!-- Zip -->
  <div class="form-group @if ($errors->first('zip')) has-error has-feedback @endif">
    {!! Form::label("zip", "Code postal", ['class' => 'control-label']) !!}
    {!! Form::text("zip", ($spot->zip) ? $spot->zip : Request::old("zip"), ['class' => 'form-control']) !!}

    @if ($errors->first('zip'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('zip') }}</span>
    @endif
  </div>

  <!-- Address -->
  <div class="form-group @if ($errors->first('address')) has-error has-feedback @endif">
    {!! Form::label("address", "Adresse", ['class' => 'control-label']) !!}
    {!! Form::textarea("address", ($spot->address) ? $spot->address : Request::old("address"), ['class' => 'form-control']) !!}

    @if ($errors->first('address'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('address') }}</span>
    @endif
  </div>

    <!-- Schedule -->
  <div class="form-group @if ($errors->first('schedule')) has-error has-feedback @endif">
    {!! Form::label("schedule", "Horaires", ['class' => 'control-label']) !!}
    {!! Form::textarea("schedule", ($spot->schedule) ? $spot->schedule : Request::old("schedule"), ['class' => 'form-control']) !!}

    @if ($errors->first('schedule'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('schedule') }}</span>
    @endif
  </div>

  {!! Form::submit("Sauvegarder les modifications", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

@stop