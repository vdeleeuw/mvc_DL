<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("Inscription");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller_product?action=add';
if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Une des saisies est vide ! <br/> <br/> \n";
?>

<form action="<?php echo $url?>" method="post">
    <label for="pr_label">Label : </label>
		<input type="text" name="pr_label"><br/>
    <label for="pr_stock_qty">Qte Stock : </label>
        <input type="text" name="pr_stock_qty"><br/>	
    <label for="pr_unit_price">Prix unitaire : </label>
        <input type="text" name="pr_unit_price"><br/>
	<br/>
	<input type="submit" value="Oui">
</form>
<br/>

<?php
$document->endSection();
$document->end();
?>
