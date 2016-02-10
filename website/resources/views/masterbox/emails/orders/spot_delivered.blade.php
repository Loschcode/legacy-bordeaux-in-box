@extends('masterbox.layouts.email')

@section('title')
Box en cours de livraison
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />
  
  @if ($gift)
  	{!! Html::emailLine("Ta box (à offrir) pour la série du <strong>$series_date</strong> vient d'être livrée à notre point relais partenaire") !!}
  @else
  	{!! Html::emailLine("Ta box pour la série du <strong>".series_date($series_date)."</strong> vient d'être livrée à notre point relais partenaire") !!}
  @endif

  <br />

  {!! Html::emailLine("Rendez vous dès que possible à <strong>$spot_name_and_infos</strong>") !!}

  <br />
	
	{!! Html::emailLine('<strong>Horaires :</strong>') !!}

  <br />
  
	{!! Html::emailLine($spot_schedule) !!}

 @stop

 @section('call-action')
  {!! Html::emailAction('Plus d\'informations', action('MasterBox\Customer\ProfileController@getIndex')) !!}
 @stop

