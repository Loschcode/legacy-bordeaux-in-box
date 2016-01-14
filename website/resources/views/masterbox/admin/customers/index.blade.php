@extends('masterbox.layouts.admin')

@section('page')
	<i class="fa fa-user"></i> Utilisateurs ({{$customers->count()}})
@stop

@section('content')

	@if (session()->has('message'))
		<div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
	@endif
	
	{!! Html::info('Les utilisateurs correspondent aux comptes d\'inscription, ils ne sont pas représentatifs des `profils` reliés aux commandes et encore moins des utilisateurs actifs et/ou ayant commandé.') !!}

	<table class="js-datas">

		<thead>

			<tr>
				<th>ID</th>
				<th>Email</th>
				<th>Téléphone</th>
				<th>Nom</th>
				<th>Role</th>
				<th>Abonnements</th>
				<th>Total payé</th>
				<th>Ville</th>
				<th>Code postal</th>
				<th>Adresse</th>
				<th>Action</th>

			</tr>

		</thead>

		<tbody>

			@foreach ($customers as $customer)

				<tr>

					<th>{{$customer->id}}</th>
					<th>{{$customer->email}}</th>
					<th>{{$customer->phone}}</th>
					<th>{{$customer->getFullName()}}</th>
					<th>{!! Html::getReadableRole($customer->role) !!}</th>
					<th>
						@if ($customer->profiles()->count() > 0)
							@foreach ($customer->profiles()->get() as $profile)

							@if ($profile->box()->first() != NULL)

								<a class="spyro-btn {{Html::getColorFromBoxSlug($profile->box()->first()->slug)}}" href="/admin/profiles/edit/{{$profile->id}}">
								

								{!! Html::getReadableProfileStatus($profile->status) !!}

								</a><br/>

							@endif

							@endforeach
						@else
							N/A
						@endif

					</th>
					<th>{{ $customer->getTurnover() }} €</th>
					<th>{!! Html::getReadableEmpty($customer->city) !!}</th>
					<th>{!! Html::getReadableEmpty($customer->zip) !!}</th>
					<th>{!! Html::getReadableEmpty($customer->address) !!}</th>
					<th>

    
					<a data-toggle="tooltip" title="En savoir plus" class="spyro-btn-sm spyro-btn spyro-btn-primary" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id]) }}"><i class="fa fa-search"></i></a>

					<a data-toggle="tooltip" title="Editer" class="spyro-btn-sm spyro-btn spyro-btn-warning" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id]) }}#edit"><i class="fa fa-pencil"></i></a>

					</th>

				</tr>

			@endforeach

		</tbody>

	</table>
@stop