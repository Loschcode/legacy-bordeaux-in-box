			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Client</th>
						<th>Abonnement</th>
						<th>Dernière étape</th>
						<th>Dernière mise à jour</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($customer_order_buildings as $order_building)

						<? $profile = $order_building->profile()->first(); ?>
						<? $order_preference = $order_building->order_preference()->first(); ?>
						
						<tr>

							<th>{{$order_building->id}}</th>

							<th>
							@if ($profile != NULL)
							<a href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $profile->customer()->first()->id]) }}">{{$profile->customer()->first()->getFullName()}}</a>
							@endif
							</th>

							<th>
								
                <a class="spyro-btn btn-blue {{HTML::getColorFromProfileStatus($profile->status)}}" href="{{action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id])}}">
                

                {!! Html::getReadableProfileStatus($profile->status) !!}

                </a><br/>

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