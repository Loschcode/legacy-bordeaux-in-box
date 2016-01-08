@extends('master-box.layouts.master')
@section('content')

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
		{!! View::make('_includes.footer')->with('stick', true) !!}
	@else
		{!! View::make('_includes.footer') !!}
	@endif
@stop