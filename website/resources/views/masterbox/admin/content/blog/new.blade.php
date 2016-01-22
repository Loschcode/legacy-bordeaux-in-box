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
      <h2 class="title title__subsection">Nouveau article</h2>
    </div>
    <div class="grid-4">
      <div class="+text-right">
        <a href="{{ action('MasterBox\Admin\ContentController@getNewBlog') }}" class="button button__section"><i class="fa fa-list"></i> Voir les articles</a>
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

  <?php /*
  {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postNewBlog', 'files' => true)) !!}

    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
      {!! Form::text("title", Request::old("title"), ['class' => 'form-control']) !!}

      @if ($errors->first('title'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('title') }}</span>
      @endif
    </div>

    <!-- Slug -->
    <div class="form-group @if ($errors->first('slug')) has-error has-feedback @endif">
      {!! Form::label("slug", "Référencement", ['class' => 'control-label']) !!}
      {!! Form::text("slug", Request::old("slug"), ['class' => 'form-control']) !!}

      @if ($errors->first('slug'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('slug') }}</span>
      @endif
    </div>

    <!-- Url -->
    <div class="form-group @if ($errors->first('url')) has-error has-feedback @endif">
      {!! Form::label("url", "Url", ['class' => 'control-label']) !!}
      {!! Form::text("url", Request::old("url"), ['class' => 'form-control']) !!}

      @if ($errors->first('url'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('url') }}</span>
      @endif
    </div>

    <!-- Content -->
    <div class="form-group @if ($errors->first('content')) has-error has-feedback @endif">
      {!! Form::label("content", "Contenu", ['class' => 'control-label']) !!}
      {!! Form::textarea("content", Request::old("content"), ['class' => 'form-control js-summernote']) !!}

      @if ($errors->first('content'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('content') }}</span>
      @endif
    </div>

    <!-- Thumbnail -->
    <div class="form-group @if ($errors->first('thumbnail')) has-error has-feedback @endif">
      {!! Form::label("thumbnail", "Image d'illustration", ['class' => 'control-label']) !!}
      {!! Form::file('thumbnail') !!}

      @if ($errors->first('thumbnail'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('thumbnail') }}</span>
      @endif
    </div>



  {!! Form::submit("Ajouter cet article", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}
  {!! Form::close() !!}

  */ ?>

@stop
