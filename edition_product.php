<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(4)) die();
$document->beginSection("Edition d'un produit");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller_product.php?action=update';
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$requete=$pdo->query('SELECT * FROM product WHERE pr_id ='.$pdo->quote($_GET['id'], PDO::PARAM_INT).';');
$record=$requete->fetch(PDO::FETCH_NUM);

if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Une des modifications est vide ! <br/> <br/> \n";
?>

<form action="<?php echo $url?>" method="post">
	<label for="pr_id">Id : </label>
		<input type="text" name="pr_id" value="<?php echo "$record[0]" ?>" readonly><br/>
    <label for="pr_label">Label : </label>
		<input type="text" name="pr_label" value="<?php echo "$record[1]" ?>"><br/>
    <label for="pr_stock_qty">Qte stock : </label>
        <input type="text" name="pr_stock_qty" value="<?php echo "$record[2]" ?>"><br/>	
    <label for="pr_unit_price">Prix unitaire : </label>
        <input type="text" name="pr_unit_price" value="<?php echo "$record[3]" ?>"><br/>
	<br/>
	<input type="submit" value="Envoyer">
</form>
<br/>

<?php
$document->endSection();
$document->end();
?>
