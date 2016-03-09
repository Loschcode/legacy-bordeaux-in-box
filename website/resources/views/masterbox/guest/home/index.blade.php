@extends('masterbox.layouts.master')

@section('gotham')
	{!! Html::gotham([
		'controller' => 'masterbox.guest.home.index',
		'no-boxes-title' => 'Désolé',
		'no-boxes-text' => 'Il ne reste plus aucune box pour ce mois ci !',
	]) !!}
@stop

@section('meta-facebook')
	<meta property="og:url" content="{{ Request::url() }}" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Bordeaux in Box" />
	<meta property="og:description" content="Chaque début de mois, on emballe la Gironde et on te l'expédie dans une jolie box." />
	@foreach ($image_articles as $image_article)
		<meta property="og:image" content="{{ $image_article->image->filename }}" />
	@endforeach
@stop

@section('header')
@stop

@section('content')

<div class="hero">
	<div class="hero__container" style="background-image: url('{{ url('images/teasing/teasing-avril.jpg') }}')">
		<div class="hero__overlay"></div>
		<div class="hero__content">

			@include('masterbox.partials.navbar', ['navbar_home' => true])
			
			<div class="hero__logo">
				<div class="grid-4 grid-11@xs grid-centered">
					<div class="logo">
						<img class="logo__picture" src="{{ url('images/logo-white.png') }}" />
					</div>
				</div>
			</div>

			<h3>En Avril, craquez pour des gourmandises 100% girondines.</h3>
			
			<div class="+spacer"></div>
			
			<div class="hero__decorate">
				<div class="container">
					@include('masterbox.partials.buttons_call_actions', ['button' => 'button__home-action'])
				</div>
			</div>

			@include('masterbox.partials.counter_call_actions')

			<div class="+text-right">
				<h1>#Gourmandise</h1>
			</div>
		</div>
	</div>
</div>

<!--
<div class="grid-11@xs gr-centered@xs">
	<div class="title --home-punchline">Des surprises tout les mois dans une petite boîte rien que pour toi !</div>
</div>
-->


<div class="container grid-11@xs gr-centered@xs">
	{{-- Section how it works --}}
	<div id="how-to" class="section">
		<h1 class="section__title --clear-space">Comment ça marche</h1>
		<p class="section__description">Plaisir perso ou idée cadeau, voici comment ça marche !</p>
	</div>
</div>


<div class="container-static">
	<div class="row">
		<div class="grid-4 grid-11@xs gr-centered@xs">
			<div class="step">
				<h2 class="step__title">Etape 1</h2>
				<div class="step__picture-container">
					<img class="step__picture --step2" src="{{ url('images/steps/step2.png') }}" />
				</div>
				<p class="step__description">
					Découvre les précédentes box et abonne-toi en quelques clics.
				</p>
			</div>
		</div>
		<div class="grid-4 grid-11@xs gr-centered@xs">
			<div class="step">
				<h2 class="step__title">Etape 2</h2>
				<div class="step__picture-container">
					<img class="step__picture --step3" src="{{ url('images/steps/step3.png') }}" />
				</div>
				<p class="step__description">
					Chaque début de mois, on emballe la Gironde et on te l'expédie dans une jolie box.
				</p>
			</div>
		</div>
		<div class="grid-4 grid-11@xs gr-centered@xs">
			<div class="step">
				<h2 class="step__title">Etape 3</h2>
				<div class="step__picture-container">
					<img class="step__picture --step1" src="{{ url('images/steps/step1.png') }}" />
				</div>
				<p class="step__description">
					Une fois reçue, tu peux donner ton avis sur le site et découvrir les produits et les petites marques derrières !
				</p>
			</div>
		</div>
	</div>
	
	<div class="+spacer-small"></div>
	@include('masterbox.partials.buttons_call_actions', ['button' => 'button__home-sub-action'])

</div>
<div class="clear"></div>

<div class="container">
	{{-- Section Inside the box --}}
	<div id="inside" class="section hide@xs">
		<h1 class="section__title">Ce qu'il y a dans la boîte !</h1>
	</div>
