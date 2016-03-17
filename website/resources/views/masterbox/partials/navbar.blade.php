  <div class="header">
    @if (Auth::guard('customer')->check())
      <div class="header__account">
        <a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}" class="header__account-link"><i class="fa fa-user"></i> Mon compte</a>
      </div>
    @else
      <div class="header__account">
        <a href="{{ action('MasterBox\Connect\CustomerController@getIndex') }}" class="header__account-link"><i class="fa fa-lock"></i> Connexion</a>
      </div>
    @endif
    <div class="header__logo-container">
      <a href="{{ url('') }}">
        <img class="header__logo" src="{{ url('images/logo.png') }}" />
      </a>
    </div>
    <div class="header__navbar-container">
      <ul class="header__navbar">
        <li><a href="{{ action('MasterBox\Guest\HomeController@getConcept') }}" class="header__navbar-link">Concept</a></li>
        <li><a href="#" class="header__navbar-link --highlight">S'abonner</a></li>
        <li><a href="#" class="header__navbar-link --highlight">L'offrir</a></li>
        <li><a href="{{ action('MasterBox\Guest\HomeController@getLastBoxs') }}" class="header__navbar-link">Dernières Boxs</a></li>
        <li><a href="{{ action('MasterBox\Guest\BlogController@getIndex') }}" class="header__navbar-link">Complices</a></li>
      </ul>
    </div>
  </div>

<!--
<div class="navbar">

  <ul class="navbar__list js-menu @if (isset($navbar_home)) --home @endif">
    <li class="navbar__item"><a class="js-anchor" href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#how-to">Comment ça marche ?</a></li>
    <li class="navbar__item"><a class="js-anchor" href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#inside">Ce qu'il y a dans la boîte</a></li>

    @if (Auth::guard('customer')->check())

      <li class="navbar__item"><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}"><i class="fa fa-user"></i> Mon compte</a></li>

      @if (!Auth::guard('administrator')->guest())
      
        <li class="navbar__item"><a href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}"><i class="fa fa-gear"></i> Administration</a></li>
          
      @endif

    @else

      <li class="navbar__item"><a href="{{ action('MasterBox\Connect\CustomerController@getIndex') }}"><i class="fa fa-lock"></i> Connexion</a></li>

    @endif

  </ul>
</div>
-->