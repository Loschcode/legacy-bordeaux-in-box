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

<body id="csstyle" data-environment="{{ app()->environment() }}" data-app="company-admin">
  
  @section('gotham')
    {!! Html::gotham() !!}
  @show
  
  <div id="sidebar" class="sidebar sidebar__wrapper">
    <ul class="sidebar__list">
      <li class="sidebar__item --brand">
        <a id="sidebar-brand" class="sidebar__brand" href="{{ action('Company\Admin\DashboardController@getIndex') }}">
          Société
        </a>
      </li>
      </li>

      <li class="sidebar__item">
        <a class="sidebar__link" href="{{ action('Company\Admin\FinancesController@getIndex') }}"><i class="fa fa-calculator"></i> Finances</a>
      </li>

      <li class="sidebar__item">
        <a class="sidebar__link" href="{{ action('Company\Admin\CoordinatesController@getIndex') }}"><i class="fa fa-location-arrow"></i> Coordonnées</a>
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

<?php /*
<!DOCTYPE HTML>

<html>

<head>

  <!-- Charset -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <title>Bordeaux In Box</title>

  <link rel="icon" href="{{ url('images/admin-favicon.ico') }}" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

  <!-- Vendor.css -->
  <link href="{{ Html::version('stylesheets/vendor.css') }}" rel="stylesheet">

  <!-- App -->
  <link href="{{ Html::version('assets/css/admin.css') }}" rel="stylesheet">

</head>

<body>

  <div id="wrapper">

      <!-- Sidebar -->
      <div id="sidebar-wrapper">
          <ul class="sidebar-nav">
              <li class="sidebar-brand">
                  <a href="{{ action('Company\Admin\DashboardController@getIndex') }}">
                      Société
                  </a>
              </li>
              <li>
                  <a href="{{ action('Company\Admin\FinancesController@getIndex') }}"><i class="fa fa-calculator"></i> Finances</a>
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

*/ ?>