@extends('masterbox.layouts.master')
@section('content')

@section('gotham')
  {!! Html::gotham([
    'success-message' => session()->get('status')
  ]) !!}
@stop

<div class="+spacer-small"></div>

<div class="container">
  <div class="grid-8 grid-centered">

    {!! Form::open(['action' => 'MasterBox\Connect\PasswordRemindersController@postReset']) !!}
      
      {{ Form::hidden('token', $token) }}

      <div class="panel">
        <div class="panel__heading">
          <h2 class="panel__title">Réinitialisation de mot de passe</h2>
        </div>
        <div class="panel__content --white">

          <div class="+spacer-extra-small"></div>

          <div class="form">
            
            <div class="row">
              {!! Form::text("email", Request::old("email"), ['placeholder' => 'Ton email', 'class' => 'form__input']) !!}
              {!! Html::checkError('email', $errors) !!}
            </div>

            <div class="+spacer-small"></div>

            <div class="row">
                {!! Form::password("password", ['placeholder' => 'Nouveau mot de passe', 'class' => 'form__input']) !!}<br />
                {!! Html::checkError('password', $errors) !!}
            </div>

            <div class="+spacer-small"></div>

            <div class="row">
                {!! Form::password("password_confirmation", ['placeholder' => 'Confirmation du nouveau mot de passe', 'class' => 'form__input']) !!}
                {!! Html::checkError('password_confirmation', $errors) !!}
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
