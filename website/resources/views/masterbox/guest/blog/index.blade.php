@extends('masterbox.layouts.master')
@section('content')

<div class="+spacer"></div>

<div class="container-static">
	
	@foreach ($blog_articles->chunk(4) as $chunk)
		<div class="row">
			@foreach ($chunk as $article)
				<div class="grid-3">
					<div class="partner">
						<div class="partner__picture-container">
							<a href="{{ action('MasterBox\Guest\BlogController@getArticle', ['slug' => $article->slug]) }}">
								<img class="partner__picture" src="{{ Html::resizeImage('medium', $article->thumbnail->filename) }}" />
							</a>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endforeach

	<div class="+spacer-small"></div>
	
	<div class="grid-6 grid-centered">
		<a href="{{ action('MasterBox\Guest\ContactController@getIndex', ['service' => 'com-partner']) }}" class="button button__submit">Envie de devenir un de nos complices ?</a>
	</div>

</div>

@stop