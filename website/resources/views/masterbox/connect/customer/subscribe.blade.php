@extends('masterbox.layouts.master')
@section('content')

<div 
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
></div>

<div class="+spacer-small"></div>

<div class="container">
  <div class="grid-8 grid-centered">
    <div class="panel">
      <div class="panel__heading">
        <h2 class="panel__title">Inscription</h2>
      </div>
      <div class="panel__content">
        <p>
          C’est le moment de créer ton compte, le formulaire juste en dessous va te poser quelques questions histoire de savoir qui tu es ...<br/>
          Si tu as déjà un compte, tu peux <a href="{{ action('MasterBox\Connect\CustomerController@getLogin') }}">te connecter</a> !
        </p>
        
        {!! Form::open(['action' => 'MasterBox\Connect\CustomerController@postSubscribe']) !!}

        <div class="+spacer-extra-small"></div>
          
          <div class="form">
            
            <h4 class="form__title">Informations personnelles</h4>
            <div class="row">
              <div class="grid-6 no-gutter">
                {!! Form::text("first_name", Request::old("first_name"), ['placeholder' => 'Prénom', 'class' => 'form__input --grouped-left']) !!}
                {!! Html::checkError('first_name', $errors) !!}
              </div>
              <div class="grid-6 no-gutter">
                {!! Form::text("last_name", Request::old("last_name"), ['placeholder' => 'Nom de famille', 'class' => 'form__input --grouped-right']) !!}
                {!! Html::checkError('last_name', $errors) !!}
              </div>
            </div>
            
            <div class="+spacer-small"></div>
            
            <h4 class="form__title">Contact</h4>

            <div class="row">
              <div class="grid-6 no-gutter">
                {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input --grouped-left']) !!}
                {!! Html::checkError('email', $errors) !!}
              </div>
              <div class="grid-6 no-gutter">
                {!! Form::text("phone", Request::old("phone"), ['placeholder' => 'Téléphone', 'class' => 'form__input --grouped-right']) !!}
                {!! Html::checkError('phone', $errors) !!}
              </div>
            </div>
            
            <div class="+spacer-small"></div>

            <h4 class="form__title">Sécurité</h4>

            <div class="row">
              <div class="grid-6 no-gutter">
                {!! Form::password("password", ['placeholder' => 'Mot de passe', 'class' => 'form__input --grouped-left']) !!}
                {!! Html::checkError('password', $errors) !!}
              </div>
              <div class="grid-6 no-gutter">
                {!! Form::password("password_confirmation", ['placeholder' => 'Confirmation du mot de passe', 'class' => 'form__input --grouped-right']) !!}
                {!! Html::checkError('password_confirmation', $errors) !!}
              </div>
            </div>



          </div>
      </div>
      <div class="panel__footer">
        <button type="submit" class="button button__submit --panel">M'inscrire</button>
      </div>
    </div>
    {!! Form::close() !!}

  </div>

</div>

<?php /*
  <div class="col-md-6 col-md-offset-3">

    <div class="description text-center">
      C’est le moment de créer ton compte, le formulaire juste en dessous va te poser quelques questions histoire de savoir qui tu es ... Ne prends pas peur tu connais déjà les réponses !<br/>
      Si tu as déjà un compte, tu peux <a href="{{ action('MasterBox\Connect\CustomerController@getLogin') }}">te connecter</a> !
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
    @include('masterbox.partials.footer')
  </div>
*/ ?>
@stop