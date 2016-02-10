@extends('masterbox.layouts.email')

@section('title')
Bienvenue !
@stop

@section('content')
  {!! Html::emailLine("Bonjour $first_name,") !!}

  <br />
  
  {!! Html::emailLine("Toute l'équipe te souhaite la bienvenue sur <strong>Bordeaux in Box !</strong>") !!}
  
@stop

@section('call-action')
  {!! Html::emailAction('Voir mon profil', action('MasterBox\Customer\ProfileController@getIndex')) !!}
@stop