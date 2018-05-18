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
echo "Prout";

$uploaddir = '/var/www/html/img';
$uploadfile = $uploaddir . basename($_FILES['image']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
    echo "Le fichier est valide, et a été téléchargé
           avec succès.\n";
} else {
    echo "echec:\n";
}

?>
<form method="post" action="index.php" enctype="multipart/form-data">
	<input type="file" name="image" />

	<!-- MAX_FILE_SIZE doit précéder le champ input de type file -->
  <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
  
<input type="submit" name="submit" value="Envoyer" />
</form>

<img src="img/image.jpg" >
</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
