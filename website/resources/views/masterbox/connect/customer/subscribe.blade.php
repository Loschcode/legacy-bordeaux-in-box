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

@include('masterbox.partials.footer')

@stop