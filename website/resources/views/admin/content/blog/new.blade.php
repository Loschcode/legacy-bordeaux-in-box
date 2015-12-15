@extends('layouts.admin')

@section('page')
  <i class="fa fa-bullhorn"></i> Nouveau article
@stop

@section('buttons')

  @if (URL::previous() != Request::root())
    <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>
  @endif

@stop

@section('content')

  {!! Form::open(array('action' => 'Admin\ContentController@postNewBlog', 'files' => true)) !!}

    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
      {{ Form::text("title", Request::old("title"), ['class' => 'form-control']) }}

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

@stop
