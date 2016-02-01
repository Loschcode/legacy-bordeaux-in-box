@extends('masterbox.layouts.admin')

@section('content')

<div
id="gotham"
data-controller="masterbox.admin.profiles.index"
data-success-message="{{ session()->get('message') }}"
></div>

<div class="row">
	<div class="grid-8">
		<h1 class="title title__section">Abonnements</h1>
	</div>
</div>

<div class="divider divider__section"></div>


{{-- Table rendered by ajax --}}
<table 
data-request="{{ action('MasterBox\Service\ApiController@getProfiles') }}"
data-focus-profile="{{ action('MasterBox\Admin\ProfilesController@getFocus') }}"
data-delete-profile="{{ action('MasterBox\Admin\ProfilesController@getDelete') }}"
data-focus-customer="{{ action('MasterBox\Admin\CustomersController@getFocus') }}"
>
<thead>
	<tr>
		<th></th>
		<th>Id</th>
		<th>Contrat</th>
		<th>Client</th>
		<th>Livraisons Restantes</th>
		<th>Paiements Effectués</th>
		<th>Statut</th>
		<th>Action</th>
	</tr>
</thead>
</table>

@stop
<?php /*
@extends('masterbox.layouts.admin')

@section('page')
	<i class="fa fa-suitcase"></i> Abonnements
@stop

@section('buttons')

  <a href="/admin/profiles/reset-profiles-priorities" class="spyro-btn spyro-btn-success">Réinitialiser les priorités des abonnements</a>

@stop

@section('content')

	<div id="js-page-profile"></div>

	  @if (session()->has('message'))
	    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
	  @endif

	  @if ($errors->has())
	    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	  @endif


	<div id="profiles-json" class="hidden">{{ $profiles->toJson() }}</div>

	<!-- Template modal -->
	<div class="modal fade" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title" id="myModalLabel"><span id="profile-title"></span></h4>
	      </div>
	      <div class="modal-body">

	      	<h3>Identifiant Stripe</h3>
	      	<p id="profile-stripe"></p>

	      	<h3>Numéro de contrat</h3>
	      	<p id="profile-contract"></p>

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
					<a id="profile-edit" class="spyro-btn spyro-btn-warning"><i class="fa fa-pencil"></i> Editer</a>
					<a id="profile-archive" class="spyro-btn spyro-btn-inverse"><i class="fa fa-archive"></i> Archiver</a>
	      </div>
	    </div>
	  </div>
	</div>

  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#details" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Résumé ({{$profiles->count()}})</a></li>

    <li><a href="#subscribed" role="tab" data-toggle="tab"><i class="fa fa-thumbs-up"></i> Abonnés ({{App\Models\CustomerProfile::getSubscribedProfiles()->count()}})</a></li>

    <li><a href="#in-progress" role="tab" data-toggle="tab"><i class="fa fa-question"></i> En création ({{App\Models\CustomerProfile::getInProgressProfiles()->count()}})</a></li>

    <li><a href="#not-subscribed" role="tab" data-toggle="tab"><i class="fa fa-minus-circle"></i> Non abonnés ({{App\Models\CustomerProfile::getNotSubscribedProfiles()->count()}})</a></li>

    <li><a href="#expired" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i> Expirés ({{App\Models\CustomerProfile::getExpiredProfiles()->count()}})</a></li>


  </ul>

  	<div class="tab-content">

	<!-- Tab List -->
	<div class="tab-pane active" id="details">
		
		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-area-chart"></i> Changement des différents statuts des abonnement au cours du temps</div>
			<div class="panel-body">
		
			<!-- Single line -->
			@include('masterbox.admin.partials.graphs.line_chart', ['config' => $config_graph_customer_profile_status_progress])

			</div>
		</div>

	{!! Html::info('Ci dessous sont listées les différents abonnements des utilisateurs du site. Les utilisateurs peuvent avoir plusieurs abonnements') !!}

	<div class="filters-profiles">
		<a data-filter="Abonné" data-toggle="tooltip" title="Filtrer par abonnés" class="spyro-btn spyro-btn-primary spyro-btn-sm no-loader"> <i class="fa fa-check hidden"></i> {{App\Models\CustomerProfile::getSubscribedProfiles()->count()}} abonnés</a>
		<a data-filter="En création" data-toggle="tooltip" title="Filtrer ceux en cours de création" class="spyro-btn spyro-btn-success spyro-btn-sm no-loader"><i class="fa fa-check hidden"></i> {{App\Models\CustomerProfile::getInProgressProfiles()->count()}} en création</a>
		<a data-filter="Non abonné" data-toggle="tooltip" title="Filtrer par non abonnées" class="spyro-btn spyro-btn-default spyro-btn-sm no-loader"><i class="fa fa-check hidden"></i> {{App\Models\CustomerProfile::getNotSubscribedProfiles()->count()}} non abonnés</a>
		<a data-filter="Expiré" data-toggle="tooltip" title="Filtrer ceux expirés" class="spyro-btn spyro-btn-danger spyro-btn-sm no-loader"><i class="fa fa-check hidden"></i> {{App\Models\CustomerProfile::getExpiredProfiles()->count()}} expirés</a>
	</div>

	<table id="table-profiles">

		<thead>

			<tr>
				<th>ID</th>
				<th>Contrat</th>
				<th>Client</th>
				<th>Livraisons restantes</th>
				<th>Paiements effectués</th>
				<th>Statut</th>
				<th>Priorité</th>
				<th>Date de création</th>
				<th>Action</th>

			</tr>

		</thead>

		<tbody>

			@foreach ($profiles as $profile)

				<tr>
					<th>{{$profile->id}}</th>
					<th><a class="simple" data-profile="{{ $profile->id }}">{{$profile->contract_id}}</a></th>
					<th><a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile->customer()->first()->id]) }}">{{ ucwords(mb_strtolower($profile->customer()->first()->getFullName())) }}</a></th>
					<th>
						{{$profile->orders()->whereNull('date_sent')->count()}}
					</th>
					<th>
						{{$profile->payments()->where('paid', TRUE)->count()}}
					</th>

					<th>
	
						@if ($profile->status == NULL)

							N/A

						@else
					
							{!! Html::getReadableProfileStatus($profile->status) !!}

						@endif


					</th>

					<th>

						{!! Html::getReadableProfilePriority($profile->priority) !!}

					</th>

					<th>
					<span class="hidden">{{$profile->created_at}}</span>
					{!! Html::diffHumans($profile->created_at) !!}
					</th>

					<th>

					<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/profiles/edit/'.$profile->id) }}"><i class="fa fa-pencil"></i></a>
					<a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{ url('/admin/profiles/delete/'.$profile->id) }}"><i class="fa fa-archive"></i></a>

					</th>

				</tr>

			@endforeach

		</tbody>

	</table>

	</div>

	<!-- Tab List -->
	<div class="tab-pane" id="subscribed">

	Rien pour le moment
		
	</div>

	<!-- Tab List -->
	<div class="tab-pane" id="in-progress">

	Rien pour le moment
		
	</div>

	<!-- Tab List -->
	<div class="tab-pane" id="not-subscribed">

	Rien pour le moment
		
	</div>

	<!-- Tab List -->
	<div class="tab-pane" id="expired">

	Rien pour le moment
		
	</div>

	</div>


@stop
*/ ?>