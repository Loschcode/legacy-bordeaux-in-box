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



<?php /*
@section('page')
  <i class="fa fa-picture-o"></i> Edition de l'illustration #{{ $image_article->id }}
@stop

@section('buttons')

  @if (URL::previous() != Request::root())
    <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>
  @endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postEditIllustration', 'files' => true)) !!}

  {!! Form::hidden("image_article_id", $image_article->id) !!}

  <div class="w80">

    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
      {!! Form::text("title", Request::old("title") ? Request::old("title") : $image_article->title, ['class' => 'form-control']) !!}

      @if ($errors->first('title'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('title') }}</span>
      @endif
    </div>

    <!-- Slug -->
    <div class="form-group @if ($errors->first('slug')) has-error has-feedback @endif">
      {!! Form::label("slug", "Slug", ['class' => 'control-label']) !!}
      {!! Form::text("slug", Request::old("slug") ? Request::old("slug") : $image_article->slug, ['class' => 'form-control']) !!}

      @if ($errors->first('slug'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('slug') }}</span>
      @endif
    </div>

    <!-- Description -->
    <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
      {!! Form::label("description", "Description", ['class' => 'control-label']) !!}
      {!! Form::text("description", Request::old("description") ? Request::old("description") : $image_article->description, ['class' => 'form-control']) !!}

      @if ($errors->first('description'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('description') }}</span>
      @endif
    </div>


    <!-- Image -->
    <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">
      {!! Form::label("image", "Image", ['class' => 'control-label']) !!}
      {!! Form::file('image', ['class' => 'form-control']) !!}

      @if ($errors->first('image'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('image') }}</span>
      @endif
    </div>

    {!! Form::submit("Editer cette illustration", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}
    {!! Form::close() !!}
  </div>
@stop
*/ ?>
