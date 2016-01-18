@extends('masterbox.layouts.admin')

@section('page')
  <h1 class="page">Réponses pour la question #{{$question->id}}</h1>
@stop

@section('content')

  @if (session()->has('message'))
    <div>{{ session()->get('message') }}</div>
  @endif
  
<h2>Réponses pour la question `{{$question->question}}` ({!! Html::getReadableQuestionType($question->type) !!})</h2>

<a href="{{ action('MasterBox\Admin\BoxQuestionsController@getIndex') }}">{{$question->question}}</a><br /><br />

<table>

	<thead>

		<tr>
			<th>Réponses</th>
			<th>Action</th>

		</tr>

	</thead>

	<tbody>

		@foreach ($answers as $answer)

			<tr>
				<th>{{$answer->content}}</th>
				<th><a href="{{ action('MasterBox\Admin\BoxQuestionsAnswersController@getEdit', ['id' => $answer->id]) }}">Editer</a> | <a href="{{ action('MasterBox\Admin\BoxQuestionsAnswersController@getDelete', ['id' => $answer->id]) }}">Archiver</a>
				</th>
			</tr>

		@endforeach

	</tbody>

</table>

<br />
<a href="{{ action('MasterBox\Admin\BoxQuestionsAnswersController@getNew', ['id' => $question->id]) }}">Ajouter une réponse à cette question</a>

@stop