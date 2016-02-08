@extends('masterbox.layouts.master')

@section('content')

<div class="container">
  <div class="grid-8 grid-centered grid-11@xs">


    {!! Form::open() !!}



    <div class="+spacer-extra-small"></div>

    <div class="form">
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
  </div>
  {!! Form::close() !!}

</div>


@stop
