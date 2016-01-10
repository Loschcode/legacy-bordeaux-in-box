@extends('layouts.admin')

@section('content')
  {!! Html::info("N'oubliez pas de mettre à jour la section de filtrage des produits, la question ne sera pas auto-validée par défaut lors des sélections automatiques de produit") !!}

  {!! Form::open(array('action' => 'Admin\\BoxesQuestionsAnswersController@postNew')) !!}

  {!! Form::hidden('question_id', $question->id) !!}

  <h2>Ajouter une réponse à la question `{{$question->question}}` ({!! Html::getReadableQuestionType($question->type) !!})</h2>

  @if (session()->has('message'))
    <div>{{ session()->get('message') }}</div>
  @endif

  @if ($errors->first('content'))
    {{{ $errors->first('content') }}}<br />
  @endif

  {!! Form::label("content", "Contenu") !!}
  {!! Form::text("content", Request::old("content")) !!}
  <br />

  {!! Form::submit("Ajouter cette réponse") !!}

  {!! Form::close() !!}
@stop