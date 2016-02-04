@extends('masterbox.layouts.email')

@section('title')
Box en cours de livraison
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  @if ($gift)
  	{!! Html::emailLine("Ta box (à offrir) pour la série du <strong>$series_date</strong> est en cours de livraison en ce moment même à notre point relais partenaire") !!}
  @else
  	{!! Html::emailLine("Ta box pour la série du <strong>$series_date</strong> est en cours de livraison en ce moment même à notre point relais partenaire") !!}
  @endif

  {!! Html::emailLine("Rendez vous dès que possible à <strong>$spot_name_and_infos</strong>") !!}
	
	{!! Html::emailLine('<strong>Horaires:</strong>') !!}
	{!! Html::emailLine($spot_schedule) !!}

 @stop

 @section('call-action')
  {!! Html::emailAction('Plus d\'informations', action('MasterBox\Customer\ProfileController@getIndex')) !!}
 @stop

