<!DOCTYPE HTML>
<html lang="fr-FR">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div>

      Bonjour {{$first_name}},<br /><br />

      Ton abonnement à Bordeaux in Box vient d'arriver à son terme :(<br /><br />

      @if ($last_box_was_sent === TRUE)
      Tu vas donc recevoir ta dernière box d'ici les prochains jours.<br /><br />
      @endif

      Nous somme heureux de t'avoir comblé avec ces petites boxes mensuelles et espérons te revoir très bientôt sur Bordeaux in Box !<br /><br />

       <a href="{{action('MasterBox\Customer\ProfileController@getIndex')}}#abonnements">https://www.bordeauxinbox.fr/profile#abonnements</a><br /><br />

      L'équipe Bordeaux in Box :)<br /><br /> 

      -------<br />
       NOTE : Veuillez à ne pas répondre à ce message. Pour nous contacter envoyez un email à <a href="mailto:bonjour@bordeauxinbox.com">bonjour@boreauxinbox.com</a>

    </div>
  </body>
</html>

