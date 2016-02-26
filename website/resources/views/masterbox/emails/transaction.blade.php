@extends('masterbox.layouts.email')

@section('title')
Transaction Bancaire
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />
  
  @if ($paid)
    {!! Html::emailLine("Une transaction bancaire d'un montant de <strong>$amount</strong> vient d'être effectuée sur Bordeaux in Box. Pour plus de détails n'hésite pas à cliquer sur le lien en dessous.") !!}
  @else
    {!! Html::emailLine("Nous avons tenté d'effectuer une transaction de <strong>$amount</strong> depuis ton compte, mais sans succès. Il se peut que la carte bancaire enregistrée soit invalide, ou que son plafond ait déjà été dépassé. Si le problème persiste, merci d'éditer tes informations bancaires depuis ton compte ;)") !!}
  @endif
  
@stop

@section('call-action')

  @if (isset($profile) && ($profile !== NULL))
    {!! Html::emailAction('Plus d\'informations', customer_connect_link($customer, action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id]))) !!}
  @else
    {!! Html::emailAction('Plus d\'informations', customer_connect_link($customer, action('MasterBox\Customer\ProfileController@getOrders'))) !!}
  @endif

@stop