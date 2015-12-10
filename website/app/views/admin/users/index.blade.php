@section('page')
	<i class="fa fa-user"></i> Utilisateurs ({{$users->count()}})
@stop

@section('content')

	@if (Session::has('message'))
		<div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
	@endif
	
	{{ HTML::info('Les utilisateurs correspondent aux comptes d\'inscription, ils ne sont pas représentatifs des `profils` reliés aux commandes et encore moins des utilisateurs actifs et/ou ayant commandé.') }}

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

			@foreach ($users as $user)

				<tr>

					<th>{{$user->id}}</th>
					<th>{{$user->email}}</th>
					<th>{{$user->phone}}</th>
					<th>{{$user->getFullName()}}</th>
					<th>{{HTML::getReadableRole($user->role)}}</th>
					<th>
						@if ($user->profiles()->count() > 0)
							@foreach ($user->profiles()->get() as $profile)

							@if ($profile->box()->first() != NULL)

								<a class="spyro-btn {{HTML::getColorFromBoxSlug($profile->box()->first()->slug)}}" href="/admin/profiles/edit/{{$profile->id}}">
								

								{{HTML::getReadableProfileStatus($profile->status)}}

								</a><br/>

							@endif

							@endforeach
						@else
							N/A
						@endif

					</th>
					<th>{{ $user->getTurnover() }} €</th>
					<th>{{ HTML::getReadableEmpty($user->city)}}</th>
					<th>{{ HTML::getReadableEmpty($user->zip) }}</th>
					<th>{{ HTML::getReadableEmpty($user->address) }}</th>
					<th>

    
					<a data-toggle="tooltip" title="En savoir plus" class="spyro-btn-sm spyro-btn spyro-btn-primary" href="{{ url('/admin/users/focus/'.$user->id) }}"><i class="fa fa-search"></i></a>

					<a data-toggle="tooltip" title="Editer" class="spyro-btn-sm spyro-btn spyro-btn-warning" href="{{ url('/admin/users/focus/'.$user->id) }}#edit"><i class="fa fa-pencil"></i></a>

					</th>

				</tr>

			@endforeach

		</tbody>

	</table>
@stop