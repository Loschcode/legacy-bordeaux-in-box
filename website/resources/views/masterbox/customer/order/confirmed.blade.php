@extends('masterbox.layouts.master')

@section('content')
	
	<div class="container confirmed">

		<h1>Confirmation de paiement</h1>

		<div class="spacer20"></div>

		<div class="col-md-6 col-md-offset-3 text-center">
			<p>
				Choupette ton paiement a été effectué et sera confirmé dans quelques minutes, toute l'équipe espère que tu passeras un agréable moment lorsque tu recevras ta box !
			</p>
			<a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}#contracts" class="spyro-btn spyro-btn-lg spyro-btn-red upper spyro-btn-block">Suivre ma commande</a>

		</div>
	</div>
	
@stop