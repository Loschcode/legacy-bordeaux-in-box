@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_customers_focus')
@stop

@section('content')

<div 
  id="gotham"
  data-form-errors="{{ $errors->has() }}"
  data-success-message="{{ session()->get('message') }}"
>

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section">Client</h1>
    <h2 class="title title__subsection">{{ $customer->getFullName() }}</h2>
  </div>
  <div class="grid-4">
    <div class="+text-right">
      <a href="{{ action('MasterBox\Admin\CustomersController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les clients</a>
    </div>
  </div>
</div>

<div class="divider divider__section"></div>

{!! Html::info("L'utilisateur peut lui-même changer son adresse de facturation, le changement côté administrateur est prévu en cas de bug quelconque.") !!}

{!! Html::info("Un changement de région implique un changement de prix de livraison, le formulaire côté administrateur a été laissé libre (celui côté utilisateur est bloqué) ; il faudra manuellement donner ou récupérer la différence avec l'utilisateur via Stripe.") !!}

{!! Html::info("Si l'utilisateur possède des abonnements, l'adresse de facturation des abonnements éditables sera également modifiée
") !!}

<div class="form">
  {!! Form::open(array('action' => 'MasterBox\Admin\CustomersController@postEdit', 'class' => 'form-inline')) !!}
  
  {!! Form::hidden('customer_id', $customer->id) !!}

  {!! Form::label("email", "Email", ['class' => 'form__label']) !!}
  {!! Form::text("email", ($customer->email) ? $customer->email : Request::old("email"), ['class' => 'form__input']) !!}
  {!! Html::checkError('email', $errors) !!}

  {!! Form::label("phone", "Téléphone", ['class' => 'form__label']) !!}
  {!! Form::text("phone", ($customer->phone) ? $customer->phone : Request::old("phone"), ['class' => 'form__input']) !!}
  {!! Html::checkError('phone', $errors) !!}

  {!! Form::label("password", "Mot de passe", ['class' => 'form__label']) !!}
  {!! Form::text('password', '', ['class' => 'form__input']) !!}
  {!! Html::checkError('password', $errors) !!}

  {!! Form::label("first_name", "Prénom", ['class' => 'form__label']) !!}
  {!! Form::text("first_name", ($customer->first_name) ? $customer->first_name : Request::old("first_name"), ['class' => 'form__input']) !!}
  {!! Html::checkError('first_name', $errors) !!}

  {!! Form::label("last_name", "Nom", ['class' => 'form__label']) !!}
  {!! Form::text("last_name", ($customer->last_name) ? $customer->last_name : Request::old("last_name"), ['class' => 'form__input']) !!}
  {!! Html::checkError('last_name', $errors) !!}

  {!! Form::label("city", "Ville", ['class' => 'form__label']) !!}
  {!! Form::text("city", ($customer->city) ? $customer->city : Request::old("city"), ['class' => 'form__input']) !!}
  {!! Html::checkError('city', $errors) !!}

  {!! Form::label("zip", "Code postal", ['class' => 'form__label']) !!}
  {!! Form::text("zip", ($customer->zip) ? $customer->zip : Request::old("zip"), ['class' => 'form__input']) !!}
  {!! Html::checkError('zip', $errors) !!}

  {!! Form::label("address", "Adresse", ['class' => 'form__label']) !!}
  {!! Form::textarea("address", ($customer->address) ? $customer->address : Request::old("address"), ['class' => 'form__input']) !!}
  {!! Html::checkError('address', $errors) !!}

  {!! Form::submit("Editer l'article", ['class' => 'button button__submit']) !!}
  {!! Form::close() !!}
</div>

@stop

<?php /*
@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-user"></i> Edition Client #{{ $customer->id }}
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
      {!! Form::label("email", "Email", ['class' => 'form__label']) !!}
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
*/ ?>