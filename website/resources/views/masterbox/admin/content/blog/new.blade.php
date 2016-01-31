@extends('masterbox.layouts.admin')

@section('content')
  
  <div
    id="gotham"
    data-form-errors="{{ $errors->has() }}"
  ></div>

  @include('masterbox.admin.partials.navbar_content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Blog</h1>
      <h2 class="title title__subsection">Nouvel article</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getBlog') }}" class="button button__section"><i class="fa fa-list"></i> Voir les articles</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <div class="form">
    {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postNewBlog', 'files' => true)) !!}
    
    <div class="row">
      <div class="grid-4">
        {!! Form::label("title", "Titre", ['class' => 'form__label']) !!}
        {!! Form::text("title", Request::old("title"), ['class' => 'form__input']) !!}
        {!! Html::checkError('title', $errors) !!}
      </div>
      
      <div class="grid-4">
        {!! Form::label("slug", "Référencement", ['class' => 'form__label']) !!}
        {!! Form::text("slug", Request::old("slug"), ['class' => 'form__input']) !!}
        {!! Html::checkError('slug', $errors) !!}
      </div>
      
      <div class="grid-4">
        {!! Form::label("url", "Url (permalink)", ['class' => 'form__label']) !!}
        {!! Form::text("url", Request::old("url"), ['class' => 'form__input']) !!}
        {!! Html::checkError('url', $errors) !!}
      </div>
    </div>

    {!! Form::label("content", "Contenu", ['class' => 'form__label']) !!}
    {!! Form::textarea("content", Request::old("content"), ['class' => 'form__markdown js-markdown']) !!}
    {!! Html::checkError('content', $errors) !!}


    {!! Form::label("thumbnail", "Image d'illustration", ['class' => 'form__label']) !!}
    {!! Form::file('thumbnail') !!}
    {!! Html::checkError('thumbnail', $errors) !!}


    {!! Form::submit("Ajouter cet article", ['class' => 'button button__submit']) !!}
    {!! Form::close() !!}
  </div>

@stop
