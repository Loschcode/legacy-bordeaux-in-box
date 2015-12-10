<?php
	
	if (isset($_GET['firstname']))
	{
		echo $_GET['firstname'];
	}
	else
	{
		echo 'Firstname doesn\'t exists';
	}
?>
<html>
<head>
	<title>Hello</title>

</head>
<body>
	<h1 class="title-red">Title</h1>
	<h3>Sub title</h3>
	Some text <br/>
	<a href="http://www.google.fr/"><?php echo $text ?></a>

	<form method="GET" action="">
	<input type="text" name="firstname" /> <input type="submit" value="send" /> 
	</form>
</body>
</html>