@extends('masterbox.layouts.master')

@section('content')

<div class="+spacer-small"></div>

<div class="container">

  {!! Form::open(['action' => 'MasterBox\Connect\CustomerController@postSubscribe']) !!}

  <div class="grid-6 grid-centered grid-11@xs">
    <a class="button button__facebook" href="{{ action('MasterBox\Connect\CustomerController@getLoginWithFacebook') }}"><i class="fa fa-facebook-official"></i> S'inscrire via Facebook</a>
    
    <div class="divider divider__or">OU</div>

    <div class="panel__heading">
      <h2 class="panel__title">Inscription</h2>
    </div>
    <div class="panel__content --white">
      <p>
        Si tu as déjà un compte, tu peux <a href="{{ action('MasterBox\Connect\CustomerController@getLogin') }}">te connecter</a> !
      </p>

      <div class="+spacer-extra-small"></div>

      <div class="form">

        {!! Form::text("first_name", Request::old("first_name"), ['placeholder' => 'Prénom', 'class' => 'form__input']) !!}
        {!! Html::checkError('first_name', $errors) !!}

        <div class="+spacer-extra-small"></div>

        {!! Form::text("last_name", Request::old("last_name"), ['placeholder' => 'Nom de famille', 'class' => 'form__input']) !!}
        {!! Html::checkError('last_name', $errors) !!}
        <div class="+spacer-extra-small"></div>


        {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input']) !!}
        {!! Html::checkError('email', $errors) !!}
        <div class="+spacer-extra-small"></div>

        {!! Form::text("phone", Request::old("phone"), ['placeholder' => 'Téléphone', 'class' => 'form__input']) !!}
        {!! Html::checkError('phone', $errors) !!}
        <div class="+spacer-extra-small"></div>


        {!! Form::password("password", ['placeholder' => 'Mot de passe', 'class' => 'form__input']) !!}
        {!! Html::checkError('password', $errors) !!}
        <div class="+spacer-extra-small"></div>

        {!! Form::password("password_confirmation", ['placeholder' => 'Confirmation du mot de passe', 'class' => 'form__input']) !!}
        {!! Html::checkError('password_confirmation', $errors) !!}


      </div>
    </div>
    <button id="test-subscribe" type="submit" class="button button__submit --panel">M'inscrire</button>
  </div>
  {!! Form::close() !!}

</div>

@stop