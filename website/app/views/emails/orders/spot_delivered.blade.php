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

			 pour la série du {{$series_date}} vient d'être livrée à notre point relais partenaire.<br /><br />

			Rendez-vous dès que possible à {{$spot_name_and_infos}}<br /><br />

			Horaires : {{$spot_schedule}}<br/><br/>

			Plus d'infos : <a href="https://www.bordeauxinbox.fr/profile#abonnements">https://www.bordeauxinbox.fr/profile#abonnements</a><br /><br />

			L'équipe Bordeaux in Box :)<br /><br />

			-------<br />
			NOTE : Veuillez à ne pas répondre à ce message. Pour nous contacter envoyez un email à <a href="mailto:jeremie@bordeauxinbox.com">jeremie@bordeauxinbox.com</a>

		</div>
	</body>
</html>

