@section('content')
  
  <div class="spacer50"></div>
  <div class="container">

    <div class="col-md-8 col-md-offset-2">

      {{ Form::open(['class' => 'form-component']) }}

      @if (Session::has('message'))
        <div class="spyro-alert spyro-alert-green">{{ Session::get('message') }}</div>
        <div class="spacer10"></div>
      @endif



      {{ Form::label("service", "Service") }}<br/>
      {{ Form::select('service', HTML::getContactServices(), Input::old('service'), ['class' => 'select'])}}

      @if ($errors->first('service'))
        <div class="error"><i class="fa fa-times"></i> {{ $errors->first('service') }}</div>
      @endif

      <div class="spacer50"></div>



      {{ Form::label("email", "Email")}}
      {{ Form::text("email", (Auth::check()) ? Auth::user()->email : Input::old("email"), ['placeholder' => 'Votre adresse email']) }}


      @if ($errors->first('email'))
        <div class="error"><i class="fa fa-times"></i> {{{ $errors->first('email') }}}</div>
      @endif

      <div class="spacer50"></div>


      {{ Form::label('message', 'Message') }}<br/>
      {{ Form::textarea("message", Input::old("message")) }}

      @if ($errors->first('message'))
        <div class="error"><i class="fa fa-times"></i> {{{ $errors->first('message') }}}</div>
      @endif

      {{ Form::submit("Envoyer mon message") }}

      {{ Form::close() }}

    </div>

    <div class="clearfix"></div>
  </div>

  <div class="spacer200"></div>
  {{ View::make('_includes.footer') }}

@stop

<?php /*
@section('content')

<br />

  {{ Form::open() }}

  @if (Session::has('message'))
  <div>{{ Session::get('message') }}</div>
  @endif

  @if ($errors->first('service'))
  {{{ $errors->first('service') }}}<br />
  @endif

  {{ Form::label("service", "Service") }}
  {{ Form::select('service', HTML::getContactServices(), Input::old('service'))}}<br />

  @if ($errors->first('email'))
  {{{ $errors->first('email') }}}<br />
  @endif
  {{ Form::label("email", "Email")}}
  {{ Form::text("email", (Auth::check()) ? Auth::user()->email : Input::old("email"), ['placeholder' => 'Votre adresse email']) }}
  <br /><br />

  @if ($errors->first('message'))
  {{{ $errors->first('message') }}}<br />
  @endif
  Message<br/>
  {{ Form::textarea("message", Input::old("message")) }}
  <br />
  {{ Form::submit("Envoyer mon message") }}

  {{ Form::close() }}

@stop
*/ ?>