@extends('masterbox.layouts.admin')

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Points relais</h1>
      <h3 class="title title__subsection">Ajouter un point relais</h3>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\SpotsController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les points relais</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <div class="grid-6">
    {!! Form::open() !!}

      {!! Form::label("name", "Nom", ['class' => 'form__label']) !!}
      {!! Form::text("name", Request::old("name"), ['class' => 'form__input']) !!}
      {!! Html::checkError('name', $errors) !!}
    
      {!! Form::label("city", "Ville", ['class' => 'form__label']) !!}
      {!! Form::text("city", Request::old("city"), ['class' => 'form__input']) !!}
      {!! Html::checkError('name', $errors) !!}

      {!! Form::label("zip", "Code postal", ['class' => 'form__label']) !!}
      {!! Form::text("zip", Request::old("zip"), ['class' => 'form__input']) !!}
      {!! Html::checkError('zip', $errors) !!}


      {!! Form::label("address", "Adresse", ['class' => 'form__label']) !!}
      {!! Form::textarea("address", Request::old("address"), ['class' => 'form__input']) !!}
      {!! Html::checkError('address', $errors) !!}

      {!! Form::label("schedule", "Horaires", ['class' => 'form__label']) !!}
      {!! Form::textarea("schedule", Request::old("schedule"), ['class' => 'form__input']) !!}
      {!! Html::checkError('schedule', $errors) !!}


      {!! Form::submit("Ajouter ce point relais", ['class' => 'button button__default --blue']) !!}
    {!! Form::close() !!}
  </div>

  <div class="+spacer"></div>

@stop
