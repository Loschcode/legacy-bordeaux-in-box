@extends('masterbox.layouts.admin')


@section('content')
  
  <div
    id="gotham"
    data-form-errors="{{ $errors->has() }}"
  ></div>

  @include('masterbox.admin.partials.navbar_content')
  
  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Illustrations</h1>
      <h2 class="title title__subsection">Edition de l'illustration {{ $image_article->title }}</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getIllustrations') }}" class="button button__section"><i class="fa fa-list"></i> Voir les illustrations</a>
      </div>
    </div>
  </div>

  <div class="divider divider__section"></div>
  
  <div class="form">
    {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postEditIllustration', 'files' => true)) !!}
    
      {!! Form::hidden("image_article_id", $image_article->id) !!}

      {!! Form::label("title", "Titre", ['class' => 'form__label']) !!}
      {!! Form::text("title", Request::old("title") ? Request::old("title") : $image_article->title, ['class' => 'form__input']) !!}
      {!! Html::checkError('title', $errors) !!}
  
      {!! Form::label("slug", "Slug", ['class' => 'form__label']) !!}
      {!! Form::text("slug", Request::old("slug") ? Request::old("slug") : $image_article->slug, ['class' => 'form__input']) !!}
      {!! Html::checkError('slug', $errors) !!}

      {!! Form::label("description", "Description", ['class' => 'form__label']) !!}
      {!! Form::text("description", Request::old("description") ? Request::old("description") : $image_article->description, ['class' => 'form__input']) !!}
      {!! Html::checkError('description', $errors) !!}

      {!! Form::label("image", "Image", ['class' => 'form__label']) !!}

      <img src="{{ Html::resizeImage('small', $image_article->image->filename) }}" />
      <br/>

      {!! Form::file('image') !!}
      {!! Html::checkError('image', $errors) !!}


    {!! Form::submit("Editer l'illustration", ['class' => 'button button__submit']) !!}
    {!! Form::close() !!}
  </div>

@stop