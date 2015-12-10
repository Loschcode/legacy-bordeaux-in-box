<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			Bonjour,<br /><br />

			Pour réintialiser ton mot de passe, il te suffit de cliquer sur le lien suivant<br /><br />

			<strong><a href="{{ url('user-password/reset/' . $token) }}">{{ url('user-password/reset/' . $token) }}</a></strong>
		</div>
	</body>
</html>

