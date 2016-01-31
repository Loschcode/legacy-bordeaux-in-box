
			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Série</th>
						<th>Client</th>
						<th>Adresse utilisateur</th>
						<th>Téléphone utilisateur</th>
						<th>Email utilisateur</th>
						<th>Questions</th>
						<th>Réponses</th>
						<th>Paiement</th>
						<th>Status</th>
						<th>A offrir</th>
						<th>Etat de la commande</th>
						<th>Mode</th>
						<th>Destination / Spot</th>
						<th>Zone</th>
						<th>Création</th>
						<th>Fin préparation</th>
						<th>Statut de la commande</th>
						<th>Action</th>
					</tr>

				</thead>

				<tbody>

					@foreach ($orders as $order)

						<?php $profile = $order->customer_profile()->first(); ?>

						<tr>

							<th>{{$order->id}}</th>
							<th>{{$order->delivery_serie()->first()->delivery}}</th>
							<th><a href="{{ url('/admin/users/focus/'.$order->customer_profile()->first()->customer()->first()->id)}}">{{$order->customer_profile()->first()->customer()->first()->getFullName()}}</a></th>

							<th>{{ $order->customer_profile()->first()->customer()->first()->getFullAddress()}} </th>
							<th>{{ $order->customer_profile()->first()->customer()->first()->phone}} </th>

							<th>{{ $order->customer_profile()->first()->customer()->first()->email}} </th>

							<th>
							<!-- Questions -->

								{!! order_questions($profile, " / ") !!}


							</th>
							<th>

								{!! order_answers($profile, " / ") !!}


							</th>
							<th>
								{{$order->already_paid}}€ / {{$order->unity_and_fees_price}}€

								@foreach ($order->payments()->get() as $payment)

									(<a href="{{url('/admin/payments/focus/'.$payment->id)}}">+</a>)

								@endforeach

							</th>
							<th>
							{!! Html::getReadableOrderStatus($order->status) !!}
							</th>
							<th>{!! Html::boolYesOrNo($order->gift) !!}</th>
							<th>{!! Html::getReadableOrderLocked($order->locked) !!}</th>
							<th>{!! Html::getReadableTakeAway($order->take_away) !!}</th>
							<th>{!! Html::getOrderSpotOrDestination($order) !!}</th>
							<th>
							@if ($order->isRegionalOrder())
								Régional
							@else
								Non régional
							@endif
							</th>
							<th>{{$order->created_at}}</th>
							<th>{{$order->date_completed}}</th>
							<th>{!! Html::getReadableOrderStatus($order->status) !!}</th>

							<th>

							@if ($order->date_completed != NULL)

								<a href="{{ url('/admin/orders/confirm-sent/'.$order->id) }}">Envoi confirmé</a> |

							@else

								<a href="{{ url('/admin/orders/confirm-ready/'.$order->id) }}">Prête pour envoi</a> |

							@endif

							<a href="{{ url('/admin/orders/confirm-problem/'.$order->id) }}">Signaler problème</a> |

							<a href="{{ url('/admin/orders/confirm-cancel/'.$order->id) }}">Annuler</a> |

							<a href="{{ url('/admin/orders/delete/'.$order->id) }}">Archiver</a>

							</th>

						</tr>

					@endforeach

				</tbody>

			</table>
