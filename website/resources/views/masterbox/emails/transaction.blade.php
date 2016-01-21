<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>

			Bonjour {{$first_name}},<br /><br />

      @if ($paid)

			Une transaction bancaire d'un montant de {{$amount}} euros vient d'être effectuée sur Bordeaux in Box. Pour plus de détails n'hésite pas à visiter<br /><br />

      @else

      Nous avons tenté d'effectuer une transaction de {{$amount}} euros depuis ton compte, mais sans succès. Il se peut que la carte bancaire enregistrée soit invalide, ou que son plafond ait déjà été dépassé. Si le problème persiste, merci d'éditer tes informations bancaires depuis ton compte ;)

      @endif

			 <a href="{{action('MasterBox\Customer\ProfileController@getIndex')}}#abonnements">https://www.bordeauxinbox.fr/profile#abonnements</a><br /><br />

			L'équipe Bordeaux in Box :)<br /><br />

			-------<br />
			 NOTE : Veuillez à ne pas répondre à ce message. Pour nous contacter envoyez un email à <a href="mailto:bonjour@bordeauxinbox.com">bonjour@boreauxinbox.com</a>

		</div>
	</body>
</html>

