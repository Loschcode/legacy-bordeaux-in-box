@extends('masterbox.layouts.admin')

@section('page')
  <h1 class="page">Edition question #{{ $question->id }}</h1>
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  {!! Html::info("Faites très attention au `slug` certains (tels `sponsor` ou `birthday`) sont utilisés pour le développement, leur disparition entraîne l'impossibilité d'utiliser les données sur le système.") !!}

  {!! Form::open(array('action' => 'MasterBox\Admin\BoxQuestionsController@postEdit')) !!}

  {!! Form::hidden('question_id', $question->id) !!}

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif
  


  <!-- Question -->
  <div class="form-group @if ($errors->first('question')) has-error has-feedback @endif">
    {!! Form::label("question", "Question", ['class' => 'control-label']) !!}
    {!! Form::text("question", (Request::old("question")) ? Request::old("question") : $question->question, ['class' => 'form-control']) !!}

    @if ($errors->first('question'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{!! $errors->first('question') !!}</span>
    @endif
  </div>

  <!-- Question Gift -->
  <div class="form-group @if ($errors->first('question_gift')) has-error has-feedback @endif">
    {!! Form::label("question_gift", "Question à offrir", ['class' => 'control-label']) !!}
    {!! Form::text("question_gift", (Request::old("question_gift")) ? Request::old("question_gift") : $question->question_gift, ['class' => 'form-control']) !!}

    @if ($errors->first('question_gift'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{!! $errors->first('question_gift') !!}</span>
    @endif
  </div>

  <!-- Short question -->
  <div class="form-group @if ($errors->first('short_question')) has-error has-feedback @endif">
    {!! Form::label("short_question", "Résumé", ['class' => 'control-label']) !!}
    {!! Form::text("short_question", (Request::old("short_question")) ? Request::old("short_question") : $question->short_question, ['class' => 'form-control']) !!}
    @if ($errors->first('short_question'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('short_question') }}</span>
    @endif
  </div>

  <!-- Slug -->
  <div class="form-group @if ($errors->first('slug')) has-error has-feedback @endif">
    {!! Form::label("slug", "Slug (facultatif)", ['class' => 'control-label']) !!}
    {!! Form::text("slug", (Request::old("slug")) ? Request::old("slug") : $question->slug, ['class' => 'form-control']) !!}
    @if ($errors->first('slug'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('slug') }}</span>
    @endif
  </div>

  <!-- Type -->
  <div class="form-group @if ($errors->first('type')) has-error has-feedback @endif">
    {!! Form::label("type", "Type", ['class' => 'control-label']) !!}
    {!! Form::select('type', Html::getPossibleQuestionTypes(), (Request::old("type")) ? Request::old("type") : $question->type, ['class' => 'form-control']) !!}<br />
    @if ($errors->first('type'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{{ $errors->first('type') }}</span>
    @endif
  </div>

  <!-- Position -->
  <div class="form-group @if ($errors->first('position')) has-error has-feedback @endif">
    {!! Form::label("position", "Position", ['class' => 'control-label']) !!}
    {!! Form::select('position', $position_listing, (Request::old("position")) ? Request::old("position") : $question->position, ['class' => 'form-control']) !!}
    @if ($errors->first('position'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{!! $errors->first('position') !!}</span>
    @endif
  </div>

  <!-- Only Gift -->
  <div class="form-group @if ($errors->first('only_gift')) has-error has-feedback @endif">
    {!! Form::label("only_gift", "Exclusif à offrir", ['class' => 'control-label']) !!}
    {!! Form::select('only_gift', [0 => 'Non', 1 => 'Oui'], (Request::old("only_gift")) ? Request::old("only_gift") : $question->only_gift, ['class' => 'form-control']) !!}
    @if ($errors->first('only_gift'))
      <span class="glyphicon glyphicon-remove form-control-feedback"></span>
      <span class="help-block">{!! $errors->first('only_gift') !!}</span>
    @endif
  </div>

  {!! Form::submit("Mettre à jour cette question", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

  {!! Form::close() !!}
  
@stop