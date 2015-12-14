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
	                <a href="{{ url('/admin') }}">
	                    Admin
	                </a>
	            </li>
	            <li>
	            	<a href="{{ url('easygo') }}"><i class="fa fa-heart"></i> Easy Go</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/orders') }}"><i class="fa fa-truck"></i> Suivi des commandes</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/deliveries') }}"><i class="fa fa-bank"></i> Séries &amp; Finances</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/spots') }}"><i class="fa fa-map-marker"></i> Points relais</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/boxes') }}"><i class="fa fa-gift"></i> Boxes</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/products') }}"><i class="fa fa-folder"></i> Produits &amp; Partenaires</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/shop') }}"><i class="fa fa-shopping-cart"></i> Shop</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/content') }}"><i class="fa fa-picture-o"></i> Contenus</a>
	            </li>
	            <li>
	            	<a href="{{ url('admin/users') }}"><i class="fa fa-group"></i> Utilisateurs</a>
	            </li>
	            <li>
	            	<a href="{{ url('admin/profiles') }}"><i class="fa fa-suitcase"></i> Abonnements</a>
	            </li>
	            <li>
	                <a href="{{ url('admin/taxes') }}"><i class="fa fa-calculator"></i> Factures &amp; Fiscalité</a>
	            </li>
	            <li>
	            	<a href="{{ url('admin/statistics') }}"><i class="fa fa-area-chart"></i> Statistiques</a>
	            </li>
	            <li>
	            	<a href="{{ url('admin/logs') }}"><i class="fa fa-gear"></i> Logs &amp; Configuration</a>
	            </li>
	            <li>
	            <a href="{{ url('admin/debug') }}"><i class="fa fa-bug"></i> Debug</a>
	            </li>
	            <li>
	            <a href="{{ url('admin/bip') }}"><i class="fa fa-music"></i> Bip</a>
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
