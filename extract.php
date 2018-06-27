<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>StegoBox</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="styles/layout.css" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--[if lt IE 9]><script src="scripts/html5shiv.js"></script><![endif]-->

</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">StagoBox</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="upload.php">Upload</a></li>
      <li><a href="append.php">Append</a></li>
      <li class = "active"><a href="extract.php">Extract</a></li>
      <li><a href="view.php">View</a></li>


    </ul>
  </div>
</nav>
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
		echo '<div>';
	    echo '<form method="post" action="extract.php">';
	    echo '<label for="pwd">Mot de Passe : </label>';
	    echo '<input type="password" name="pwd">';
	    echo '<input type="hidden" name="image" value="'.$image.'">';
	    echo '<input type="submit" name="submit" value="Go">';
	    echo '</form>';
	    echo '<img src="'.$image.'" width="90" height="90"/><br />';
	    echo '</div>';
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
