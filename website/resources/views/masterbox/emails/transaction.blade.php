<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>

			Bonjour {{$first_name}},<br /><br />

			Une transaction bancaire d'un montant de {{$amount}} euro vient d'être effectuée sur Bordeaux in Box. Pour plus de détails n'hésite pas à visiter<br /><br />

			 <a href="{{action('MasterBox\Customer\ProfileController@getIndex')}}#abonnements">https://www.bordeauxinbox.fr/profile#abonnements</a><br /><br />

			L'équipe Bordeaux in Box :)<br /><br />

			-------<br />
			 NOTE : Veuillez à ne pas répondre à ce message. Pour nous contacter envoyez un email à <a href="mailto:jeremie@bordeauxinbox.com">jeremie@bordeauxinbox.com</a>

		</div>
	</body>
</html>

