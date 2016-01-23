<!DOCTYPE HTML>
<html lang="fr-FR">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div>      

      Notre service a constaté une tentative de génération de commande alors qu'il n'y a plus de série disponible pour de nouvelles générations liées au profil N°{{$customer_profile_id}} correspondant à {{$customer_full_name}} ({{$customer_email}}).
        

      <br /><br /><br /> 
      Trace de la transaction : <br /><br />

      {!! nl2br($log_store) !!}
             
    </div>
  </body>
</html>
