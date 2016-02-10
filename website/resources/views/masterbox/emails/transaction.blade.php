@extends('masterbox.layouts.email')

@section('title')
Transaction Bancaire
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />
  
  @if ($paid)
    {!! Html::emailLine("Une transaction bancaire d'un montant de $amount vient d'être effectuée sur Bordeaux in Box. Pour plus de détails n'hésite pas à cliquer sur le lien en dessous.") !!}
  @else
    {!! Html::emailLine("Nous avons tenté d'effectuer une transaction de $amount depuis ton compte, mais sans succès. Il se peut que la carte bancaire enregistrée soit invalide, ou que son plafond ait déjà été dépassé. Si le problème persiste, merci d'éditer tes informations bancaires depuis ton compte ;)") !!}
  @endif
  
@stop

@section('call-action')
  {!! Html::emailAction('Voir mes abonnements', action('MasterBox\Customer\ProfileController@getOrders')) !!}
@stop