<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>StegoBox</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="styles/layout.css" type="text/css">
<!--[if lt IE 9]><script src="scripts/html5shiv.js"></script><![endif]-->
</head>
<body>

<!-- content -->
<div class="wrapper row2">
        
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<center>
<?php

	// ON RECUP LES PARAMS ET ON LANCE LE SCRIPT
	if(isset($_POST['img']) && isset($_POST['msg']) && isset($_POST['pass'])){
		$img = $_POST['img'];
		$msg = $_POST['msg'];
		$pass = $_POST['pass'];
		echo "$img $msg $pass<br/>";

		$command = './script/append.sh '.$msg.' '.$img.' '.$pass;
		echo "$command<br/>";
		$output = shell_exec($command);
		echo "<pre>$output</pre><br/>";
	}

	// AFFICHE TT LES IMG PNG
	$dirname = "img/";
	$images = glob($dirname."*.png");
	displaytemplate($images);

	// AFFICHE TT LES IMG JPG
	$dirname = "img/";
	$images = glob($dirname."*.jpg");
	displaytemplate($images);


	function displayTemplate($images) {
		foreach($images as $image) {
			echo '<img src="'.$image.'" />';
			echo '<form method="post" action="append.php">';
			echo '<label for="msg">Message : </label>';
			echo '<input type="text" placeholder="Entrez le message" id="msg" name="msg">';
			echo '<label for="pass">Mot de passe : </label>';
			echo '<input type="password" id="pass" name="pass">';
			echo '<input type="hidden" value="'.$image.'" name="img">';
			echo '<button type="submit">GO</button>';
			echo '</form>';
			echo '<br/>';
		}
	}

?>
    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
