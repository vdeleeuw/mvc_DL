<?php
session_start();

require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("Logout");
?>

<p>
Vous vous etes bien deconnecte(e).<br/>
</p>

<?php
$document->endSection();
$document->end();
?>
