@extends('layouts.admin')

@section('page')
  <i class="fa fa-suitcase"></i> abonnement #{{$profile->id}} ({{Form::getReadableProfileStatus($profile->status)}})
@stop

@section('buttons')

@if (URL::previous() != Request::root())
  
  <a href="{{URL::previous()}}" class="spyro-btn spyro-btn-success">Retour</a>

@endif

@stop

@section('content')

  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif
  
  @if (Session::has('error'))
    <div class="js-alert-remove spyro-alert spyro-alert-danger">{{ Session::get('error') }}</div>
  @endif

  @if ($errors->delivery->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Impossible d'effectuer la modification de l'adresse de livraison</div>
  @endif

  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#details" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Résumé</a></li>
    <li><a href="#deliveries" role="tab" data-toggle="tab"><i class="fa fa-truck"></i> Livraisons</a></li>
    <li><a href="#paiements" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i> Historique de paiements</a></li>
    <li><a href="#questions" role="tab" data-toggle="tab"><i class="fa fa-question"></i> Questionnaire</a></li>
    <li><a href="#products" role="tab" data-toggle="tab"><i class="fa fa-folder-open"></i> Produits</a></li>
  </ul>
  

  <div class="tab-content">

    <!-- Tab details -->
    <div class="tab-pane active" id="details">
    
    <div class="spacer20"></div>

      @include('admin.profiles.partials.details', array('profile' => $profile))
      
    </div>

      <div class="spacer20"></div>
    </div>

    <!-- Tab deliveries -->
    <div class="tab-pane" id="deliveries">

      <div class="spacer20"></div>

      @include('admin.profiles.partials.deliveries', array('profile' => $profile))
      
    </div>
  
    <div class="tab-pane" id="paiements">

      <div class="spacer20"></div>

      @include('admin.partials.payments_from_profile_table', array('payments' => $profile->payments()->get()))


    </div>

    <div class="tab-pane" id="questions">
     
        @include('admin.profiles.partials.questions', array('profile' => $profile, 'questions' => $questions))
  
    </div>

    <div class="tab-pane" id="products">
     
        @include('admin.profiles.partials.products', array('profile' => $profile))
  
    </div>

  </div>


@stop