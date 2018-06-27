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
      <a class="navbar-brand" href="#">StagoBox</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="upload.php">Upload</a></li>
      <li><a href="append.php">Append</a></li>
      <li><a href="extract.php">Extract</a></li>
      <li class = "active"><a href="view.php">View</a></li>


    </ul>
  </div>
</nav>

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
    echo '<div width="90" height="90">';
    echo '<img src="'.$image.'" /><br />';
    echo '</div>;
}

?>

</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
