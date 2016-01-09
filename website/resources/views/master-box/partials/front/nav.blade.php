<!-- Navigation -->
<div id="subnav" class="navigation">
  <ul>

    <li><a href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#how-to">Comment ça marche ?</a></li>
    <li><a href="{{ action('MasterBox\Guest\HomeController@getIndex') }}#inside">Ce qu'il y a dans la boîte</a></li>

    @if (Auth::check())

      <li><a href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}"><i class="fa fa-user"></i> Mon compte</a></li>

      @if (Auth::user()->role == 'admin')
      
        <li><a href="{{ action('MasterBox\Admin\DashboardController@getIndex') }}"><i class="fa fa-gear"></i> Administration</a></li>
          
      @endif

    @else

      <li><a href="{{ url('user/login') }}"><i class="fa fa-lock"></i> Connexion</a></li>

    @endif

    <li><a href="{{ url('help') }}"><i class="fa fa-question-circle"></i> Aide</a></li>
  </ul>
</div>