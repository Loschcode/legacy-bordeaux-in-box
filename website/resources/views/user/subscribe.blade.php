@extends('layouts.master')
@section('content')

  <div class="col-md-6 col-md-offset-3">

    <div class="description text-center">
      C’est le moment de créer ton compte, le formulaire juste en dessous va te poser quelques questions histoire de savoir qui tu es ... Ne prends pas peur tu connais déjà les réponses !<br/>
      Si tu as déjà un compte, tu peux <a href="{{ url('user/login') }}">te connecter</a> !
    </div>

    <div class="spacer20"></div>


    {!! Form::open() !!}

    {!! Form::text("first_name", Request::old("first_name"), ['placeholder' => 'Prénom']) !!}

    @if ($errors->first('first_name'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('first_name') }}}</span>
    @endif


    {!! Form::text("last_name", Request::old("last_name"), ['placeholder' => 'Nom de famille']) !!}
    @if ($errors->first('last_name'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('last_name') }}}</span>
    @endif

    {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email']) !!}
    @if ($errors->first('email'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('email') }}}</span>
    @endif


    {!! Form::text("phone", Request::old("phone"), ['placeholder' => 'Téléphone']) !!}
    @if ($errors->first('phone'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('phone') }}}</span>
    @endif


    {!! Form::password("password", ['placeholder' => 'Mot de passe']) !!}
    @if ($errors->first('password'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('password') }}}</span>
    @endif


    {!! Form::password("password_confirmation", ['placeholder' => 'Confirmation du mot de passe']) !!}
    @if ($errors->first('password_confirmation'))
      <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('password_confirmation') }}}</span>
    @endif

    {!! Form::submit("S'inscrire", ['class' => 'upper spyro-btn spyro-btn-red spyro-btn-lg spyro-btn-block spyro-btn-opacity-inverse']) !!}
    {!! Form::close() !!}

  </div>



  <div class="clearfix"></div>

  <div class="spacer250"></div>
  <div class="footer-container">
    @include('_includes.footer')
  </div>
@stop