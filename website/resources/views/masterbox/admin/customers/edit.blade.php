@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-user"></i> Edition Utilisateur #{{ $customer->id }}
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  {!! Html::info('Si l\'utilisateur possède des abonnements, l\'adresse de facturation des abonnements éditables sera également modifiée') !!}

  {!! Form::open(array('action' => 'MasterBox\Admin\CustomersController@postEdit', 'class' => 'form-inline')) !!}

  {!! Form::hidden('customer_id', $customer->id) !!}

  <div class="w80">
    <!-- Email -->
    <div class="form-group @if ($errors->first('email')) has-error has-feedback @endif">
      {!! Form::label("email", "Email", ['class' => 'control-label']) !!}
      {!! Form::text("email", ($customer->email) ? $customer->email : Request::old("email"), ['class' => 'form-control']) !!}

      @if ($errors->first('email'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('email') }}</span>
      @endif
    </div>


    <!-- Phone -->
    <div class="form-group @if ($errors->first('phone')) has-error has-feedback @endif">
      {!! Form::label("phone", "Téléphone", ['class' => 'control-label']) !!}
      {!! Form::text("phone", ($customer->phone) ? $customer->phone : Request::old("phone"), ['class' => 'form-control']) !!}

      @if ($errors->first('phone'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('phone') }}</span>
      @endif
    </div>


    <!-- Password -->
    <div class="form-group @if ($errors->first('password')) has-error has-feedback @endif">
      {!! Form::label("password", "Mot de passe", ['class' => 'control-label']) !!}
      {!! Form::text('password', '', ['class' => 'form-control']) !!}

      @if ($errors->first('password'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('password') }}</span>
      @else
        <span class="help-block">Laissez vide si inchangé</span>
      @endif
    </div>

    <!-- Firstname -->
    <div class="form-group @if ($errors->first('first_name')) has-error has-feedback @endif">
      {!! Form::label("first_name", "Prénom", ['class' => 'control-label']) !!}
    {!! Form::text("first_name", ($customer->first_name) ? $customer->first_name : Request::old("first_name"), ['class' => 'form-control']) !!}

      @if ($errors->first('first_name'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('first_name') }}</span>
      @endif
    </div>

    <!-- Name -->
    <div class="form-group @if ($errors->first('last_name')) has-error has-feedback @endif">
      {!! Form::label("last_name", "Nom", ['class' => 'control-label']) !!}
      {!! Form::text("last_name", ($customer->last_name) ? $customer->last_name : Request::old("last_name"), ['class' => 'form-control']) !!}

      @if ($errors->first('last_name'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('last_name') }}</span>
      @endif
    </div>

    <!-- City -->
    <div class="form-group @if ($errors->first('city')) has-error has-feedback @endif">
      {!! Form::label("city", "Ville", ['class' => 'control-label']) !!}
      {!! Form::text("city", ($customer->city) ? $customer->city : Request::old("city"), ['class' => 'form-control']) !!}

      @if ($errors->first('city'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('city') }}</span>
      @endif
    </div>

    <!-- Zip -->
    <div class="form-group @if ($errors->first('zip')) has-error has-feedback @endif">
      {!! Form::label("zip", "Code postal", ['class' => 'control-label']) !!}
      {!! Form::text("zip", ($customer->zip) ? $customer->zip : Request::old("zip"), ['class' => 'form-control']) !!}

      @if ($errors->first('zip'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('zip') }}</span>
      @endif
    </div>

    <!-- Address -->
    <div class="form-group @if ($errors->first('address')) has-error has-feedback @endif">
      {!! Form::label("address", "Adresse", ['class' => 'control-label']) !!}
      {!! Form::textarea("address", ($customer->address) ? $customer->address : Request::old("address"), ['class' => 'form-control']) !!}

      @if ($errors->first('address'))
        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        <span class="help-block">{{ $errors->first('address') }}</span>
      @endif
    </div>
  </div>


  {!! Form::submit("Sauvegarder les modifications", ['class' => 'spyro-btn spyro-btn-lg spyro-btn-success']) !!}

  {!! Form::close() !!}

@stop