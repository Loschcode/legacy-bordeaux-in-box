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