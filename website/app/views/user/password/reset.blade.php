@section('content')
<form action="{{ action('RemindersController@postReset') }}" method="POST">
    <input type="hidden" name="token" value="{{ $token }}">
    
    @if ($errors->first('email'))
    {{{ $errors->first('email') }}}
    @endif

    {{ Form::label("email", "Email") }}
    {{ Form::text("email", Input::old("email")) }}<br />


    @if ($errors->first('password'))
    {{{ $errors->first('password') }}}
    @endif

    {{ Form::label("password", "Mot de passe") }}
    {{ Form::password("password") }}<br />


    @if ($errors->first('password_confirmation'))
    {{{ $errors->first('password_confirmation') }}}
    @endif
    {{ Form::label("password_confirmation", "Confirmation de mot de passe") }}
    {{ Form::password("password_confirmation") }}<br />

    {{ Form::submit("Réinitialiser mon mot de passe") }}

</form>
@stop