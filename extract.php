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

if(isset($_POST['pwd']) && isset($_POST['image']))
{ 
	
	$pwd = $_POST['pwd'];
	$img = $_POST['image'];
	//echo "> $pwd > $img<br/>";

	$command = './script/extract.sh '.$img.' '.$pwd;
	//echo "$command<br/>";
	$output = shell_exec($command);
	//echo "<pre>$output</pre><br/>";
	$output = shell_exec('cat secret.txt');
	echo "<pre>$output</pre><br/>";
}



// AFFICHE TT LES IMG PNG
//$dirname = "img/";
//$images = glob($dirname."*.png");
//displayTemplate($images);

// AFFICHE TT LES IMG JPG
$dirname = "img/";
$images = glob($dirname."*.jpg");
displayTemplate($images);

function displayTemplate($images){
	foreach($images as $image) {
	    echo '<form method="post" action="extract.php">';
	    echo '<label for="pwd">Mot de Passe : </label>';
	    echo '<input type="password" name="pwd">';
	    echo '<input type="hidden" name="image" value="'.$image.'">';
	    echo '<input type="submit" name="submit" value="Go">';
	    echo '</form>';
	    echo '<img src="'.$image.'" /><br />';
	}
}

?>

</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
