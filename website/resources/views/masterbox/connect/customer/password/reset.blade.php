@extends('masterbox.layouts.master')
@section('content')

<div 
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
  data-success-message="@if (session()->has('status')) {{ session()->get('status') }} @endif"
></div>

<div class="+spacer-small"></div>

<div class="container">
  <div class="grid-8 grid-centered">

    {!! Form::open(['action' => 'MasterBox\Connect\PasswordRemindersController@postReset']) !!}
      
      {{ Form::hidden('token', $token) }}

      <div class="panel">
        <div class="panel__heading">
          <h2 class="panel__title">Réinitialisation mot de passe</h2>
        </div>
        <div class="panel__content">

          <div class="+spacer-extra-small"></div>

          <div class="form">
            
            <h4 class="form__title">Votre Email</h4>
            <div class="row">
              {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input']) !!}
              {!! Html::checkError('email', $errors) !!}
            </div>

            <div class="+spacer-small"></div>
            
            <h4 class="form__title">Nouveau mot de passe</h4>
            <div class="row">
              <div class="grid-6 no-gutter">
                {!! Form::password("password", ['placeholder' => 'Mot de passe', 'class' => 'form__input --grouped-left']) !!}<br />
                {!! Html::checkError('password', $errors) !!}
              </div>
              <div class="grid-6 no-gutter">
                {!! Form::password("password_confirmation", ['placeholder' => 'Confirmation mot de passe', 'class' => 'form__input --grouped-right']) !!}
                {!! Html::checkError('password_confirmation', $errors) !!}
              </div>
            </div>

            <div class="+spacer-small"></div>

          </div>
        </div>
        <div class="panel__footer">
          <button type="submit" class="button button__submit --panel">Réinitialiser mon mot de passe</button>
        </div>
      </div>
    {!! Form::close() !!}

  </div>

</div>

@include('masterbox.partials.footer')

@stop


<?php /*
@extends('masterbox.layouts.master')

@section('content')
  
  {!! Form::open(['action' => 'MasterBox\Connect\PasswordRemindersController@postReset']) !!}
      
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
*/ ?>