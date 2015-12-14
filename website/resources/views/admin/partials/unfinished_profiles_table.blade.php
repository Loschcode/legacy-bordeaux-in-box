			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Utilisateur</th>
						<th>Abonnement</th>
						<th>Dernière étape</th>
						<th>Dernière mise à jour</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($user_order_buildings as $order_building)

						<? $profile = $order_building->profile()->first(); ?>
						<? $order_preference = $order_building->order_preference()->first(); ?>
						
						<? 
						if ($profile != NULL) {
							
							$box = $profile->box()->first();

						} else {

							$box = NULL;

						}

						?>
						<tr>

							<th>{{$order_building->id}}</th>

							<th>
							@if ($profile != NULL)
							<a href="/admin/users/focus/{{$profile->user()->first()->id}}">{{$profile->user()->first()->getFullName()}}</a>
							@endif
							</th>

							<th>
								
  							@if ($box == NULL)

  								Non renseigné

  							@else

  								<a href="{{url('/admin/profiles/edit/'.$profile->id)}}">{{$box->title}}</a>

  							@endif

							</th>

							<th>
								
								{{$order_building->step}}

							</th>

							<th>
								
								<span class="hidden">{{$order_building->updated_at}}</span>

								{{ str_replace('dans', '', strtolower(Html::diffHumans($order_building->updated_at))) }}

							</th>

						</tr>

					@endforeach

				</tbody>

			</table>