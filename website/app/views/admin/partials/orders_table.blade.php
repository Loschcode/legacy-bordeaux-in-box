
			<table class="js-datas">

				<thead>

					<tr>
						<th>ID</th>
						<th>Série</th>
						<th>Utilisateur</th>
						<th>Adresse utilisateur</th>
						<th>Téléphone utilisateur</th>
						<th>Email utilisateur</th>
						<th>Abonnement</th>
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

						<?php $profile = $order->user_profile()->first(); ?>
						<?php $box = $profile->box()->first(); ?>

						<tr>

							<th>{{$order->id}}</th>
							<th>{{$order->delivery_serie()->first()->delivery}}</th>
							<th><a href="{{ url('/admin/users/focus/'.$order->user_profile()->first()->user()->first()->id)}}">{{$order->user_profile()->first()->user()->first()->getFullName()}}</a></th>

							<th>{{ $order->user_profile()->first()->user()->first()->getFullAddress()}} </th>
							<th>{{ $order->user_profile()->first()->user()->first()->phone}} </th>

							<th>{{ $order->user_profile()->first()->user()->first()->email}} </th>

							<th>


							@if ($box == NULL)

								Non renseigné

							@else

								<a href="{{url('/admin/profiles/edit/'.$profile->id)}}">{{$box->title}}</a>

							@endif


							</th>
							<th>
							<!-- Questions -->

							@if ($box == NULL)

								Pas de question

							@else

								{{order_questions($box, $profile, " / ")}}

						    @endif

							</th>
							<th>
							@if ($box == NULL)

								Pas de réponse

							@else

								{{order_answers($box, $profile, " / ")}}

							@endif

							</th>
							<th>
								{{$order->already_paid}}€ / {{$order->unity_and_fees_price}}€

								@foreach ($order->payments()->get() as $payment)

									(<a href="{{url('/admin/payments/focus/'.$payment->id)}}">+</a>)

								@endforeach

							</th>
							<th>
							{{HTML::getReadableOrderStatus($order->status)}}
							</th>
							<th>{{HTML::boolYesOrNo($order->gift)}}</th>
							<th>{{HTML::getReadableOrderLocked($order->locked)}}</th>
							<th>{{HTML::getReadableTakeAway($order->take_away)}}</th>
							<th>{{HTML::getOrderSpotOrDestination($order)}}</th>
							<th>
							@if ($order->isRegionalOrder())
								Régional
							@else
								Non régional
							@endif
							</th>
							<th>{{$order->created_at}}</th>
							<th>{{$order->date_completed}}</th>
							<th>{{HTML::getReadableOrderStatus($order->status)}}</th>

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
