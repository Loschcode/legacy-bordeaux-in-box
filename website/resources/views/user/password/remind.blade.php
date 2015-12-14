@extends('layouts.master')

@section('content')
		
	<div class="spacer50"></div>
	<div class="col-md-4 col-md-offset-4">

		@if (session()->has('status'))
			<div class="spyro-alert spyro-alert-green">{{ session()->get('status') }}</div>
		@endif
		<form action="{{ action('RemindersController@postRemind') }}" method="POST">
		    <input type="email" name="email" placeholder="Email">

		    @if (session()->has('error'))
		    	<div class="error"><i class="fa fa-times"></i> {{ session()->get('error') }}</div>
		    @endif
		    <input type="submit" value="Réinitialiser mon mot de passe" class="spyro-btn spyro-btn-block spyro-btn-red spyro-btn-lg upper">
		</form>

	</div>
	<div class="clearfix"></div>

	<div class="spacer200"></div>

	{!! View::make('_includes.footer')->with('stick', true) !!}

@stop