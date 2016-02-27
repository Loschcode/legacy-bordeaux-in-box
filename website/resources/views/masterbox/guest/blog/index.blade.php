@extends('masterbox.layouts.master')

@section('gotham')
	{!! Html::gotham([
		'controller' => 'masterbox.guest.blog.index'
	]) !!}
@stop

@section('content')

<div class="+spacer"></div>

<div class="container-static">
	
	<div id="freewall">
		@foreach ($blog_articles as $article)
			<div class="js-brick grid-3 grid-11@xs">
					<a href="{{ action('MasterBox\Guest\BlogController@getArticle', ['slug' => $article->slug]) }}">
						<img class="partner__picture" src="{{ Html::resizeImage('medium', $article->thumbnail->filename) }}" />
					</a>
			</div>
		@endforeach
	</div>

	<div class="+spacer-small"></div>
	
	<div class="grid-6 grid-11@xs grid-centered">
		<a href="{{ action('MasterBox\Guest\ContactController@getIndex', ['service' => 'com-partner']) }}" class="button button__submit">Envie de devenir un de nos complices ?</a>
	</div>

</div>

@stop