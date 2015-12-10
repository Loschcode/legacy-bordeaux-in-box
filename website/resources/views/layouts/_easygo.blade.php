<!DOCTYPE HTML>

<html>

<head>

  <!-- Charset -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <title>Bordeaux In Box</title>

  <!-- Bootstrap -->
  <link href="{{ url('public/assets/bower_components/bootstrap/dist/css/bootstrap.css') }}" rel="stylesheet">

  <!-- Font awesome -->
  <link href="{{ url('public/assets/bower_components/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <!-- Flat ui -->
  <link href="{{ url('public/assets/bower_components/Bootflat/bootflat/css/bootflat.css') }}" rel="stylesheet">

  <!-- Dashboard -->
  <link href="{{ url('public/assets/css/dashboard.css') }}" rel="stylesheet">

</head>

<body>
  @yield('content')
</body>

<!-- jQuery -->
<script src="{{ url('public/assets/bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Underscore -->
<script src="{{ url('public/assets/bower_components/underscore/underscore-min.js') }}"></script>

<!-- jQuery Appear -->
<script src="{{ url('public/assets/bower_components/appear/jquery.appear.js') }}"></script>

<!-- Boostrap -->
<script src="{{ url('public/assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script src="{{ url('public/assets/js/dashboard/dashboard.js') }}"></script>

</html>
