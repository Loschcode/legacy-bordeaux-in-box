@section('content')

  {{ HTML::info("Un changement dans la sélection de réponses d'une question affectera la génération automatique de produits. Veuillez mettre à jour les filtres dans la section correspondante après avoir édité une question.") }}

  {{ Form::open(array('action' => 'AdminBoxesQuestionsAnswersController@postEdit')) }}

  {{ Form::hidden('answer_id', $answer->id) }}

  <h2>Editer une réponse de la question `{{$question->question}}` ({{HTML::getReadableQuestionType($question->type)}})</h2>

  @if (Session::has('message'))
  <div>{{ Session::get('message') }}</div>
  @endif

  @if ($errors->first('content'))
  {{{ $errors->first('content') }}}<br />
  @endif
  {{ Form::label("content", "Réponse")}}
  {{ Form::text("content", (Input::old("content")) ? Input::old("content") : $answer->content) }}
  <br />

  {{ Form::submit("Mettre à jour cette réponse") }}

  {{ Form::close() }}
  
@stop