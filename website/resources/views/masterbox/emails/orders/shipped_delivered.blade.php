@extends('masterbox.layouts.email')

@section('title')
Box en cours de livraison
@stop

@section('content')

  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />

  @if ($gift)
  	{!! Html::emailLine("Ta box (à offrir) pour la série du <strong>$series_date</strong> est en cours de livraison en ce moment même et ne devrait pas tarder à arriver !") !!}
  @else
  	{!! Html::emailLine("Ta box pour la série du <strong>".series_date($series_date)."</strong> est en cours de livraison en ce moment même et ne devrait pas tarder à arriver !") !!}
  @endif
	
	<br />

	@if (($gift) && ($billing_address))
		{!! Html::emailLine("<strong>Adresse de facturation</strong> : $billing_address") !!}
	@endif

	@if ($destination_address)
		{!! Html::emailLine("<strong>Adresse de livraison</strong> : $destination_address") !!}
	@endif

@stop

@section('call-action')
  {!! Html::emailAction('Plus d\'informations', action('MasterBox\Customer\ProfileController@getIndex')) !!}
@stop
