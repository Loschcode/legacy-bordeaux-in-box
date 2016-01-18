@extends('masterbox.layouts.admin')

@section('page')
  <h1 class="page">Réponses pour la question #{{$question->id}}</h1>
@stop

@section('content')

  @if (session()->has('message'))
    <div>{{ session()->get('message') }}</div>
  @endif
  
<h2>Réponses pour la question `{{$question->question}}` ({!! Html::getReadableQuestionType($question->type) !!})</h2>

Fil : <a href="{{ url('/admin/boxes') }}">{{$box->title}}</a> - <a href="{{ url('/admin/boxes/questions/focus/'.$box->id) }}">{{$question->question}}</a><br /><br />

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
				<th><a href="{{ url('/admin/boxes/questions/answers/edit/'.$answer->id) }}">Editer</a> | <a href="{{ url('/admin/boxes/questions/answers/delete/'.$answer->id) }}">Archiver</a>
				</th>
			</tr>

		@endforeach

	</tbody>

</table>

<br />
<a href="{{ url('/admin/boxes/questions/answers/new/' . $question->id) }}">Ajouter une réponse à cette question</a>

@stop