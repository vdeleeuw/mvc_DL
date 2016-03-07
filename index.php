<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("Accueil");
?>

<p>
Bienvenue sur notre site de projet ! <br/>

<?php
if ($_SESSION['userId'] == 0)
	echo "Vous pouvez vous authentifier ou vous inscrire pour avoir plus d'options.";
else
	echo "Vous pouvez utiliser le menu a gauche pour naviguer.";
?>

</p>

<?php
$document->endSection();
$document->end();
?>
