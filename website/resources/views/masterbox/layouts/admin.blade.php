<!DOCTYPE HTML>

<html>

<head>

	<!-- Charset -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Bordeaux In Box</title>

	<link rel="icon" href="{{ url('assets/img/admin-favicon.ico') }}" />

	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

  <!-- Vendor.css -->
  <link href="{{ url('stylesheets/vendor.css') }}" rel="stylesheet">

	<!-- App -->
	<link href="{{ url('assets/css/admin.css') }}" rel="stylesheet">

</head>

<body>

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
	                <a href="{{ action('MasterBox\Admin\ProductsController@getIndex') }}"><i class="fa fa-folder"></i> Produits &amp; Partenaires</a>
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

</body>

<!-- Vendor.js -->
<script src="{{ url('javascripts/vendor.js') }}"></script>

<!-- Controllers -->
<script src="{{ url('assets/js/admin/global.js') }}"></script>
<script src="{{ url('assets/js/admin/contact.js') }}"></script>
<script src="{{ url('assets/js/admin/logs.js') }}"></script>
<script src="{{ url('assets/js/admin/profile.js') }}"></script>
<script src="{{ url('assets/js/admin/bip.js') }}"></script>

<!-- App -->
<script src="{{ url('assets/js/admin/main.js') }}"></script>


</html>
