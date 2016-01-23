<div class="navbar">
  <div class="navbar__wrapper">
    <ul class="navbar__list">
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getBlog') }}">Blog</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getIllustrations') }}">Illustrations</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ContentController@getPages') }}">Pages</a>
      </li>
    </ul>
  </div>
</div>