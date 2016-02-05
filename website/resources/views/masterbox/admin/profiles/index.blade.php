@extends('masterbox.layouts.admin')

@section('gotham')
	{!! Html::gotham([
		'controller' => 'masterbox.admin.profiles.index'
	]) !!}
@stop

@section('content')

<div class="row">
	<div class="grid-6">
		<h1 class="title title__section">Abonnements</h1>
	</div>
	<div class="grid-6 +text-right">
		<a href="{{ action('MasterBox\Admin\ProfilesController@getResetProfilesPriorities') }}" class="button button__section">Réinitialiser les priorités des abonnements</a>
	</div>
</div>

<div class="divider divider__section"></div>

{!! Html::info("Ci dessous sont listées les différents abonnements des utilisateurs du site. Les utilisateurs peuvent avoir plusieurs abonnements") !!}

<span class="label label__default --green">Abonnés ({{ App\Models\CustomerProfile::getSubscribedProfiles()->count()}})</span>
<span class="label label__default --blue">En création ({{ App\Models\CustomerProfile::getInProgressProfiles()->count()}})</span>
<span class="label label__default --grey">Non abonnés ({{ App\Models\CustomerProfile::getNotSubscribedProfiles()->count()}})</span>
<span class="label label__default --red">Expirés ({{ App\Models\CustomerProfile::getExpiredProfiles()->count()}})</span>

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
		<th>Priorité</th>
		<th>Date de création</th>
		<th>Action</th>
	</tr>
</thead>
</table>

@stop
