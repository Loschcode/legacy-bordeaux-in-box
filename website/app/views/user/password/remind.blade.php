@section('content')
		
	<div class="spacer50"></div>
	<div class="col-md-4 col-md-offset-4">

		@if (Session::has('status'))
			<div class="spyro-alert spyro-alert-green">{{ Session::get('status') }}</div>
		@endif
		<form action="{{ action('RemindersController@postRemind') }}" method="POST">
		    <input type="email" name="email" placeholder="Email">

		    @if (Session::has('error'))
		    	<div class="error"><i class="fa fa-times"></i> {{ Session::get('error') }}</div>
		    @endif
		    <input type="submit" value="Réinitialiser mon mot de passe" class="spyro-btn spyro-btn-block spyro-btn-red spyro-btn-lg upper">
		</form>

	</div>
	<div class="clearfix"></div>

	<div class="spacer200"></div>

	{{ View::make('_includes.footer')->with('stick', true) }}

@stop
<!--
@section('content')

	<form action="{{ action('RemindersController@postRemind') }}" method="POST">
	    <input type="email" name="email">
	    <input type="submit" value="Réinitialiser mon mot de passe">
	</form>

@stop
-->