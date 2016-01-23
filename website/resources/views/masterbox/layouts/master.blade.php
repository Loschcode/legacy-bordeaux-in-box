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
  <link href="{{ url('stylesheets/masterbox.css') }}" rel="stylesheet">
  
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

<body id="csstyle" data-environment="{{ app()->environment() }}">
  
  <div class="header">
    <div class="header__item --first"></div>
    <div class="header__item --second"></div>
    <div class="header__item --third"></div>
    <div class="header__item --fourth"></div>
    <div class="header__item --fifth"></div>
  </div>

  {{-- Logo --}}
  <div class="logo">
    <a href="{{ action('MasterBox\Guest\HomeController@getIndex') }}" class="logo__link">
      <img class="logo__picture" src="{{ url('images/logo.png') }}" />
    </a>
  </div>

  {{-- Navbar --}}
  @include('masterbox.partials.navbar')

  @yield('content')

  {{-- Footer --}}
  @section('footer')
    <div class="+spacer"></div>
    @include('masterbox.partials.footer')
  @show

</body>

@section('stripe-checkout')
@show

{{-- Facebook Conversion Code for Impressions --}}
@include('masterbox.partials.facebook_conversions')

{{-- Google analytics tracking --}}
@include('masterbox.partials.google_analytics')

</html>
