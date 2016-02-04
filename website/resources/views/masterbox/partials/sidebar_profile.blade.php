<div class="js-menu-sidebar hide@xs sidebar">
  <ul class="sidebar__list">
    <li class="sidebar__item"><a class="sidebar__link {{ Html::cssLinkProfileMenuActive('account', $active_menu) }}" href="{{ action('MasterBox\Customer\ProfileController@getIndex') }}"><i class="fa fa-cog sidebar__icon"></i> Mon compte</a></li>
    <li class="sidebar__item"><a class="sidebar__link {{ Html::cssLinkProfileMenuActive('orders', $active_menu) }}" href="{{ action('MasterBox\Customer\ProfileController@getOrders') }}"><i class="fa fa-shopping-bag sidebar__icon"></i> Abonnements</a></li>
    <li class="sidebar__item"><a class="sidebar__link {{ Html::cssLinkProfileMenuActive('contact', $active_menu) }}" href="{{ action('MasterBox\Customer\ProfileController@getContact') }}"><i class="fa fa-envelope-o sidebar__icon"></i> Contact</a></li>
    <li class="sidebar__item --last"><a class="sidebar__link" href="{{ action('MasterBox\Connect\CustomerController@getLogout') }}"><i class="fa fa-lock sidebar__icon"></i> Déconnexion</a></li>
  </ul>
</div>