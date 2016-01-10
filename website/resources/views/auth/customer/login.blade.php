@extends('master-box.layouts.master')

@section('content')
  
  <div id="js-page-login"></div>

  <div class="container">


    <div class="spacer50"></div>

    <div class="col-md-4 col-md-offset-4">

      @if (session()->has('message'))
        <div class="spyro-alert spyro-alert-green">{{ session()->get('message') }}</div>
      @endif

      {!! Form::open() !!}



      {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email']) !!}
      @if ($errors->first('email'))
        <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('email') }}}</span>
      @endif

      {!! Form::password("password", ['placeholder' => 'Mot de passe']) !!}
      @if ($errors->first('password'))
        <span class="error"><i class="fa fa-times"></i> {{{ $errors->first('password') }}}</span>
      @endif

      {!! Form::submit("Se connecter", ['class' => 'spyro-btn spyro-btn-red spyro-btn-block spyro-btn-lg upper']) !!}

      <div class="spacer20"></div>

      <div class="text-center">
        <a href="{{ url('user/subscribe') }}" class="spyro-btn spyro-btn-green spyro-btn-block upper">S'inscrire</a>
      </div>
    </div>



    <div class="col-md-8 col-md-offset-2">
      <div class="description">
        Hey choupette ... si tu as oublié ton mot de passe <a href="{{ url('user-password/remind') }}">on peut t'aider</a> et si tu n'as toujours pas de compte tu peux <a href="{{ url('user/subscribe') }}">t'inscrire</a> !
      </div>

    </div>
  </div>

  <div class="spacer150"></div>

  @include('master-box.partials.front.footer')

@stop