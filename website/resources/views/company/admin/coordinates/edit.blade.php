@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-map-marker"></i> Edition coordonnées #{{$coordinate->id}}
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')
  {!! Form::open() !!}
  {!! Form::hidden('coordinate_id', $coordinate->id) !!}

  <strong>Google Place ID : {{$coordinate->place_id}}</strong>
  <br />
  <br />

  <!-- Address -->
  <div class="form-group @if ($errors->first('address')) has-error has-feedback @endif">
    {!! Form::label("address", "Adresse", ['class' => 'control-label']) !!}
    {!! Form::text("address", ($coordinate->address) ? $coordinate->address : Request::old("address"), ['class' => 'form-control']) !!}

    @if ($errors->first('address'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('address') }}</span>
    @endif
  </div>

  <!-- Address detail -->
  <div class="form-group @if ($errors->first('address_detail')) has-error has-feedback @endif">
    {!! Form::label("address_detail", "Complément d'adresse", ['class' => 'control-label']) !!}
    {!! Form::textarea("address_detail", ($coordinate->address_detail) ? $coordinate->address_detail : Request::old("address_detail"), ['class' => 'form-control']) !!}

    @if ($errors->first('address_detail'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('address_detail') }}</span>
    @endif
  </div>

  <!-- City -->
  <div class="form-group @if ($errors->first('city')) has-error has-feedback @endif">
    {!! Form::label("city", "Ville", ['class' => 'control-label']) !!}
    {!! Form::text("city", ($coordinate->city) ? $coordinate->city : Request::old("city"), ['class' => 'form-control']) !!}

    @if ($errors->first('city'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('city') }}</span>
    @endif
  </div>

  <!-- Zip -->
  <div class="form-group @if ($errors->first('zip')) has-error has-feedback @endif">
    {!! Form::label("zip", "Code postal", ['class' => 'control-label']) !!}
    {!! Form::text("zip", ($coordinate->zip) ? $coordinate->zip : Request::old("zip"), ['class' => 'form-control']) !!}

    @if ($errors->first('zip'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('zip') }}</span>
    @endif
  </div>

  </div>

  <!-- Country -->
  <div class="form-group @if ($errors->first('country')) has-error has-feedback @endif">
    {!! Form::label("country", "Pays", ['class' => 'control-label']) !!}
    {!! Form::text("country", ($coordinate->country) ? $coordinate->country : Request::old("country"), ['class' => 'form-control']) !!}

    @if ($errors->first('country'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('country') }}</span>
    @endif
  </div>

  {!! Form::submit("Sauvegarder les modifications", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

@stop