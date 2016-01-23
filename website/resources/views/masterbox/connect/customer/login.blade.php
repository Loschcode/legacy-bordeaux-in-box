@extends('masterbox.layouts.master')
@section('content')

<div 
id="gotham"
data-form-errors="{{ $errors->has() }}"
></div>

<div class="+spacer-small"></div>

<div class="container">
<div class="grid-6 grid-centered">
    <div class="panel">
      <div class="panel__heading">
        <h2 class="panel__title">Connexion</h2>
      </div>
      <div class="panel__content --white">

        {!! Form::open(['action' => 'MasterBox\Connect\CustomerController@postLogin']) !!}

        <div class="+spacer-extra-small"></div>

        <div class="form">

          {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input']) !!}
          {!! Html::checkError('email', $errors) !!}

          <div class="+spacer-extra-small"></div>

          {!! Form::password("password", ['placeholder' => 'Mot de passe', 'class' => 'form__input']) !!}
          {!! Html::checkError('password', $errors) !!}


          <div class="+spacer-small"></div>

          <p>
            Si tu as oublié ton mot de passe <a href="{{ action('MasterBox\Connect\PasswordRemindersController@getRemind') }}">on peut t'aider</a> et si tu n'as toujours pas de compte tu peux <a href="{{ action('MasterBox\Connect\CustomerController@getSubscribe') }}">t'inscrire</a> !
          </p>

        </div>
      </div>
      <button type="submit" class="button button__submit --panel">Me connecter</button>
    </div>
    {!! Form::close() !!}

  </div>

</div>

<div class="+spacer"></div>

@stop
<?php /*
@extends('masterbox.layouts.master')

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
        <a href="{{ action('MasterBox\Connect\CustomerController@getSubscribe') }}" class="spyro-btn spyro-btn-green spyro-btn-block upper">S'inscrire</a>
      </div>
    </div>



    <div class="col-md-8 col-md-offset-2">
      <div class="description">
        Hey choupette ... si tu as oublié ton mot de passe <a href="{{ action('MasterBox\Connect\PasswordRemindersController@getRemind') }}">on peut t'aider</a> et si tu n'as toujours pas de compte tu peux <a href="{{ action('MasterBox\Connect\CustomerController@getSubscribe') }}">t'inscrire</a> !
      </div>

    </div>
  </div>

  <div class="spacer150"></div>

  @include('masterbox.partials.front.footer')

@stop
*/ ?>