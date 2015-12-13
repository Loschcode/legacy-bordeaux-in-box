@extends('layouts.admin')

@section('page')
  <h1 class="page">Nouvelle question {{$box->title}}</h1>
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
  
  {!! HTML::info("Remplissez le champs `slug` uniquement s'il va être utilisé ailleurs que dans le formulaire dans le système (demander à un développeur compétent si vous ne comprenez pas ce que cela signifie).") !!}

  {!! Form::open(array('action' => 'AdminBoxesQuestionsController@postNew')) !!}

  {!! Form::hidden('box_id', $box->id) !!}

    {!! HTML::info("Le résumé ne sera visible que depuis le panel administrateur. Veuillez rester pragmatique dans sa composition (1 ou 2 mots).") !!}
  
    <!-- Question -->
    <div class="form-group @if ($errors->first('question')) has-error has-feedback @endif">
      {!! Form::label("question", "Question", ['class' => 'control-label']) !!}
      {!! Form::text("question", Input::old("question"), ['class' => 'form-control']) !!}

      @if ($errors->first('question'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('question') }}</span>
      @endif
    </div>

    <!-- Short question -->
    <div class="form-group @if ($errors->first('short_question')) has-error has-feedback @endif">
      {!! Form::label("short_question", "Résumé", ['class' => 'control-label']) !!}
      {!! Form::text("short_question", Input::old("short_question"), ['class' => 'form-control']) !!}
      @if ($errors->first('short_question'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('short_question') }}</span>
      @endif
    </div>

    <!-- Filter -->
    <div class="form-group @if ($errors->first('filter_must_match')) has-error has-feedback @endif">
      {!! Form::label("filter_must_match", "Le filtre doit correspondre", ['class' => 'control-label']) !!}
      {!! Form::select('filter_must_match', ['0' => 'Non', '1' => 'Oui'], Input::old('filter_must_match'), ['class' => 'form-control'])!!}
      @if ($errors->first('filter_must_match'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('filter_must_match') }}</span>
      @endif
    </div>

    <!-- Slug -->
    <div class="form-group @if ($errors->first('slug')) has-error has-feedback @endif">
      {!! Form::label("slug", "Slug (facultatif)", ['class' => 'control-label']) !!}
      {!! Form::text("slug", Input::old("slug"), ['class' => 'form-control']) !!}
      @if ($errors->first('slug'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('slug') }}</span>
      @endif
    </div>

    <!-- Type -->
    <div class="form-group @if ($errors->first('type')) has-error has-feedback @endif">
      {!! Form::label("type", "Type", ['class' => 'control-label']) !!}
      {!! Form::select('type', HTML::getPossibleQuestionTypes(), Input::old('type'), ['class' => 'form-control']) !!}
      @if ($errors->first('type'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('type') }}</span>
      @endif
    </div>

    <!-- Position -->
    <div class="form-group @if ($errors->first('position')) has-error has-feedback @endif">
      {!! Form::label("position", "Position", ['class' => 'control-label']) !!}
      {!! Form::select('position', $position_listing, Input::old('position'), ['class' => 'form-control']) !!}
      @if ($errors->first('position'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('position') }}</span>
      @endif
    </div>


  {!! Form::submit("Ajouter cette question", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

  {!! Form::close() !!}
  
@stop