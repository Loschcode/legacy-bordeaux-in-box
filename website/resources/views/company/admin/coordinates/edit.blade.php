@extends('company.layouts.admin')

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section"><i class="fa fa-location-arrow"></i> Edition Coordonnée #{{ $coordinate->id }}</h1>
    </div>
    <div class="grid-4 +text-right">
      <a href="{{ action('Company\Admin\CoordinatesController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir toutes les coordonnées</a>
    </div>
  </div>

  <div class="divider divider__section"></div>

  {!! Form::open() !!}
  {!! Form::hidden('coordinate_id', $coordinate->id) !!}
  
  <div class="typography">
    <strong>Google Place ID:</strong> {{$coordinate->place_id}}
  </div>

  {!! Form::label("address", "Adresse", ['class' => 'form__label']) !!}
  {!! Form::text("address", ($coordinate->address) ? $coordinate->address : Request::old("address"), ['class' => 'form__input']) !!}

  {!! Form::label("address_detail", "Complément d'adresse", ['class' => 'form__label']) !!}
  {!! Form::text("address_detail", ($coordinate->address_detail) ? $coordinate->address_detail : Request::old("address_detail"), ['class' => 'form__input']) !!}

  {!! Form::label("city", "Ville", ['class' => 'form__label']) !!}
  {!! Form::text("city", ($coordinate->city) ? $coordinate->city : Request::old("city"), ['class' => 'form__input']) !!}

  {!! Form::label("zip", "Code postal", ['class' => 'form__label']) !!}
  {!! Form::text("zip", ($coordinate->zip) ? $coordinate->zip : Request::old("zip"), ['class' => 'form__input']) !!}

  {!! Form::label("country", "Pays", ['class' => 'form__label']) !!}
  {!! Form::text("country", ($coordinate->country) ? $coordinate->country : Request::old("country"), ['class' => 'form__input']) !!}
  
  <div class="+spacer-extra-small"></div>

  {!! Form::submit("Sauvegarder les modifications", ['class' => 'button button__default --blue']) !!}

@stop