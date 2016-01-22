<!DOCTYPE HTML>
<html>
<head>

  {{-- Charset --}}
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  {{-- Title --}}
  <title>Bordeaux In Box</title>

  {{-- Favicon --}}
  <link rel="icon" href="{{ url('assets/images/favicon-bib.ico') }}" />

  {{-- Responsive scale --}}
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0" />

  {{-- FontAwesome (icons) (we use CDN to load the icons faster) --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

  {{-- SASS app --}}
  <link href="{{ url('stylesheets/vendor.css') }}" rel="stylesheet">
  <link href="{{ url('stylesheets/masterbox-admin.css') }}" rel="stylesheet">
  
  {{-- CoffeeScript App --}}
  @if ( ! $app->environment('production'))

    <script>
      window.brunch = window.brunch || {};
      window.brunch.server = 'localhost';
    </script>

  @endif

  <script src="{{ url('javascripts/vendor.js') }}"></script>
  <script src="{{ url('javascripts/app.js') }}"></script>
  <script>require('initialize');</script>

</head>
<body>

	<div
		id="gotham-layout"
		data-layout="masterbox-admin"
	></div>
	
	<div id="sidebar" class="sidebar sidebar__wrapper">
		<ul class="sidebar__list">
			<li class="sidebar__item --brand">
				<a id="sidebar-brand" class="sidebar__brand" href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}">
					Boxes principales
				</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ url('easygo') }}"><i class="fa fa-heart"></i> Easy Go</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\OrdersController@getIndex') }}"><i class="fa fa-truck"></i> Suivi des commandes</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\DeliveriesController@getIndex') }}"><i class="fa fa-bank"></i> Séries &amp; Finances</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\SpotsController@getIndex') }}"><i class="fa fa-map-marker"></i> Points relais</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\BoxController@getIndex') }}"><i class="fa fa-gift"></i> Box</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\ContentController@getIndex') }}"><i class="fa fa-picture-o"></i> Contenus</a>
			</li>
			<li class="sidebar__item">
				<a class="sidebar__link" href="{{ action('MasterBox\Admin\CustomersController@getIndex') }}"><i class="fa fa-group"></i> Utilisateurs</a>
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
		@yield('page')
		@yield('buttons')
		@yield('content')

	</div>

	<?php /*>
	<div id="wrapper">

	    <!-- Sidebar -->
	    <div id="sidebar-wrapper">
	        <ul class="sidebar-nav">
	            <li class="sidebar-brand">
	                <a href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}">
	                    Boxes principales
	                </a>
	            </li>
	            <li>
	            	<a href="{{ url('easygo') }}"><i class="fa fa-heart"></i> Easy Go</a>
	            </li>
	            <li>
	                <a href="{{ action('MasterBox\Admin\OrdersController@getIndex') }}"><i class="fa fa-truck"></i> Suivi des commandes</a>
	            </li>
	            <li>
	                <a href="{{ action('MasterBox\Admin\DeliveriesController@getIndex') }}"><i class="fa fa-bank"></i> Séries &amp; Finances</a>
	            </li>
	            <li>
	                <a href="{{ action('MasterBox\Admin\SpotsController@getIndex') }}"><i class="fa fa-map-marker"></i> Points relais</a>
	            </li>
	            <li>
	                <a href="{{ action('MasterBox\Admin\BoxController@getIndex') }}"><i class="fa fa-gift"></i> Box</a>
	            </li>
	            <li>
	                <a href="{{ action('MasterBox\Admin\ContentController@getIndex') }}"><i class="fa fa-picture-o"></i> Contenus</a>
	            </li>
	            <li>
	            	<a href="{{ action('MasterBox\Admin\CustomersController@getIndex') }}"><i class="fa fa-group"></i> Utilisateurs</a>
	            </li>
	            <li>
	            	<a href="{{ action('MasterBox\Admin\ProfilesController@getIndex') }}"><i class="fa fa-suitcase"></i> Abonnements</a>
	            </li>
	            <li>
	            	<a href="{{ action('MasterBox\Admin\StatisticsController@getIndex') }}"><i class="fa fa-area-chart"></i> Statistiques</a>
	            </li>
	            <li>
	            	<a href="{{ action('MasterBox\Admin\LogsController@getIndex') }}"><i class="fa fa-gear"></i> Logs &amp; Configuration</a>
	            </li>
	            <li>
	            <a href="{{ action('MasterBox\Admin\DebugController@getIndex') }}"><i class="fa fa-bug"></i> Debug</a>
	            </li>
	        </ul>
	    </div>
	    <!-- /#sidebar-wrapper -->

	    <!-- Page Content -->
	    <div id="page-content-wrapper">
	        <div class="container-fluid">
	            <div class="row">
	                <div class="col-lg-12">

	                	<div class="row header">
	                		<div class="col-md-9">
			                	<h1 class="page">
			                		@yield('page')
			                	</h1>
			                </div>
			                <div class="col-md-3 buttons-container">
			                	@yield('buttons')
			                </div>
	                	</div>

	                	<div class="clearfix"></div>

	                	@yield('content')

	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- /#page-content-wrapper -->

	</div>
	<!-- /#wrapper -->
	*/ ?>
</body>	


</html>
