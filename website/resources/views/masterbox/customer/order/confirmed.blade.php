@extends('masterbox.layouts.master')

@section('content')

<div class="section section__wrapper">
  <h1 class="section__title --page">Confirmation de paiement</h1>
</div>

<div class="page page__wrapper">
  <div class="container">
    <div class="grid-8 grid-centered grid-11@xs">

      <div class="artwork artwork__container">
        <img class="artwork__picture" src="{{ url('images/artwork.png') }}" />
      </div>
      
      <div class="typography">
				<p class="+text-center">Ton paiement a été effectué et sera confirmé dans quelques minutes, toute l'équipe espère que tu passeras un agréable moment lorsque tu recevras ta box !</p>
      </div>
    </div>
		
		<div class="+spacer-small"></div>

    <div class="grid-8 grid-centered grid-11@xs gr-centered@xs">
    	<div class="row row-align-center@xs">
    		<div class="grid-6 grid-11@xs">
	    		<a href="{{ action('MasterBox\Guest\HomeController@getIndex') }}" class="button button__submit --grey">Revenir à l'accueil</a>
	    	</div>
        <div class="+spacer-small clear show@xs hide"></div>
	    	<div class="grid-6 grid-11@xs">
	    		<a href="{{ action('MasterBox\Customer\ProfileController@getOrders') }}" class="button button__submit">Suivre ma commande</a>
	    	</div>
	    </div>
    </div>

  </div>
</div>
<div class="clear"></div>
<div class="+spacer-large"></div>
@stop
