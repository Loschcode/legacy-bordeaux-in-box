@extends('masterbox.layouts.master')


@section('content')

<div class="illustration">

	<div class="grid-8 grid-centered grid-11@xs">

		<h1 class="illustration__name">{{$image_article->title}}</h1>
		
		<div class="grid-8 grid-centered">
			<div class="illustration__image-container">
				<img class="illustration__image" src="{{ url($image_article->image->full) }}">
			</div>
		</div>

		<div class="illustration__description +text-center">
			{{ $image_article->description }}
		</div>

		<div class="+text-center">
			@if ($previous_article !== NULL || $next_article !== NULL)

				@if ($previous_article !== NULL)
				<a class="button button__default" href="{{ action('MasterBox\Guest\IllustrationsController@getIllustration', ['slug' => $previous_article->slug]) }}">&larr; Box précédente</a>
				@endif

				@if ($next_article !== NULL)
				<a class="button button__default" href="{{ action('MasterBox\Guest\IllustrationsController@getIllustration', ['slug' => $next_article->slug]) }}">Box suivante  &rarr;</a>
				@endif
			@endif
		</div>
	</div>
</div>


	@stop
