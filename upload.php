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

	if ($extension_upload != ".jpg" && $extension_upload != ".jpeg"){

		echo("Vous ne pouvez télécharger que des images au format jpeg.")

	}

	else{

		$nom = SITE_ROOT."/img/{$file_name}.{$extension_upload}";

		$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $nom);
		if ($resultat) {
		    echo "Le fichier a été téléchargé
			   avec succès.\n";
		} else {
		    echo "echec\n";
		}

	}
}

?>

Seulement les images au format jpeg sont supportées. 
<form method="post" action="upload.php" enctype="multipart/form-data">
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