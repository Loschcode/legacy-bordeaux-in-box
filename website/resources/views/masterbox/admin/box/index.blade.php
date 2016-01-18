@extends('masterbox.layouts.admin')

@section('page')
	<i class="fa fa-gift"></i> Box
@stop

@section('content')
	

	@if (session()->has('message'))
	  <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
	@endif

	@if ($errors->has())
	  <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	@endif

  <a href="{{action('MasterBox\Admin\BoxQuestionsController@getIndex')}}">Editer les questions</a>

@stop