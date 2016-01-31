<div class="navbar">
  <div class="navbar__wrapper">
    <ul class="navbar__list">

      <!-- Navbar content -->
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id]) }}">Résumé</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ProfilesController@getDeliveries', ['id' => $profile->id]) }}">Livraisons</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ProfilesController@getPayments', ['id' => $profile->id]) }}">Historique de paiements</a>
      </li>
      <li class="navbar__item">
        <a class="navbar__link" href="{{ action('MasterBox\Admin\ProfilesController@getQuestions', ['id' => $profile->id]) }}">Questionnaire</a>
      </li>
    </ul>
  </div>
</div>