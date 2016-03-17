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

@section('header-divider')
@stop

@section('content')
	
	  <ul id="slider">
	    <li>
		    <div class="hero">
		    	<div class="hero__container" style="background-image: url('{{ url('images/box-february/cover.jpg') }}');">
		    		<div class="hero__overlay" style="opacity: 0.5"></div>
						

			    		<div class="hero__content">	
			    			<h2 class="hero__title --long-text">Tous les mois, des créations de Bordeaux et sa région <br/> directement envoyés chez vous, où que vous soyez !</h2>
			    			<a href="#" class="button__hero">S'abonner</a>

			    						
			    		</div>
		    	</div>
		    </div>
	    </li>
	        <li>
	    	    <div class="hero">
	    	    	<div class="hero__container" style="background-image: url('{{ url('images/teasing/teasing-avril.jpg') }}')">
	    	    		<div class="hero__overlay"></div>
	    					
								
	    		    		<div class="hero__content">	
	    		    			<h2 class="hero__title">En Avril, craquez pour des gourmandises 100% girondines.</h2>
	    		    			<a class="button__hero">S'abonner</a>
	    		    		</div>
	    	    	</div>
	    	    </div>
	        </li>
	  
	  </ul>



<!--
<div class="container grid-11@xs gr-centered@xs">
	{{-- Section how it works --}}
	<div id="how-to" class="section">
		<h1 class="section__title --clear-space">Comment ça marche</h1>
		<p class="section__description">Plaisir perso ou idée cadeau, voici comment ça marche !</p>
	</div>
</div>
-->

<!--
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

		
		<div class="+spacer-extra-small"></div>



</div>
<div class="clear"></div>
-->

<div class="container">
	{{-- Section Partners --}}
	<div class="section">
		<h1 class="section__title --clear-space">Nos complices</h1>
		<p class="section__description">Plus de 120 marques dénichées actuellement !</p>
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

	<div class="+spacer-small"></div>
	<div class="grid-5 grid-centered grid-11@xs">
		<a class="button button__home-partner" href="{{ action('MasterBox\Guest\BlogController@getIndex') }}">Voir les autres boutiques complices ...</a>
	</div>
</div>
<div class="clear"></div>

<div class="+spacer"></div>



	<div class="seen seen__wrapper hide@xs">
		<div class="section">
			<h1 class="section__title --clear-space">Ils parlent de nous</h1>
		</div>
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

<script type="text/javascript">
    $(document).ready(function() {
        $("#slider").lightSlider({item: 1, loop: true, slideMargin: 0, pager: false, auto: true, pause: 5000, speed: 1000});
    });
</script>
@stop

@section('footer-spacer')
@stop

