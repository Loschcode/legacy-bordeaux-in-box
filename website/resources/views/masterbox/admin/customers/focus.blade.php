@extends('masterbox.layouts.admin')

@section('navbar')
  @include('masterbox.admin.partials.navbar_customers_focus')
@stop

@section('content')

<div class="row">
  <div class="grid-8">
    <h1 class="title title__section"><i class="fa fa-user"></i> Client</h1>
    <h2 class="title title__subsection">{{ $customer->getFullName() }} (#{{ $customer->id }})</h2>
  </div>
  <div class="grid-4">
    <div class="+text-right">
      <a href="{{ action('MasterBox\Admin\CustomersController@getIndex') }}" class="button button__section"><i class="fa fa-list"></i> Voir les clients</a>
    </div>
  </div>
</div>

<div class="divider divider__section"></div>

<div class="row">
  <div class="grid-6">
    <div class="panel">
      <div class="panel__wrapper">
        <div class="panel__header">
          <h3 class="panel__title">Résumé</h3>
        </div>
        <div class="panel__content typography">
          <strong>Prénom:</strong> {{$customer->first_name}}<br />
          <strong>Nom:</strong> {{$customer->last_name}}<br />
          <div class="+spacer-extra-small"></div>
          <strong>Email:</strong> {{$customer->email}}<br />
          <strong>Téléphone:</strong> {{ readable_customer_phone($customer->phone) }}<br />
          <div class="+spacer-extra-small"></div>
          <strong>Ville:</strong> {!! Html::getReadableEmpty($customer->city) !!}<br />
          <strong>Code postal:</strong> {!! Html::getReadableEmpty($customer->zip) !!}<br />
          <strong>Adresse:</strong> {!! Html::getReadableEmpty($customer->address) !!}<br />
          <strong>Adresse détail:</strong> {!! Html::getReadableEmpty($customer->address_detail) !!}<br/>
          <div class="+spacer-extra-small"></div>
          <strong>Emails totalement autorisés depuis le:</strong> {!! Html::getReadableEmpty($customer->emails_fully_authorized) !!}<br/>
        </div>
      </div>
    </div>
  </div>
  <div class="grid-6">
    <div class="panel">
      <div class="panel__wrapper">
        <div class="panel__header">
          <h3 class="panel__title">Abonnements</h3>
        </div>
        <div class="panel__content">
          @if ($customer->profiles()->count() > 0)
            <table class="js-datatable-simple">
            <thead>
              <tr>
                <th>Id</th>
                <th>Contrat</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($customer->profiles()->get() as $profile)
                <tr>
                <td>{{ $profile->id }}</td>
                <td>{{ $profile->contract_id }}</td>
                <td>{!! Html::getReadableProfileStatus($profile->status) !!}</td>
                <td><a class="button button__default --green --table" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id])}}"><i class="fa fa-search"></i></a></td>
                </tr>
              @endforeach
            </tbody>
            </table>
          @else
            Aucun abonnement pour le moment
          @endif
        </div>
      </div>
    </div>
  </div>

@stop
<?php /*
@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-user"></i> Client {{$customer->getFullName()}} (#{{$customer->id}})
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>
@endif

@stop

@section("content")

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif
  
  @if (session()->has('error'))
    <div class="js-alert-remove spyro-alert spyro-alert-danger">{{ session()->get('error') }}</div>
  @endif

  @if ($errors->delivery->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Impossible d'effectuer la modification de l'adresse de livraison</div>
  @endif

  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#details" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Résumé</a></li>
    <li><a href="#edit" role="tab" data-toggle="tab"><i class="fa fa-truck"></i> Edition</a></li>

  </ul>
  

  <div class="tab-content">

    <!-- Tab details -->
    <div class="tab-pane active" id="details">
      
      <div class="spacer20"></div>


    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-user"></i> Détails du compte</div>

      <div class="panel-body">

      <strong>Prénom :</strong> {{$customer->first_name}}<br />
      <strong>Nom :</strong> {{$customer->last_name}}<br />
      <br />
      <strong>Email :</strong> {{$customer->email}}<br />
      <strong>Téléphone :</strong> {{$customer->phone}}<br />
      <br />

      <strong>Ville :</strong> {!! Html::getReadableEmpty($customer->city) !!}<br />
      <strong>Code postal :</strong> {!! Html::getReadableEmpty($customer->zip) !!}<br />
      <strong>Adresse :</strong> {!! Html::getReadableEmpty($customer->address) !!}<br />
      <br />

      <strong>Emails totalement autorisés depuis le :</strong> {{ $customer->emails_fully_authorized }}
      <br /><br />

      <strong>Abonnements :</strong><br />
       @if ($customer->profiles()->count() > 0)
          
          @foreach ($customer->profiles()->get() as $profile)

                <a class="spyro-btn btn-blue {{HTML::getColorFromProfileStatus($profile->status)}}" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id])}}">
                

                {!! Html::getReadableProfileStatus($profile->status) !!}

                </a><br/>

          @endforeach

        @else
              Pas d'abonnement pour le moment
        @endif

      </div>
    </div>

  </div>

    <!-- Tab details -->
    <div class="tab-pane" id="edit">
      
      <div class="spacer20"></div>


      {!! Html::info("Si l'utilisateur possède des abonnements, l'adresse de facturation des abonnements éditables sera également modifiée") !!}

      {!! Form::open(array('action' => 'MasterBox\Admin\CustomersController@postEdit')) !!}

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

    </div>

@stop
*/ ?>