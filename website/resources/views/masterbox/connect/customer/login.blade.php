@extends('masterbox.layouts.master')

@section('content')

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
    
    <div class="divider divider__or">OU</div>

    <a class="button button__facebook" href="{{ action('MasterBox\Connect\CustomerController@getLoginWithFacebook') }}"><i class="fa fa-facebook-official"></i> Se connecter via Facebook</a>
        
        <!--
        {{ HTML::linkAction("MasterBox\Connect\CustomerController@getLoginWithGoogle", "Se connecter via Google", null, ['class' => 'btn btn-bg btn-google']) }}

        {{ HTML::linkAction("MasterBox\Connect\CustomerController@getLoginWithTwitter", "Se connecter via Twitter", null, ['class' => 'btn btn-bg btn-twitter']) }}
        -->

  </div>

</div>

<div class="+spacer"></div>

@stop