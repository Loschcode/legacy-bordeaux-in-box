<div class="footer">
  <div class="container">
    <div class="row">
      <div class="grid-4">
        <ul>
          <li><a href="{{ action('MasterBox\Guest\HomeController@getHelp') }}">Foire aux questions</a></li>
          <li><a href="{{ action('MasterBox\Guest\ContactController@getIndex') }}">Nous contacter</a></li>
        </ul>
      </div>
      <div class="grid-4 push-4">
        <ul>
        <li><a href="{{ action('MasterBox\Guest\HomeController@getLegals') }}">Mentions Légales</a></li>
        <li><a href="{{ action('MasterBox\Guest\HomeController@getCgv') }}">Conditions générales de vente</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
