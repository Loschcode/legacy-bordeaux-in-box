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
<body id="csstyle" class="background-grey" data-environment="{{ app()->environment() }}" data-app="masterbox-admin">
  
  @section('gotham')
    {!! Html::gotham() !!}
  @show

  
  @yield('content')

</body> 


</html>
