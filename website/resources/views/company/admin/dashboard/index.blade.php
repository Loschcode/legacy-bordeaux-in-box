@extends('company.layouts.admin')

@section('page')
	<i class="fa fa-home"></i> Société
@stop

@section('content')
	<p>
		Ceci est le panel d'administration de Bordeaux In Box, il vous permet d'éditer les différentes ressources de l'application.

    <br /><br />
    <a href="{{ action('Company\Admin\DashboardController@getIndex') }}">Société</a>
    <br />
    <a href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}">Boxes principales</a>
	</p>
@stop