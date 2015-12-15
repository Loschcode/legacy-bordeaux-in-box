@extends('layouts.admin')

@section('page')
  <i class="fa fa-bullhorn"></i> Edition article #{{$blog_article->id}}
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


  {!! Form::open(array('action' => 'Admin\ContentController@postEditBlog', 'files' => true)) !!}

    {!! Form::hidden("blog_article_id", $blog_article->id) !!}


    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
      {!! Form::text("title", Request::old("title") ? Request::old("title") : $blog_article->title, ['class' => 'form-control']) !!}

      @if ($errors->first('title'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('title') }}</span>
      @endif
    </div>


    <!-- Slug -->
    <div class="form-group @if ($errors->first('slug')) has-error has-feedback @endif">
      {!! Form::label("slug", "Référencement", ['class' => 'control-label']) !!}
      {!! Form::text("slug", Request::old("slug") ? Request::old("slug") : $blog_article->slug, ['class' => 'form-control']) !!}

      @if ($errors->first('slug'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('slug') }}</span>
      @endif
    </div>

    <!-- Url -->
    <div class="form-group @if ($errors->first('url')) has-error has-feedback @endif">
      {!! Form::label("url", "Url", ['class' => 'control-label']) !!}
      {!! Form::text("url", Request::old("url") ? Request::old("url") : $blog_article->url, ['class' => 'form-control']) !!}

      @if ($errors->first('url'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('url') }}</span>
      @endif
    </div>

    <!-- Content -->
    <div class="form-group @if ($errors->first('content')) has-error has-feedback @endif">
      {!! Form::label("content", "Contenu", ['class' => 'control-label']) !!}
      {!! Form::textarea("content", Request::old("content") ? Request::old("content") : $blog_article->content, ['class' => 'form-control js-summernote']) !!}

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
      @else
        <span class="help-block">Laissez vide si vous ne voulez pas écraser l'image actuelle</span>
      @endif
    </div>

    {!! Form::submit("Mettre à jour cette article", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) !!}

  {!! Form::close() !!}

@stop