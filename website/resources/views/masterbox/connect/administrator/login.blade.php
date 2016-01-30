@extends('masterbox.layouts.admin')

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

    </div>

  </div>

  <div class="spacer150"></div>

@stop

?>