<!DOCTYPE HTML>
<html>
<head>

  <!-- Charset -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <!-- Title -->
  <title>Bordeaux In Box</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ url('public/assets/img/favicon-bib.ico') }}" />

  <!-- Responsive scale -->
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

  <!-- Vendor css -->
  <link href="{{ url('public/stylesheets/vendor.css?version=1.2') }}" rel="stylesheet">

  <!-- Front -->
  <link href="{{ url('public/stylesheets/front.css?version=1.2') }}" rel="stylesheet">

  <!-- App -->
  <link href="{{ url('public/assets/css/app.css?version=1.2') }}" rel="stylesheet">

</head>

<body>
  <div class="header">
    <div class="fill1"></div>
    <div class="fill2"></div>
    <div class="fill3"></div>
    <div class="fill4"></div>
    <div class="fill5"></div>
  </div>

  <!-- Logo -->
  <div class="img --logo center">
    <a href="{{ url() }}"><img id="logo-text" src="{{ url('public/images/logo.png') }}" /></a>
  </div>

  <!-- Navigation -->
  @include('_includes.front.nav')

  @yield('content')


</body>

<!-- Vendor js -->
<script src="{{ url('public/javascripts/vendor.js') }}"></script>

<!-- Gotham App -->
<script src="{{ url('public/javascripts/app.js') }}"></script>
<script>require('initialize');</script>

<!-- Controllers -->
<script src="{{ url('public/assets/js/app/global.js') }}"></script>
<script src="{{ url('public/assets/js/app/box.js') }}"></script>
<script src="{{ url('public/assets/js/app/billing.js') }}"></script>
<script src="{{ url('public/assets/js/app/payment.js') }}"></script>
<script src="{{ url('public/assets/js/app/login.js') }}"></script>
<script src="{{ url('public/assets/js/app/spot.js') }}"></script>
<script src="{{ url('public/assets/js/app/card.js') }}"></script>


<!-- The required Stripe lib -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<!-- App -->
<script src="{{ url('public/assets/js/app/main.js') }}"></script>

<!-- Facebook Conversion Code for Impressions -->
@include('_includes.front.facebook_conversions')

<!-- Google Analytics -->
@include('_includes.front.google_analytics')


</html>
