<!DOCTYPE HTML>
<html>
<head>

  {{-- Charset --}}
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  @section('meta-facebook')
  @show

  {{-- Title --}}
  <title>Bordeaux In Box</title>

  {{-- Favicon --}}
  <link rel="icon" href="{{ url('images/global/favicon-bib.ico') }}" />

  {{-- Responsive scale --}}
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0" />

  {{-- FontAwesome (icons) (we use CDN to load the icons faster) --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.css" rel="stylesheet">

  {{-- SASS app --}}
  <link href="{{ Html::version('stylesheets/vendor.css') }}" rel="stylesheet">
  <link href="{{ Html::version('stylesheets/masterbox.css') }}" rel="stylesheet">
  
  {{-- CoffeeScript App --}}
  @if ( ! $app->environment('production'))

  <script>
    window.brunch = window.brunch || {};
    window.brunch.server = 'localhost';
  </script>

  @endif
  
  {{-- Display the javascript stripe checkout here, if needed --}}
  @yield('stripe-checkout')
  
  {{-- Display the javascript stripe library here, if needed --}}
  @yield('stripe')

  <script src="{{ Html::version('javascripts/vendor.js') }}"></script>
  <script src="{{ Html::version('javascripts/app.js') }}"></script>
  <script>require('initialize');</script>

</head>

<body id="csstyle" data-environment="{{ app()->environment() }}" data-app="masterbox">
  
  @section('gotham')
  {!! Html::gotham() !!}
  @show
  
  @section('header')
    @include('masterbox.partials.navbar')

  @show

  @yield('content')
  
  @section('footer-spacer')
  <div class="+spacer"></div>
  @show

  {{-- Footer --}}
  @section('footer')
  @include('masterbox.partials.footer')
  @show
  
</body>

@if (app()->environment() !== 'testing' && is_someone_online_slack())

  {{-- Support --}}
  <script src="https://cdn.smooch.io/smooch.min.js"></script>

  @if (Auth::guard('customer')->check())
    <script>
    Smooch.init({
      appToken: '3lcdwxsxss1gvpzcel9yhunam',
      givenName: '{{ Auth::guard('customer')->user()->full_name }}',
      email: '{{ Auth::guard('customer')->user()->email }}',
      properties: {
        customer_id: '{{ Auth::guard('customer')->user()->id }}',
        customer_admin_url: '{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => Auth::guard('customer')->user()->id]) }}'
      },
      customText: {
        headerText: 'Une question ? Demande-nous !',
        inputPlaceholder: 'Écris ton message',
        sendButtonText: 'Envoyer',
        introText: '',
        settingsText: ''
      }
    });
    </script>
  @else

  <script>
  Smooch.init({
    appToken: '3lcdwxsxss1gvpzcel9yhunam',
    customText: {
      headerText: 'Une question ? Demande-nous !',
      inputPlaceholder: 'Écris ton message',
      sendButtonText: 'Envoyer',
      introText: '',
      settingsText: ''
    }
  });
  </script>

  @endif
@endif

{{-- Facebook Conversion Code for Impressions --}}
@include('masterbox.partials.facebook_conversions')

{{-- Google analytics tracking --}}
@include('masterbox.partials.google_analytics')

{{-- Hotjar tracking (mouse, events, etc) --}}
@include('masterbox.partials.hotjar')

</html>
