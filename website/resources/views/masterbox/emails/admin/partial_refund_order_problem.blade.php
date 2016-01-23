<!DOCTYPE HTML>
<html lang="fr-FR">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div>      

      Notre service a constaté le remboursement partiel d'une commande. A l'heure actuelle, les commandes doivent être remboursées intégralement ou pas du tout. Veuillez vérifier et corriger la transaction. Celle-ci est liée au profil N°{{$customer_profile_id}} correspondant à {{$customer_full_name}} ({{$customer_email}}).
           

      <br /><br /><br /> 
      Trace de la transaction : <br /><br />

      {!! nl2br($log_store) !!}
             
    </div>
  </body>
</html>
