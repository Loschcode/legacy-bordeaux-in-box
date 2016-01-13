@extends('masterbox.layouts.admin')

@section('page')
	<i class="fa fa-list-alt"></i> Suivi des commandes
@stop

@section('buttons')

<a class="spyro-btn spyro-btn-danger" href="{{ url('admin/orders/everything-is-ready') }}">Tout est prêt</a>
<a class="spyro-btn spyro-btn-danger" href="{{ url('admin/orders/everything-has-been-sent') }}">Tout est envoyé</a>

<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/download-csv-orders') }}">Télécharger le fichier CSV</a>
<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/email-locked-orders') }}">Liste des Emails</a>

@stop

@section('content')

	<h2>LISTING DES EMAILS DES COMMANDES A PREPARER</h2>

	@foreach ($locked_orders as $order)

		<? $profile = $order->user_profile()->first() ?>

		@if ($profile != NULL)

			<?= $profile->customer()->first()->email ?>, 

		@endif

	@endforeach

@stop