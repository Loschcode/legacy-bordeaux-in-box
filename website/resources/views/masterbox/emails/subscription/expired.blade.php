@extends('masterbox.layouts.email')

@section('title')
Transaction Bancaire
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />

  {!! Html::emailLine("Ton abonnement à Bordeaux in Box vient d'arriver à son terme :(") !!}

  <br/>

  @if ($last_box_was_sent === true)
  
    {!! Html::emailLine("Tu vas donc recevoir ta dernière box d'ici les prochains jours.") !!}
    <br/>

  @endif

  {!! Html::emailLine("<strong>Nous sommes heureux de t'avoir comblé avec ces petites boxes mensuelles et espérons te revoir très bientôt sur Bordeaux in box !</strong>") !!}

  
@stop

@section('call-action')

  @if (!isset($customer))

    {!! Html::emailAction('Plus d\'informations', action('MasterBox\Customer\ProfileController@getOrders')) !!}

  @else

    @if (isset($profile) && ($profile !== NULL))
      {!! Html::emailAction('Plus d\'informations', customer_connect_link($customer, action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id]))) !!}
    @else
      {!! Html::emailAction('Plus d\'informations', customer_connect_link($customer, action('MasterBox\Customer\ProfileController@getOrders'))) !!}
    @endif

  @endif

@stop