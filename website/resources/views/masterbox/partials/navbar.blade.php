<div class="navbar">

  <ul class="navbar__list">
    <li class="navbar__item"><a class="js-anchor" href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#how-to">Comment ça marche ?</a></li>
    <li class="navbar__item"><a class="js-anchor" href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#inside">Ce qu'il y a dans la boîte</a></li>

    @if (Auth::guard('customer')->check())

      <li class="navbar__item"><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}"><i class="fa fa-user"></i> Mon compte</a></li>

      @if (Auth::guard('customer')->user()->role == 'admin')
      
        <li class="navbar__item"><a href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}"><i class="fa fa-gear"></i> Administration</a></li>
          
      @endif

    @else

      <li class="navbar__item"><a href="{{ action('MasterBox\Connect\CustomerController@getIndex') }}"><i class="fa fa-lock"></i> Connexion</a></li>

    @endif

    <li class="navbar__item"><a href="{{ action('MasterBox\Guest\HomeController@getHelp') }}"><i class="fa fa-question-circle"></i> Aide</a></li>
  </ul>
</div>