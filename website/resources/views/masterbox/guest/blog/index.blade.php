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
							<a href="{{ action('MasterBox\Guest\BlogController@getArticle', ['id' => $article->id]) }}">
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
		<a href="{{ action('MasterBox\Guest\BlogController@getRedirectContact') }}" class="button button__submit">Envie de devenir un de nos complices ?</a>
	</div>

</div>
	<?php /*
	<div class="container blog">

		@if ($blog_articles->count() === 0)
	 		<div class="spyro-well text-center">
				Aucun article pour le moment
			</div>
		@else

			@foreach ($blog_articles as $blog_article)
				<div class="blog-post">
					<div class="col-md-8 col-md-offset-2">

						<a class="blog-title" href="{{url('/blog/article/'.$blog_article->id)}}">{{ $blog_article->title }}</a><br />

						<img class="img-responsive thumbnail blog-align" src="{{ url($blog_article->thumbnail->full) }}">

						<a href="{{url('/blog/article/'.$blog_article->id)}}" class="spyro-btn spyro-btn-green spyro-btn-block upper spyro-btn-lg text-center">Voir l'article</a>
					</div>

					<div class="clearfix"></div>
				</div>

				<div class="spacer100"></div>

			@endforeach

			<div class="text-center">
				{!! $blog_articles->render() !!}
			</div>

		@endif
	</div>

	<div class="spacer100"></div>


	@if ($blog_articles->count() === 0)
		{!! View::make('masterbox.partials.front.footer')->with('stick', true) !!}
	@else
		{!! View::make('masterbox.partials.front.footer') !!}
	@endif
	*/ ?>
@stop