</div>

<div class="+spacer-small"></div>

<div class="container-static">

	<div id="freewall-boxes">
		@foreach ($image_articles as $article)
			<div class="js-brick" style="width: 250px">
					<a rel="showcase" class="js-showcase" title="{{ $article->title }}" href="{{ $article->image->full }}">
						<img class="partner__picture" src="{{ Html::resizeImage('medium', $article->image->filename) }}" />
					</a>
			</div>
		@endforeach
	</div>

	<!--
	<div class="row">
		<div class="grid-4 background background__green">
			<div class="product">
				<div class="product__picture-container">
					<img class="product__picture" src="{{ url('images/products/cake.png') }}" />
				</div>
				<h3 class="product__title">Des produits prêts à manger</h3>
				<p class="product__description">
					Du vin, forcément on est à Bordeaux, des macarons, du thé, des chocolats et plein d'autres choses à croquer salées ou sucrées !
				</p>
			</div>
		</div>
		<div class="grid-4 background background__yellow">
			<div class="product">
				<div class="product__picture-container">
					<img class="product__picture --underwear" src="{{ url('images/products/underwear.png') }}" />
				</div>
				<h3 class="product__title">Des objets prêts à utiliser</h3>
				<p class="product__description">
					Des produits de beauté, des jolis bijoux, des accessoires originaux pour les enfants ou pour les grands,				
				</p>
			</div>
		</div>
		<div class="grid-4 background background__pink">
			<div class="product">
				<div class="product__picture-container">
					<img class="product__picture --glasses" src="{{ url('images/products/glasses.png') }}" />
				</div>
				<h3 class="product__title">Des offres prêtes à tester</h3>
				<p class="product__description">
					Places de concert, séances de bien-être, repas pour 2, visites culturelles, en plus des événements organisés par Bordeaux in Box.
				</p>
			</div>
		</div>
		-->

		
		<div class="+spacer-extra-small"></div>



</div>
<div class="clear"></div>

<div class="container">
	{{-- Section Partners --}}
	<div class="section">
		<h1 class="section__title">Nos complices</h1>
	</div>
</div>

<div class="+spacer-small"></div>

<div class="container-static">
	
	<div id="freewall-partners">
		@foreach ($articles as $article)
			<div class="js-brick" style="width: 250px">
					<a href="{{ action('MasterBox\Guest\BlogController@getArticle', ['slug' => $article->slug]) }}">
						<img class="partner__picture" src="{{ Html::resizeImage('medium', $article->thumbnail->filename) }}" />
					</a>
			</div>
		@endforeach
	</div>


	<div class="grid-5 grid-centered grid-11@xs">
		<a class="button button__home-partner" href="{{ action('MasterBox\Guest\BlogController@getIndex') }}">Voir les autres boutiques complices ...</a>
	</div>
</div>
<div class="clear"></div>

<div class="+spacer"></div>


{{-- Section seen --}}
<div class="container hide@xs">
	<div class="section">
		<h1 class="section__title">Ils parlent de nous</h1>
	</div>
</div>

	<div class="seen seen__wrapper hide@xs">
		<div class="container-static">
			<div class="row seen__line">
				<div class="grid-2">
				<div class="seen__picture --elle"><img src="{{ url('images/seen/elle.png') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --direct-matin"><img src="{{ url('images/seen/direct-matin.png') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --france-bleu"><img src="{{ url('images/seen/france-bleu.png') }}" /></div>
				</div>
				<div class="grid-1">
					<div class="seen__picture --blackbox"><img src="{{ url('images/seen/blackbox.png') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --vivre-bordeaux"><img src="{{ url('images/seen/vivre-bordeaux.png') }}" /></div>
				</div>
				<div class="grid-1">
					<div class="seen__picture --france3"><img src="{{ url('images/seen/france3.png') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --objectif-aquitaine"><img src="{{ url('images/seen/objectif-aquitaine.jpg') }}" /></div>
				</div>
			</div>
		</div>
	</div>


@stop

@section('footer-spacer')
@stop
