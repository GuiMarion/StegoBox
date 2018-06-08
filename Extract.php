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
define ('SITE_ROOT', realpath(dirname(__FILE__)));

echo "Prout";

if(isset($_POST['pwd']))
{ 
	
	$pwd = $_POST['pwd'];
	$img = $_POST['image'];

	$command = './script/extract.sh '.$img.' '.$pwd;
	echo "$command<br/>";
	$output = shell_exec($command);
	echo "<pre>$output</pre><br/>";
}



// AFFICHE TT LES IMG PNG
$dirname = "img/";
$images = glob($dirname."*.png");

foreach($images as $image) {
    echo '<img src="'.$image.'" /><br />';
}
// AFFICHE TT LES IMG JPG
$dirname = "img/";
$images = glob($dirname."*.jpg");

foreach($images as $image) {
    echo '<img src="'.$image.'" /><br />';
    echo '<form method="post">'
    echo '<input type="password" name="pwd">'
    echo '<input type="hidden" name="image" value="$image">'
    echo '<input type="submit" name="submit" value="Go">'
    echo '<img src="'.$image.'" /><br />';
}

?>
<!-- <form method="post" action="index.php" enctype="multipart/form-data">
	<input type="password" name="pwd" />
	<img src="img/image.jpg" >
	<input type="submit" name="submit" value="Go" />
</form> -->

</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
