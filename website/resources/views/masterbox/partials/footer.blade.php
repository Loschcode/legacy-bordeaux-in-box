<div id="footer-wrapper">
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="grid-4">
          <ul>
            <li><a target="_blank" href="https://www.facebook.com/BordeauxinBox/"><i class="fa fa-facebook-official"></i> Facebook</a></li>
            <li><a target="_blank" href="https://www.instagram.com/bordeauxinbox/"><i class="fa fa-instagram"></i> Instagram</a></li>
            <li></li>
          </ul>
        </div>
        <div class="grid-4">
          <ul>
            <li><a href="{{ action('MasterBox\Guest\HomeController@getHelp') }}">Besoin d'aide ?</a></li>
            <li><a href="{{ action('MasterBox\Guest\ContactController@getIndex') }}">Nous contacter</a></li>
          </ul>
        </div>
        <div class="grid-4">
          <ul>
          <li><a href="{{ action('MasterBox\Guest\HomeController@getLegals') }}">Mentions Légales</a></li>
          <li><a href="{{ action('MasterBox\Guest\HomeController@getCgv') }}">Conditions générales de vente</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>