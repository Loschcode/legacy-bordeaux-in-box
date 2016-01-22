@extends('masterbox.layouts.master')
@section('content')

<div
	id="gotham"
	data-controller="masterbox.guest.home.index"
	data-no-boxes-title="Désolé"
	data-no-boxes-text="Il ne reste plus aucune box pour ce mois ci !"
	data-success-message="@if (session()->has('status')) {{ session()->get('status') }} @endif"
></div>

<div class="artwork">
	<img class="artwork --picture" src="{{ url('images/artwork.png') }}" />
</div>

<div class="title --home-punchline">Des surprises tout les mois dans une petite boîte rien que pour toi !</div>

{{-- Buttons to order --}}
@if ($next_series->first()->getCounter() !== 0 || $next_series->first()->getCounter() === FALSE)
	
	<div class="row">
		<div class="grid-3 push-3">
			<a class="button button__home-action" href="{{ action('MasterBox\Customer\PurchaseController@getGift') }}"><i class="fa fa-gift"></i>L'offrir</a>
		</div>
		<div class="grid-3 push-3">
			<a class="button button__home-action" href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}"><i class="fa fa-shopping-cart"></i> La recevoir</a>
		</div>
	</div>

	<div class="counter">
		<div class="counter__content">
			Il ne reste que {{$next_series->first()->getCounter()}} box(s) et {{ str_replace('dans', '', strtolower(Html::diffHumans($next_series->first()->delivery, 5
			))) }} pour commander la box de {!! Html::convertMonth($next_series->first()->delivery) !!}
		</div>
	</div>


@endif

{{-- No more boxes to order --}}
@if ($next_series->first()->getCounter() === 0)

	<div class="row">
		<div class="grid-3 push-3">
			<a class="button button__home-action js-no-boxes" href="#"><i class="fa fa-gift"></i>L'offrir</a>
		</div>
		<div class="grid-3 push-3">
			<a class="button button__home-action js-no-boxes" href="#"><i class="fa fa-shopping-cart"></i> La recevoir</a>
		</div>
	</div>
@endif


{{-- Section how it works --}}
<div id="how-to" class="section">
	<h1 class="section__title">Comment ça marche ?</h1>
	<p class="section__description">Plaisir perso ou idée cadeau, voici comment ça marche !</p>
</div>

<div class="container-static">
	<div class="row">
		<div class="grid-4">
			<div class="step">
				<h2 class="step__title">Etape 1</h2>
				<div class="step__picture-container">
					<img class="step__picture --step1" src="{{ url('images/steps/step1.png') }}" />
				</div>
				<p class="step__description">
					Mamoune, poulette ou bichette ? Tu as le choix entre 3 thèmes ! Et pour que ta box te ressemble au mieux, nous te posons en plus quelques petites questions !
				</p>
			</div>
		</div>
		<div class="grid-4">
			<div class="step">
				<h2 class="step__title">Etape 2</h2>
				<div class="step__picture-container">
					<img class="step__picture --step2" src="{{ url('images/steps/step2.png') }}" />
				</div>
				<p class="step__description">
					Maintenant qu'on en sait un peu plus sur toi, on te laisse choisir si tu veux t'abonner ou juste tester.
				</p>
			</div>
		</div>
		<div class="grid-4">
			<div class="step">
				<h2 class="step__title">Etape 3</h2>
				<div class="step__picture-container">
					<img class="step__picture --step3" src="{{ url('images/steps/step3.png') }}" />
				</div>
				<p class="step__description">
					Et voilà ! Tu n'as plus qu'à attendre le début du mois suivant pour la recevoir chez toi ou la récupérer dans une de nos boutiques complices.
				</p>
			</div>
		</div>
	</div>
</div>

{{-- Section Inside the box --}}
<div id="inside" class="section">
	<h1 class="section__title">Ce qu'il y a dans la boîte !</h1>
</div>

<div class="+spacer-small"></div>

<div class="container-fluid">
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
</div>

{{-- Section Partners --}}
<div class="section">
	<h1 class="section__title">Nos complices</h1>
</div>
<div class="+spacer-small"></div>
<div class="container-static">
	@foreach ($articles->chunk(4) as $chunk)
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

	<div class="grid-5 grid-centered">
		<a class="button button__home-partner" href="#">Voir les autres boutiques complices ...</a>
	</div>
</div>

<div class="+spacer"></div>

@stop
