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
}

?>

</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
