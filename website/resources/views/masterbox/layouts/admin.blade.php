<!DOCTYPE HTML>
<html>
<head>

	{{-- Charset --}}
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	{{-- Title --}}
	<title>Bordeaux In Box</title>

	{{-- Favicon --}}
	<link rel="icon" href="{{ url('images/admin-favicon.ico') }}" />

	{{-- Responsive scale --}}
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0" />

	{{-- FontAwesome (icons) (we use CDN to load the icons faster) --}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

	{{-- SASS app --}}
	<link href="{{ Html::version('stylesheets/vendor.css') }}" rel="stylesheet">
	<link href="{{ Html::version('stylesheets/admin.css') }}" rel="stylesheet">

	{{-- CoffeeScript App --}}
	@if ( ! $app->environment('production'))

	<script>
		window.brunch = window.brunch || {};
		window.brunch.server = 'localhost';
	</script>

	@endif

	<script src="{{ Html::version('javascripts/vendor.js') }}"></script>
	<script src="{{ Html::version('javascripts/app.js') }}"></script>
	<script>require('initialize');</script>

</head>
<body id="csstyle" data-environment="{{ app()->environment() }}" data-app="masterbox-admin">
	
	@section('gotham')
		{!! Html::gotham() !!}
	@show
	
	<div id="sidebar" class="sidebar sidebar__wrapper">
		<ul class="sidebar__list">
			<li class="sidebar__item --brand">
				<a id="sidebar-brand" class="sidebar__brand" href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}">
					Boxes principales
				</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\EasyGoController@getIndex') }}"><i class="fa fa-heart"></i> Easy Go</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\DeliveriesController@getIndex') }}"><i class="fa fa-bank"></i> Séries</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\SpotsController@getIndex') }}"><i class="fa fa-map-marker"></i> Points relais</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\BoxController@getIndex') }}"><i class="fa fa-gift"></i> Box</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\ContentController@getBlog') }}"><i class="fa fa-picture-o"></i> Contenus</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\CustomersController@getIndex') }}"><i class="fa fa-group"></i> Clients</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}"><i class="fa fa-suitcase"></i> Abonnements</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\StatisticsController@getIndex') }}"><i class="fa fa-area-chart"></i> Statistiques</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\LogsController@getIndex') }}"><i class="fa fa-gear"></i> Logs &amp; Configuration</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\DebugController@getIndex') }}"><i class="fa fa-bug"></i> Debug</a>
			</li>
		</ul>
	</div>
	<div class="page page__wrapper">

		@section('navbar-container')
			<div class="navbar">
				<div class="navbar__wrapper">
					<div class="row">
						<div class="grid-10">
							<ul class="navbar__list">
								<!-- Navbar content -->
								@yield('navbar')
							</ul>
						</div>
						<div class="grid-2">
							<div class="navbar__logout">
								<a class="navbar__link --logout" href="{{ action('MasterBox\Connect\AdministratorController@getLogout') }}">{{ Auth::guard('administrator')->user()->getFullName() }} <i class="fa fa-remove"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		@show

		@yield('content')

		<div class="+spacer"></div>
	</div>

</body>	


</html>
