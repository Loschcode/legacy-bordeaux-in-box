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

    {!! Form::open(['action' => 'MasterBox\Connect\PasswordRemindersController@postRemind']) !!}

    <div class="panel">
      <div class="panel__heading">
        <h2 class="panel__title">Mot de passe oublié</h2>
      </div>
      <div class="panel__content --white">

        <div class="+spacer-extra-small"></div>

        <div class="form">

          {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input']) !!}
          {!! Html::checkError('email', $errors) !!}

          <div class="+spacer-small"></div>

        </div>
      </div>
      <div class="panel__footer">
        <button type="submit" class="button button__submit --panel">Récupérer mon mot de passe</button>
      </div>
    </div>
    {!! Form::close() !!}

  </div>

</div>

@stop

