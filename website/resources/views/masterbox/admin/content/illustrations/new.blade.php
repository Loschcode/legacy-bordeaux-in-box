@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-picture-o"></i> Nouvelle Illustration
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

  {!! Form::open(array('action' => 'MasterBox\Admin\ContentController@postNewIllustration', 'files' => true)) !!}

    <div class="w80">

      <!-- Title -->
      <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
        {!! Form::label("title", "Titre", ['class' => 'control-label']) !!}
        {!! Form::text("title", Request::old("title"), ['class' => 'form-control']) !!}

        @if ($errors->first('title'))
          <span class="glyphicon glyphicon-remove form-control-feedback"></span>
          <span class="help-block">{{ $errors->first('title') }}</span>
        @endif
      </div>

      <!-- Description -->
      <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
        {!! Form::label("description", "Description", ['class' => 'control-label']) !!}
        {!! Form::text("description", Request::old("description"), ['class' => 'form-control']) !!}

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

      {!! Form::submit("Ajouter cette illustration", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}
      {!! Form::close() !!}
    </div>
@stop