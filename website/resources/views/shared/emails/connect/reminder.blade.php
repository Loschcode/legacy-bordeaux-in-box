@extends('masterbox.layouts.email')

@section('title')
Oublie de mot de passe
@stop

@section('content')
  {!! Html::emailLine("Bonjour,") !!}
  
  {!! Html::emailLine("Pour réintialiser ton mot de passe, il te suffit de cliquer sur le lien suivant :") !!}
  
@stop

@section('call-action')
  {!! Html::emailAction('Réinitialiser mon mot de passe', action('MasterBox\Connect\PasswordRemindersController@getReset', ['token' => $token])) !!}
@stop