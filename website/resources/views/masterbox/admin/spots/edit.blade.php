@extends('masterbox.layouts.admin')

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Points relais</h1>
      <h3 class="title title__subsection">Edition point relais (#{{ $spot->id }})</h3>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\SpotsController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les points relais</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>

  {!! Form::open() !!}
  {!! Form::hidden('delivery_spot_id', $spot->id) !!}

    {!! Form::label("name", "Nom", ['class' => 'form__label']) !!}
    {!! Form::text("name", ($spot->name) ? $spot->name : Request::old("name"), ['class' => 'form__input']) !!}
    {!! Html::checkError('name', $errors) !!}

    {!! Form::label("city", "Ville", ['class' => 'form__label']) !!}
    {!! Form::text("city", ($spot->city) ? $spot->city : Request::old("city"), ['class' => 'form__input']) !!}
    {!! Html::checkError('city', $errors) !!}

    {!! Form::label("zip", "Code postal", ['class' => 'form__label']) !!}
    {!! Form::text("zip", ($spot->zip) ? $spot->zip : Request::old("zip"), ['class' => 'form__input']) !!}
    {!! Html::checkError('zip', $errors) !!}

    {!! Form::label("address", "Adresse", ['class' => 'form__label']) !!}
    {!! Form::textarea("address", ($spot->address) ? $spot->address : Request::old("address"), ['class' => 'form__input']) !!}
    {!! Html::checkError('address', $errors) !!}



    {!! Form::label("schedule", "Horaires", ['class' => 'form__label']) !!}
    {!! Form::textarea("schedule", ($spot->schedule) ? $spot->schedule : Request::old("schedule"), ['class' => 'form__input']) !!}
    {!! Html::checkError('schedule', $errors) !!}

  {!! Form::submit("Sauvegarder les modifications", ['class' => 'button button__default --blue']) !!}

@stop