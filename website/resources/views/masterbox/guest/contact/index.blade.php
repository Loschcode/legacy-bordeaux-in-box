@extends('masterbox.layouts.master')
@section('content')
  
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

@stop