<div class="sidebar">
  <ul class="sidebar__list">
    <li class="sidebar__item"><a class="sidebar__link {{ Html::cssLinkProfileMenuActive('account', $active_menu) }}" href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}">Mon compte</a></li>
    <li class="sidebar__item"><a class="sidebar__link {{ Html::cssLinkProfileMenuActive('orders', $active_menu) }}" href="{{ action('MasterBox\Customer\ProfileController@getOrders') }}">Abonnements</a></li>
    <li class="sidebar__item"><a class="sidebar__link" href="{{ action('MasterBox\Guest\ContactController@getIndex') }}">Contact</a></li>
    <li class="sidebar__item --last"><a class="sidebar__link" href="{{ action('MasterBox\Connect\CustomerController@getLogout') }}">Déconnexion</a></li>
  </ul>
</div>