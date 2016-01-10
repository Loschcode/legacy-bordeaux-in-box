@extends('masterbox.layouts.master')

@section('content')
		
	<div class="spacer50"></div>
	<div class="col-md-4 col-md-offset-4">
    
    @if (count($errors) > 0)
        <div class="remove spyro-alert spyro-alert-danger">
          <strong>Whoops !</strong> Il y'a des erreurs dans le formulaire <br/>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

		@if (session()->has('status'))
			<div class="spyro-alert spyro-alert-green">{{ session()->get('status') }}</div>
		@endif

    {!! Form::open(['action' => 'RemindersController@postRemind']) !!}
		  <input type="text" name="email" placeholder="Email">

		  @if (session()->has('error'))
		    <div class="error"><i class="fa fa-times"></i> {{ session()->get('error') }}</div>
		  @endif
      
		  <input type="submit" value="Réinitialiser mon mot de passe" class="spyro-btn spyro-btn-block spyro-btn-red spyro-btn-lg upper">
    
    {!! Form::close() !!}

	</div>
	<div class="clearfix"></div>

	<div class="spacer200"></div>

	@include('masterbox.partials.front.footer', ['stick' => true])

@stop