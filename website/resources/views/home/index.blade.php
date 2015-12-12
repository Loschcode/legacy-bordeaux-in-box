@extends('layouts.master')
@section('content')

	<div class="center">

		<img class="img --artwork" src="{{ url('images/artwork.png') }}" />

		<p class="heading --md --xlight">
			Des surprises tous les mois dans une petite boîte rien que pour toi !
		</p>

		@if ($next_series->first()->getCounter() !== 0 || $next_series->first()->getCounter() === FALSE)

      <div class="buttons-punchline text-center">
        <a href="{{ url('order/gift') }}"><i class="fa fa-gift"></i> L'offrir</a>
        <a href="{{ url('order/classic') }}"><i class="fa fa-shopping-cart"></i> La recevoir</a>
      </div>

		@endif

		<div class="next-series">

				@if ($next_series->first()->getCounter() === 0)

					<div class="text-center">
						Il ne reste plus aucune box pour ce mois-ci !<br/>
						<div class="community community-small">
							<a target="_blank" href="https://www.facebook.com/BordeauxinBox?fref=ts"><i class="fa fa-facebook"></i> En savoir plus</a>
						</div>

					</div>

				@else

					@if ($next_series->first()->getCounter() !== FALSE)

						Il ne reste que {{$next_series->first()->getCounter()}} box(s) et {{ str_replace('dans', '', strtolower(HTML::diffHumans($next_series->first()->delivery, 5
						))) }} pour commander la box de {!! HTML::convertMonth($next_series->first()->delivery) !!}

					@endif

				@endif

		</div>



	</div>

	<div class="clearfix"></div>

	<div class="spacer200"></div>

	<div class="container">


		<div id="how-to" class="anchor"></div>

		<div class="text-center">
			<h1 class="title">Comment ça marche</h1>
			<div class="block-description">
				<p>
					Plaisir perso ou idée cadeau, voici comment ça marche !
				</p>
			</div>
		</div>

		<div class="spacer100"></div>

		<div class="row">
			<div class="col-md-4 center">
				<h2 class="heading --amatic --lg">Etape 1</h2>
        <div class="img --step1">
				  <img src="{{ url('images/step1.png') }}" />
        </div>
				<p class="text">
					Mamoune, poulette ou bichette ? Tu as le choix entre 3 thèmes ! Et pour que ta box te ressemble au mieux, nous te posons en plus  quelques petites questions !
				</p>
			</div>
			<div class="col-md-4 center">
				<h2 class="heading --amatic --lg">Etape 2</h2>
				<div class="block-step">
          <div class="img --step2">
					 <img src="{{ url('images/step2.png') }}" />
          </div>
				</div>
				<p class="text">
					Maintenant qu'on en sait un peu plus sur toi, on te laisse choisir si tu veux t'abonner ou juste tester.
				</p>
			</div>
			<div class="col-md-4 center">
				<h2 class="heading --amatic --lg">Etape 3</h2>
        <div class="img --step3">
				  <img src="{{ url('images/step3.png') }}" />
        </div>
				<p class="text">
					Et voilà ! Tu n'as plus qu'à attendre le début du mois suivant pour la recevoir chez toi ou la récupérer dans une de nos boutiques complices.
				</p>
			</div>
		</div>

	</div>

	<div id="inside" class="anchor"></div>

		<div class="inside">

			<div class="text-center">
				<h1 class="title">Ce qu'il y a dans la boîte !</h1>

				<div class="spacer100"></div>

					<div id="candies" class="w33 block block-green">
							<img class="icon icon-cake" src="{{ url('assets/img/icons/cake.png') }}" />
							<h1 class="title-step">Des produits prêts à manger</h1>
							<p>
								Du vin, forcément on est à Bordeaux, des macarons, du thé, des chocolats
								et plein d'autres choses à croquer salées ou sucrées !
							</p>
					</div>
					<div class="w33-middle block block-yellow">
							<img class="icon icon-underwear" src="{{ url('assets/img/icons/underwear.png') }}" />
							<h1 class="title-step">Des objets prêts à utiliser</h1>
							<p>
							  Des produits de beauté, des jolis bijoux, des accessoires originaux pour les enfants ou pour les grands,
							</p>
					</div>
					<div class="w33 block block-pink">
						<img class="icon icon-glasses" src="{{ url('assets/img/icons/glasses.png') }}" />
						<h1 class="title-step">Des offres prêtes à tester</h1>
						<p>
							 Places de concert, séances de bien-être, repas pour 2, visites culturelles, en plus des événements organisés par Bordeaux in Box.
						</p>
					</div>

					<div class="clearfix"></div>
				</div>
			</div>

		</div>

	</div>

	<div class="clearfix"></div>
	<div class="spacer150"></div>

	<div class="container">

		<div id="partners" class="anchor"></div>

		<div class="text-center">
			<h1 class="title">Les complices</h1>
		</div>

		<div class="spacer100"></div>

		<div class="partners">

          <?php $i = 0 ?>

          @foreach ($articles as $article)

            @if ($i === 4)
              <div class="clearfix"></div>
              <div class="spacer"></div>
              <?php $i = 0 ?>
            @endif


            <div class="col-md-3">
              <a href="{{ url('blog/article/' . $article->id) }}">
                <div class="img --thumbnail" title="{{ $article->title }}" data-gotham="tooltipster">
                  <img src="{{ $article->thumbnail->full }}" />
                </div>
              </a>
            </div>

            <?php $i++ ?>
          @endforeach

        <div class="clearfix"></div>


			<div class="text-center">
				<div class="spacer"></div>
				<a href="{{ url('blog') }}" class="button --xl">Voir les autres boutiques complices ...</a>
			</div>
		</div>


	</div>


	<div class="container">

	<div class="clearfix"></div>
	<div class="spacer100"></div>

</div>

	<div class="container">

	<div class="clearfix"></div>
	<div class="spacer100"></div>


		<div id="others" class="anchor">

			<div class="text-center">
				<h1 class="title">Et sinon</h1>
			</div>

			<p class="others">
				Pour savoir si tout ça est bien légal <a href="/legals">tu peux cliquer là</a> ... et si tu veux nous contacter pour parler de la pluie et du beau temps, <a href="{{ url('contact') }}">on est toujours présent !</a>
			</p>


		</div>

		<div class="spacer20"></div>
		<div class="center">
			<a target="_blank" href="https://www.facebook.com/BordeauxinBox?fref=ts" class="button --icon --xxl --facebook"><i class="fa fa-facebook"></i> Rejoins-nous sur Facebook !</a>
		</div>

</div>

<div class="spacer150"></div>
</div>

<div class="footer-container">
	@include('_includes.footer')
</div>


@stop
