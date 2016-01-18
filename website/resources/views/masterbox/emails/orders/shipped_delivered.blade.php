<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>

			Bonjour {{$first_name}},<br /><br />

			Ta box {{$box_title}} 

			@if ($gift)

			 (à offrir)

			@endif
			
			 pour la série du {{$series_date}} est en cours de livraison en ce moment même et ne devrait pas tarder à arriver ! <br /><br />

			@if (($gift) && ($billing_address))
			Adresse de facturation : {{$billing_address}}<br /><br />
			@endif

			@if ($destination_address)
			Adresse de livraison : {{$destination_address}}<br /><br />
			@endif

			Plus d'infos : <a href="https://www.bordeauxinbox.fr/profile#abonnements">https://www.bordeauxinbox.fr/profile#abonnements</a><br /><br />

			L'équipe Bordeaux in Box :)<br /><br />

			-------<br />
			NOTE : Veuillez à ne pas répondre à ce message. Pour nous contacter envoyez un email à <a href="mailto:bonjour@bordeauxinbox.com">bonjour@boreauxinbox.com</a>

		</div>
	</body>
</html>

