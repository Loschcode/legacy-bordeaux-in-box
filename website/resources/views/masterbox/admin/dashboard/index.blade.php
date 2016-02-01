@extends('masterbox.layouts.admin')

@section('navbar-container')
@stop

@section('content')

  <div class="row">
    <div class="grid-8">
      <h1 class="title title__section">Choix du panel d'administration</h1>
    </div>
  </div>

  <div class="divider divider__section"></div>

    
    <div class="row">
      <div class="grid-6">
        <div class="dashboard dashboard__wrapper --grey">
          <h3 class="dashboard__title">Boxes Principales</h3>
          <p class="dashboard__description">Panel d'administration permettant de voir les ressources de la section boxes principales.</p>
          <p class="dashboard__active">Actuel</p>
        </div>
      </div>
      <div class="grid-6">
        <div class="dashboard dashboard__wrapper">
          <h3 class="dashboard__title">Société</h3>
          <p class="dashboard__description">Panel d'administration permettant de voir les ressources globales de l'entreprise.</p>
          <a class="dashboard__button" href="{{ action('Company\Admin\DashboardController@getIndex') }}">Accéder</a>
        </div>
      </div>
    </div>
@stop