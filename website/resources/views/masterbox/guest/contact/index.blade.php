@extends('masterbox.layouts.master')
@section('content')

<div 
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
  data-success-message="{{ session()->get('message') }}"
></div>

<div class="+spacer-small"></div>

<div class="container">
  <div class="grid-8 grid-centered">

    {!! Form::open() !!}
      
      <div class="panel">
        <div class="panel__heading">
          <h2 class="panel__title">Nous Contacter</h2>
        </div>
        <div class="panel__content">

          <div class="+spacer-extra-small"></div>

          <div class="form">
          
            <div class="row">
              {!! Form::select('service', Html::getContactServices(), Request::old('service'), ['class' => 'js-chosen']) !!}
              {!! Html::checkError('service', $errors) !!}
            </div>
            
            <div class="+spacer-small"></div>

            <div class="row">
              {!! Form::text("email", Request::old("email"), ['placeholder' => 'Email', 'class' => 'form__input']) !!}
              {!! Html::checkError('email', $errors) !!}
            </div>
            
            <div class="+spacer-small"></div>

            <div class="row">
              {!! Form::textarea("message", Request::old("message"), ['class' => 'form__input', 'placeholder' => 'Votre message']) !!}
              {!! Html::checkError('message', $errors) !!}
            </div>

            <div class="+spacer-small"></div>

          </div>
        </div>
        <div class="panel__footer">
          <button type="submit" class="button button__submit --panel">Envoyer mon message à l'équipe</button>
        </div>
      </div>
    {!! Form::close() !!}

  </div>

</div>

@include('masterbox.partials.footer')

@stop

<?php /*
  <div class="spacer50"></div>
  <div class="container">

    <div class="col-md-8 col-md-offset-2">

      {!! Form::open(['class' => 'form-component']) !!}

      @if (session()->has('message'))
        <div class="spyro-alert spyro-alert-green">{{ session()->get('message') }}</div>
        <div class="spacer10"></div>
      @endif



      {!! Form::label("service", "Service") !!}<br/>
      {!! Form::select('service', Html::getContactServices(), Request::old('service'), ['class' => 'select']) !!}

      @if ($errors->first('service'))
        <div class="error"><i class="fa fa-times"></i> {{ $errors->first('service') }}</div>
      @endif

      <div class="spacer50"></div>



      {!! Form::label("email", "Email") !!}
      {!! Form::text("email", (Auth::check()) ? Auth::guard('customer')->user()->email : Request::old("email"), ['placeholder' => 'Votre adresse email']) !!}


      @if ($errors->first('email'))
        <div class="error"><i class="fa fa-times"></i> {{{ $errors->first('email') }}}</div>
      @endif

      <div class="spacer50"></div>


      {!! Form::label('message', 'Message') !!}<br/>
      {!! Form::textarea("message", Request::old("message")) !!}

      @if ($errors->first('message'))
        <div class="error"><i class="fa fa-times"></i> {{{ $errors->first('message') }}}</div>
      @endif

      {!! Form::submit("Envoyer mon message") !!}

      {!! Form::close() !!}

    </div>

    <div class="clearfix"></div>
  </div>

  <div class="spacer200"></div>
  {!! View::make('masterbox.partials.front.footer') !!}
*/ ?>
