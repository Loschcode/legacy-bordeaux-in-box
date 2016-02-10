@extends('masterbox.layouts.email')

@section('title')
Changement de ton point relais
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />
  
  {!! Html::emailLine("Les abonnements reliés au point relais <strong>$old_spot_name</strong> viennent d'être transférés vers le point relais <strong>$new_spot_name</strong>. Ton abonnement en fait partie.") !!}

  <br />

  {!! Html::emailLine("<strong>Nouveau point relais :</strong> $new_spot_name_and_infos") !!}

  <br />
  
  {!! Html::emailLine("<strong>Horaires :</strong>") !!}
  {!! Html::emailLine($new_spot_schedule) !!}

  <br />

  {!! Html::emailLine("Si tu souhaites sélectionner un autre point relais, n'hésite pas à le modifier directement depuis ton compte") !!}

  <br />
  <br />

@stop

@section('call-action')
  {!! Html::emailAction('Accèder à mon compte', action('MasterBox\Customer\ProfileController@getIndex')) !!}
@stop
