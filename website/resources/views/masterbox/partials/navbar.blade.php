
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
      <ul class="header__navbar js-menu">
        <div class="show@xs hide">
          @if (Auth::guard('customer')->check())
            <li><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}" class="header__account-link"><i class="fa fa-user"></i> Mon compte</a>
            </li>
          @else
            <li>
              <a href="{{ action('MasterBox\Connect\CustomerController@getIndex') }}" class="header__account-link"><i class="fa fa-lock"></i> Connexion</a>
            </li>
          @endif
        </div>
        <li><a href="{{ action('MasterBox\Guest\HomeController@getConcept') }}" class="header__navbar-link">Concept</a></li>
        @if (App\Models\DeliverySerie::nextOpenSeries()->first() !== NULL)
          @if (App\Models\DeliverySerie::nextOpenSeries()->first()->getCounter() !== 0 || App\Models\DeliverySerie::nextOpenSeries()->first()->getCounter() === FALSE)
              
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}" class="header__navbar-link --highlight">S'abonner</a></li>
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getGift') }}" class="header__navbar-link --highlight">L'offrir</a></li>
          @endif
        @endif

        @if (App\Models\DeliverySerie::nextOpenSeries()->first() === NULL or App\Models\DeliverySerie::nextOpenSeries()->first()->getCounter() === 0)
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getClassic') }}" class="header__navbar-link --highlight js-no-boxes">S'abonner</a></li>
          <li><a href="{{ action('MasterBox\Customer\PurchaseController@getGift') }}" class="header__navbar-link --highlight js-no-boxes">L'offrir</a></li>
        @endif

        <li><a href="{{ action('MasterBox\Guest\HomeController@getBox', ['month' => 'march', 'year' => 2016]) }}" class="header__navbar-link">Dernières Boxs</a></li>
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