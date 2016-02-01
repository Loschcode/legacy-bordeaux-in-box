@extends('masterbox.layouts.admin')

@section('content')
  
  @include('masterbox.admin.partials.navbar_content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Illustration</h1>
      <h2 class="title title__subsection">Nouvelle Illustration</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getIllustrations') }}" class="button button__section"><i class="fa fa-list"></i> Voir les illustrations</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  
  <div class="form">
    {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postNewIllustration', 'files' => true)) !!}
    
      {!! Form::label("title", "Titre", ['class' => 'form__label']) !!}
      {!! Form::text("title", Request::old("title"), ['class' => 'form__input']) !!}
      {!! Html::checkError('title', $errors) !!}

      {!! Form::label("description", "Description", ['class' => 'form__label']) !!}
      {!! Form::text("description", Request::old("description"), ['class' => 'form__input']) !!}
      {!! Html::checkError('description', $errors) !!}
      

      {!! Form::label("image", "Image", ['class' => 'form__label']) !!}
      {!! Form::file('image') !!}
      {!! Html::checkError('image', $errors) !!}


    {!! Form::submit("Ajouter l'illustration", ['class' => 'button button__submit']) !!}
    {!! Form::close() !!}
  </div>

@stop
