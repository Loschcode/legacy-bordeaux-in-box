@extends('masterbox.layouts.master')

@section('gotham')
	{!! Html::gotham([
		'controller' => 'masterbox.guest.home.index',
		'no-boxes-title' => 'Désolé',
		'no-boxes-text' => 'Il ne reste plus aucune box pour ce mois ci !',
		'error-message' => '',
		'success-message' => ''
	]) !!}
@stop

@section('content')

<div class="artwork artwork__container">
	<img class="artwork__picture" src="{{ url('images/artwork.png') }}" />
</div>

<div class="grid-11@xs gr-centered@xs">
	<div class="title --home-punchline">Des surprises tout les mois dans une petite boîte rien que pour toi !</div>
</div>

<div class="container">

		{{-- Buttons to order --}}
		@if ($next_series->first() !== NULL)
			@if ($next_series->first()->getCounter() !== 0 || $next_series->first()->getCounter() === FALSE)
				
				<div class="row row-align-center@xs">
					<div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
						<a id="test-pick-gift" class="button button__home-action" href="{{ action('MasterBox\Customer\PurchaseController@getGift') }}"><i class="fa fa-gift"></i>L'offrir</a>
					</div>
					<div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
						<a id="test-pick-classic" class="button button__home-action" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}"><i class="fa fa-shopping-cart"></i> La recevoir</a>
					</div>
				</div>
				
				<div class="grid-11@xs gr-centered@xs">
					<div class="counter">
						<div class="counter__content">
							Il ne reste que {{$next_series->first()->getCounter()}} box(s) et {{ str_replace('dans', '', strtolower(Html::diffHumans($next_series->first()->delivery, 5
							))) }} pour commander la box de {!! Html::convertMonth($next_series->first()->delivery) !!}
						</div>
					</div>
				</div>


			@endif
		@endif

		{{-- No more boxes to order --}}
		@if ($next_series->first() === NULL or $next_series->first()->getCounter() === 0)

			<div class="row">
				<div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
					<a class="button button__home-action js-no-boxes" href="#"><i class="fa fa-gift"></i>L'offrir</a>
				</div>
				<div class="grid-3 push-3 grid-11@xs grid-centered@xs push-0@xs">
					<a class="button button__home-action js-no-boxes" href="#"><i class="fa fa-shopping-cart"></i> La recevoir</a>
				</div>
			</div>
		@endif
</div>

<div class="container grid-11@xs gr-centered@xs">
	{{-- Section how it works --}}
	<div id="how-to" class="section">
		<h1 class="section__title">Comment ça marche ?</h1>
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
</div>
<div class="clear"></div>

<div class="container">
	{{-- Section Inside the box --}}
	<div id="inside" class="section hide@xs">
		<h1 class="section__title">Ce qu'il y a dans la boîte !</h1>
	</div>
</div>

<div class="+spacer-small"></div>

<div class="container-fluid hide@xs">
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
	</div>
	<div class="clear"></div>

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
	@foreach ($articles->chunk(4) as $chunk)
		<div class="row row-align-center@xs">
			@foreach ($chunk as $article)
				<div class="grid-3 grid-12@xs gr-centered@xs">
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
				<div class="grid-1">
					<div class="seen__picture --france2"><img src="{{ url('images/seen/france3.png') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --objectif-aquitaine"><img src="{{ url('images/seen/objectif-aquitaine.jpg') }}" /></div>
				</div>
				<div class="grid-2">
					<div class="seen__picture --vivre-bordeaux"><img src="{{ url('images/seen/vivre-bordeaux.png') }}" /></div>
				</div>
			</div>
		</div>
	</div>


@stop

@section('footer-spacer')
@stop
