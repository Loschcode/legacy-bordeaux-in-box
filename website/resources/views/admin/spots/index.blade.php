@extends('layouts.admin')

@section('page')
	<i class="fa fa-map-marker"></i> Points relais
@stop

@section('buttons')
	<a class="spyro-btn spyro-btn-primary" href="{{ url('/admin/spots/new') }}"><i class="fa fa-plus"></i> Ajouter un point relais</a>
@stop

@section('content')

	@if (session()->has('message'))
	  <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
	@endif

	@if ($errors->has())
	  <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	@endif

	{!! Html::info("Lorsqu'un point relais est désactivé, il n'est plus visible dans la liste des points relais des nouvelles commandes utilisateur mais reste en place pour les commandes en cours. On ne peut pas supprimer un point relais reliés à des commandes en cours.") !!}

	<ul class="nav nav-tabs" role="tablist">
	  <li class="active"><a href="#online" role="tab" data-toggle="tab"><i class="fa fa-check"></i>Actifs ({{$active_spots->count()}})</a></li>
	  <li><a href="#offline" role="tab" data-toggle="tab"><i class="fa fa-times"></i> Hors lignes ({{$unactive_spots->count()}})</a></li>
	</ul>

	<div class="tab-content">

	  <!-- Tab List -->
	  <div class="tab-pane active" id="online">

	  	<table class="js-datas">

	  		<thead>

	  			<tr>
	  				<th>ID</th>
	  				<th>Nom</th>
	  				<th>Statut</th>
	  				<th>Commandes actives reliées (toutes séries confondues)</th>
	  				<th>Ville</th>
	  				<th>Code postal</th>
	  				<th>Adresse</th>
	  				<th>Horaires</th>
	  				<th>Action</th>

	  			</tr>

	  		</thead>

	  		<tbody>

	  			@foreach ($active_spots as $spot)

	  				<tr>
	  					<th>{{$spot->id}}</th>
	  					<th>{{$spot->name}}</th>
	  					<th>{!! Html::getReadableActive($spot->active) !!}</th>
	  					<th>{{$spot->orders()->where('take_away', '=', true)->activeOrders()->count()}}</th>
	  					<th>{{$spot->city}}</th>
	  					<th>{{$spot->zip}}</th>
	  					<th>{{$spot->address}}</th>
	  					<th>{{$spot->schedule}}</th>
	  					<th>

	  					@if ($spot->active)
	  						<a data-toggle="tooltip" title="Désactiver" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{ url('/admin/spots/desactivate/'.$spot->id) }}"><i class="fa fa-times"></i></a>
	  					@else
	  					<a data-toggle="tooltip" title="Activer" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/spots/activate/'.$spot->id) }}"><i class="fa fa-check"></i></a>
	  					@endif

	  					<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/spots/edit/'.$spot->id) }}"><i class="fa fa-pencil"></i></a>

	  					@if ($spot->orders()->notCanceledOrders()->count() <= 0)
	  					 	<a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{ url('/admin/spots/delete/'.$spot->id) }}"><i class="fa fa-trash-o"></i></a></th>
	  					@endif

	  				</tr>

	  			@endforeach

	  		</tbody>

	  	</table>

			<br />

      <div class="panel panel-default">

        <div class="panel-heading">Transférer les abonnements vers un point relais</div>
        <div class="panel-body">

				{!! Html::info("Un email sera envoyé aux abonnés leur indiquant le changement de point relais lié à leur abonnement") !!}

          {!! Form::open(['class' => 'form-inline', 'action' => 'Admin\SpotsController@postTransferSpotSubscriptions']) !!}

          <div class="form-group @if ($errors->first('old_spot')) has-error has-feedback @endif">
            {!! Form::label("old_spot", "Point relais d'origine", ['class' => 'sr-only']) !!}
            {!! Form::select("old_spot", $spots_list) !!}
          </div>

          vers

          <div class="form-group @if ($errors->first('new_spot')) has-error has-feedback @endif">
            {!! Form::label("new_spot", "Nouveau point relais", ['class' => 'sr-only']) !!}
            {!! Form::select("new_spot", $spots_list) !!}
          </div>

          {!! Form::submit("Transférer", ['class' => 'spyro-btn spyro-btn-success']) !!}

          {!! Form::close() !!}

        </div>
      </div>

	  </div>

	  <div class="tab-pane" id="offline">
	  	<table class="js-datas">

	  		<thead>

	  			<tr>
	  				<th>Nom</th>
	  				<th>Statut</th>
	  				<th>Commandes actives reliées (toutes séries confondues)</th>
	  				<th>Ville</th>
	  				<th>Code postal</th>
	  				<th>Adresse</th>
	  				<th>Horaires</th>
	  				<th>Action</th>

	  			</tr>

	  		</thead>

	  		<tbody>

	  			@foreach ($unactive_spots as $spot)

	  				<tr>
	  					<th>{{$spot->name}}</th>
	  					<th>{!! Html::getReadableActive($spot->active) !!}</th>
	  					<th>{{$spot->orders()->where('take_away', '=', true)->activeOrders()->count()}}</th>
	  					<th>{{$spot->city}}</th>
	  					<th>{{$spot->zip}}</th>
	  					<th>{{$spot->address}}</th>
	  					<th>{{$spot->schedule}}</th>
	  					<th>


	  					@if ($spot->active)
	  						<a data-toggle="tooltip" title="Désactiver" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{ url('/admin/spots/desactivate/'.$spot->id) }}"><i class="fa fa-times"></i></a>
	  					@else
	  					<a data-toggle="tooltip" title="Activer" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/spots/activate/'.$spot->id) }}"><i class="fa fa-check"></i></a>
	  					@endif

	  					<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/spots/edit/'.$spot->id) }}"><i class="fa fa-pencil"></i></a>

	  					@if ($spot->orders()->notCanceledOrders()->count() <= 0)
	  					 	<a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{ url('/admin/spots/delete/'.$spot->id) }}"><i class="fa fa-trash-o"></i></a></th>
	  					@endif


	  				</tr>

	  			@endforeach

	  		</tbody>

	  	</table>
	  </div>

@stop