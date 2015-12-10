@section('page')
  <i class="fa fa-gift"></i> Nouvelle Boxe
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {{ HTML::info('Lors de l\'ajout d\'une nouvelle boxe elle sera désactivé par défaut') }}
  <div class="w80">
    {{ Form::open(array('action' => 'AdminBoxesController@postNew', 'files' => true)) }}


    <!-- Title -->
    <div class="form-group @if ($errors->first('title')) has-error has-feedback @endif">
      {{ Form::label("title", "Titre", ['class' => 'control-label']) }}
      {{ Form::text("title", Input::old("title"), ['class' => 'form-control']) }}

      @if ($errors->first('title'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('title') }}</span>
      @endif
    </div>

    <!-- Description -->
    <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
      {{ Form::label("description", "Description", ['class' => 'control-label']) }}
      {{ Form::text("description", Input::old("description"), ['class' => 'form-control']) }}

      @if ($errors->first('description'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('description') }}</span>
      @endif
    </div>

    <!-- Image -->
    <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">
      {{ Form::label("image", "Image", ['class' => 'control-label']) }}
      {{ Form::file('image', ['class' => 'form-control']) }}

      @if ($errors->first('image'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('image') }}</span>
      @endif
    </div>

    {{ Form::submit("Ajouter cette box", ['class' => 'spyro-btn spyro-btn-success spyro-btn-lg']) }}

    {{ Form::close() }}
  </div>

@stop
<?php
/*
@section("content")

  <h2>Ajouter une box</h2>
  
  @if (Session::has('message'))
  <div>{{ Session::get('message') }}</div>
  @endif

  {{ Form::open(array('action' => 'AdminBoxesController@postNew', 'files' => true)) }}

  @if ($errors->first('title'))
  {{{ $errors->first('title') }}}<br />
  @endif
  {{ Form::label("title", "Titre") }}
  {{ Form::text("title", Input::old("title")) }}
  <br />

  @if ($errors->first('image'))
  {{{ $errors->first('image') }}}<br />
  @endif
  {{ Form::label("image", "Image") }}
  {{ Form::file('image') }}

  <br /><br />

  {{ Form::submit("Ajouter cette box (elle sera désactivé par défaut)") }}

  {{ Form::close() }}
@stop
*/ ?>