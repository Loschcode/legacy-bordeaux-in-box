@extends('masterbox.layouts.email')

@section('title')
Transaction Bancaire
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />

  {!! Html::emailLine("Ton abonnement à Bordeaux in Box vient d'arriver à son terme :(") !!}<br/>

  @if ($last_box_was_sent === true)
    {!! Html::emailLine("Tu vas donc recevoir ta dernière box d'ici les prochains jours.") !!}<br/>
  @endif

  <br />

  {!! Html::emailLine("Nous sommes heureux de t'avoir comblé avec ces petites boxes mensuelles et espérons te revoir très bientôt sur Bordeaux in box !") !!}

  
@stop

@section('call-action')
  {!! Html::emailAction('Voir mes abonnements', action('MasterBox\Customer\ProfileController@getOrders')) !!}
@stop

