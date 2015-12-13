@extends('layouts.admin')

@section('page')
	<h1 class="page">Questions {{$box->title}}</h1>
@stop

@section('buttons')
	<a class="spyro-btn spyro-btn-success" href="{{ url('/admin/boxes/questions/new/' . $box->id) }}"><i class="fa fa-plus"></i> Ajouter une question</a>
@stop


@section('content')

	@if (Session::has('message'))
	  <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
	@endif
  
<table class="js-datas">

	<thead>

		<tr>
			<th>Position</th>
			<th>Question</th>
			<th>Résumé</th>
			<th>Filtre doit correspondre</th>
			<th>Slug</th>
			<th>Type</th>
			<th>Réponses</th>
			<th>Action</th>

		</tr>

	</thead>

	<tbody>

		@foreach ($questions as $question)

			<tr>
				<th>{{$question->position}}</th>
				<th>{{$question->question}}</th>
				<th>{{$question->short_question}}</th>
				<th>{!! HTML::boolYesOrNo($question->filter_must_match) !!}</th>
				<th>{{$question->slug}}</th>
				<th>{!! HTML::getReadableQuestionType($question->type) !!}</th>
				<th>
				@if (HTML::hasNoAnswerPossible($question->type))
					Cette question ne nécessite pas de réponse prédéfinie
				@else
					<a class="spyro-btn spyro-btn-primary spyro-btn-sm" href="{{url('admin/boxes/questions/answers/focus/'.$question->id)}}">
						@if ($question->answers()->first() !== NULL)
							Cette question possède {{$question->answers()->count()}} réponses
						@else
							Cette question ne possède aucune réponse pour le moment
						@endif
					</a>
				@endif
				</th>
				<th>
					<a data-toggle="tooltip" class="spyro-btn spyro-btn-warning spyro-btn-sm" title="Editer" href="{{ url('/admin/boxes/questions/edit/'.$question->id) }}"><i class="fa fa-pencil"></i></a>
					<a data-toggle="tooltip" class="spyro-btn spyro-btn-inverse spyro-btn-sm" title="Archiver" href="{{ url('/admin/boxes/questions/delete/'.$question->id) }}"><i class="fa fa-archive"></i></a>
				</th>
			</tr>


		@endforeach

	</tbody>

</table>

<br />

@stop