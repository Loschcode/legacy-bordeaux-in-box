<li class="navbar__item"><a class="navbar__link" href="#" data-jq-dropdown="#logs"><i class="fa fa-tasks"></i> Logs <i class="fa fa-angle-down"></i></a></li>
<li class="navbar__item"><a class="navbar__link" href="#" data-jq-dropdown="#configuration"><i class="fa fa-gear"></i> Configuration <i class="fa fa-angle-down"></i></a></li>

<div id="logs" class="jq-dropdown jq-dropdown-tip">
  <ul class="jq-dropdown-menu">
    <li><a href="{{ action('MasterBox\Admin\LogsController@getIndex') }}">Prises de contact</a></li>
    <li><a href="{{ action('MasterBox\Admin\LogsController@getEmailTraces') }}">Traces des emails</a></li>
    <li><a href="{{ action('MasterBox\Admin\LogsController@getProfileNotes') }}">Notes des abonnements</a></li>

  </ul>
</div>

<div id="configuration" class="jq-dropdown jq-dropdown-tip">
  <ul class="jq-dropdown-menu">
    <li><a href="{{ action('MasterBox\Admin\LogsController@getEditSettings') }}">Emails Transactionnels</a></li>
  </ul>
</div>