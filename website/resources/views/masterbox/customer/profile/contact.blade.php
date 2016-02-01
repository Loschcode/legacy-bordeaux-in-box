@extends('masterbox.layouts.master')

@section('content')

  <div class="container">
    <div class="row">
      <div class="grid-2">
        @include('masterbox.partials.sidebar_profile')
      </div>
      <div class="grid-9">
        <div class="profile profile__wrapper">
          <div class="profile__section">
            <h3 class="profile__title">Contact</h3>
            <p>Un problème, une question, n'hésite pas à nous contacter.</p>
            {!! Form::open(['action' => 'MasterBox\Guest\ContactController@getIndex']) !!}
              {{ Form::hidden('email', $customer->email) }}

              {!! Form::label('service', 'Service', ['class' => 'form__label']) !!}
              {!! Form::select('service', Html::getContactServices(), (request()->input('service')) ? request()->input('service') : old('service'), ['class' => 'js-chosen']) !!}
               {!! Html::checkError('service', $errors) !!}
           

              {!! Form::label('message', 'Message', ['class' => 'form__label']) !!}
              {!! Form::textarea("message", Request::old("message"), ['class' => 'form__input', 'placeholder' => 'Votre message']) !!}
              {!! Html::checkError('message', $errors) !!}
              
              <button type="submit" class="button button__submit">Envoyer mon message à l'équipe</button>

            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>

@stop
<?php /*
    {!! Form::label('service', 'Service', ['class' => 'form__label']) !!}
      {!! Form::select('service', Html::getContactServices(), (request()->input('service')) ? request()->input('service') : old('service'), ['class' => 'js-chosen']) !!}
      {!! Html::checkError('service', $errors) !!}
      <div class="+spacer-small"></div>

      {!! Form::label('email', 'Email', ['class' => 'form__label']) !!}

      @if (Auth::check())
      {!! Form::text("email", Auth::user()->email, ['class' => 'form__input']) !!}
      @else
      {!! Form::text("email", Request::old("email"), ['class' => 'form__input']) !!}
      @endif

      {!! Html::checkError('email', $errors) !!}

      <div class="+spacer-small"></div>

      {!! Form::label('message', 'Message', ['class' => 'form__label']) !!}
      {!! Form::textarea("message", Request::old("message"), ['class' => 'form__input', 'placeholder' => 'Votre message']) !!}
      {!! Html::checkError('message', $errors) !!}

      <div class="+spacer-small"></div>

    </div>

    <button type="submit" class="button button__submit">Envoyer mon message à l'équipe</button>
*/?>