<div class="navbar">
  <div class="navbar__wrapper">
    <ul class="navbar__list">

      <!-- Navbar content -->
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getBlog') }}">Blog</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getIllustrations') }}">Illustrations</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getPages') }}">Pages</a>
      </li>

      <!-- Connection details -->
      <li class="navbar__item">
        <a class="navbar__link" href="#">{{Auth::guard('administrator')->user()->getFullName()}}</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link fa fa-remove sidebar__icon" href="{{ action('MasterBox\Connect\AdministratorController@getLogout') }}"></a>
      </li>

    </ul>
  </div>
</div>