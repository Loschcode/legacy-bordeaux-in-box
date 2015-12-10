<!DOCTYPE HTML>
<html>
  <head>

    <!-- Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Bordeaux In Box</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="{{ url('public/stylesheets/vendor.css') }}" rel="stylesheet">
    <link href="{{ url('public/stylesheets/easygo.css') }}" rel="stylesheet">

    <!-- Javascripts -->
    <script src="{{ url('public/javascripts/vendor.js') }}"></script>
    <script src="{{ url('public/javascripts/app.js') }}"></script>
    <script>require('initialize');</script>

  </head>

  <body>
    @yield('content')
  </body>
</html>
