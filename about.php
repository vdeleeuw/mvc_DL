<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("About");
?>

<p>
Bienvenue sur le site de notre projet ! <br/>
La realisation fut longue et laborieuse. <br/>
Nous esperons que ce site vous apporte joie et plaisir <br/>
Si vous rencontrez un quelconque probleme, <br/>
Merci de contacter l'un des deux webmasters au plus vite : <br/>
<a href="mailto:valerian.deleeuw@etud.univ-angers.fr?subject=Projet web 2015">valerian.deleeuw@etud.univ-angers.fr</a><br/> 
<a href="mailto:johan.defaye@etud.univ-angers.fr?subject=Projet web 2015">johan.defaye@etud.univ-angers.fr</a><br/> 
</p>

<?php
$document->endSection();
$document->end();
?>
