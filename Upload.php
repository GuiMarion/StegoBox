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

if(isset($_FILES['image']))
{ 
	$file_name = $_FILES['image']['name'];
	$extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
	//$nom = "/var/www/html/img//{$id_membre}.{$extension_upload}";
	$nom = SITE_ROOT."/img/{$file_name}.{$extension_upload}";

	$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $nom);
	if ($resultat) {
	    echo "Le fichier a été téléchargé
		   avec succès.\n";
	} else {
	    echo "echec\n";
	}
}

?>
<form method="post" action="index.php" enctype="multipart/form-data">
	<input type="file" name="image" />

	<!-- MAX_FILE_SIZE doit précéder le champ input de type file -->
  <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
  
<input type="submit" name="submit" value="Envoyer" />
</form>
</center>

    <!-- / content body -->
 </div>
</div>
</body>
</html>

<!-- Localized -->
