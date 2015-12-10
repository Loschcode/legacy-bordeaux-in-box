<!-- Navigation -->
<div id="subnav" class="navigation">
  <ul>
    <li><a href="{{ url('/#how-to') }}">Comment ça marche ?</a></li>
    <li><a href="{{ url('/#inside') }}">Ce qu'il y a dans la boîte</a></li>

    @if (Auth::check())
      <li><a href="{{ url('profile') }}"><i class="fa fa-user"></i> Mon compte</a></li>

      @if (Auth::user()->role == 'admin')
      
        <li><a href="{{ url('/admin') }}"><i class="fa fa-gear"></i> Administration</a></li>
          
      @endif

    @else 

      <li><a href="{{ url('user/logout') }}"><i class="fa fa-lock"></i> Connexion</a></li>

    @endif

    <li><a href="{{ url('help') }}"><i class="fa fa-question-circle"></i> Aide</a></li>
  </ul>
</div>