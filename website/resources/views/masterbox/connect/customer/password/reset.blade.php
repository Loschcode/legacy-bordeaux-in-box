@extends('masterbox.layouts.master')

@section('content')
  
  {!! Form::open(['action' => 'MasterBox\Connect\CustomerRemindersController@postReset']) !!}
      
      <input type="hidden" name="token" value="{{ $token }}">
      
      @if ($errors->first('email'))
          {{{ $errors->first('email') }}}
      @endif

      {!! Form::label("email", "Email") !!}
      {!! Form::text("email", Request::old("email")) !!}<br />


      @if ($errors->first('password'))
          {{{ $errors->first('password') }}}
      @endif

      {!! Form::label("password", "Mot de passe") !!}
      {!! Form::password("password") !!}<br />


      @if ($errors->first('password_confirmation'))
          {{{ $errors->first('password_confirmation') }}}
      @endif
      {!! Form::label("password_confirmation", "Confirmation de mot de passe") !!}
      {!! Form::password("password_confirmation") !!}<br />

      {!! Form::submit("Réinitialiser mon mot de passe") !!}

  {!! Form::close() !!}
@stop