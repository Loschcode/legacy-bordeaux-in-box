@extends('masterbox.layouts.admin_login')

@section('gotham')
  {!! Html::gotham([
    'form-errors-text' => 'Les identifiants sont invalides'
  ]) !!}
@stop

@section('content')
  
  <div class="grid-3 grid-centered">
    <div class="connect connect__wrapper">
      <div class="connect__thumbnail-wrapper">
        <img class="connect__thumbnail" src="{{ url('images/macaron-masterbox.png') }}" />
      </div>
      {!! Form::open() !!}
      {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input', 'autofocus']) !!}
      <div class="+spacer-extra-small"></div> 
      {!! Form::password("password", ['placeholder' => 'Mot de passe', 'class' => 'form__input']) !!}
      <div class="+spacer-extra-small"></div>
      {!! Form::submit("Se connecter", ['class' => 'button button__connect']) !!}
      {!! Form::close() !!}
    </div>
  </div>


@stop
