@extends('layouts.admin')

@section('content')

  {!! Html::info("Un changement dans la sélection de réponses d'une question affectera la génération automatique de produits. Veuillez mettre à jour les filtres dans la section correspondante après avoir édité une question.") !!}

  {!! Form::open(array('action' => 'Admin\BoxesQuestionsAnswersController@postEdit')) !!}

  {!! Form::hidden('answer_id', $answer->id) !!}

  <h2>Editer une réponse de la question `{{$question->question}}` ({!! Html::getReadableQuestionType($question->type) !!}})</h2>

  @if (session()->has('message'))
    <div>{{ session()->get('message') }}</div>
  @endif

  @if ($errors->first('content'))
    {{{ $errors->first('content') }}}<br />
  @endif

  {!! Form::label("content", "Réponse") !!}
  {!! Form::text("content", (Request::old("content")) ? Request::old("content") : $answer->content) !!}

  <br />

  {!! Form::submit("Mettre à jour cette réponse") !!}

  {!! Form::close() !!}
  
@stop