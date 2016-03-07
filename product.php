<?php
session_start();

require_once('view/document.php');
$document=new Document();
if (!$document->begin(1)) die();
$document->beginSection("Liste des produits");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$res=$pdo->query("SELECT * FROM product;");

if(isset($_GET['prix']))
	echo "Merci pour votre commande ! Nous attendons votre cheque de ".$_GET['prix']." euros au plus vite. <br/> <br/> \n";

echo "<a href=\"$url/controller/controller_command.php?action=ajout\">Passer une commande</a> \n";
if(isset($_SESSION["userId"]) && isset($_SESSION["userId"]) && $_SESSION["userLevel"] == 4)
	echo " - <a href=\"$url/controller/controller_product.php?action=ajout\">Inserer produit</a>\n";
echo "<br/> <br/> \n";

?>

<table>
	<tr>
		<th>Id</th>
		<th>Label</th>
		<th>Qte stock</th>
		<th>Prix unitaire</th>
	</tr>

<?php

while($tableau=$res->fetch(PDO::FETCH_NUM)){ ?>
	<tr>
		<td> <?php echo "$tableau[0]"; ?> </td>
		<td> <?php echo "$tableau[1]"; ?> </td>
		<td> <?php echo "$tableau[2]"; ?> </td>
		<td> <?php echo "$tableau[3]"; ?> </td>
		<?php if(isset($_SESSION["userId"]) && isset($_SESSION["userId"]) && $_SESSION["userLevel"] == 4){ ?>
			<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_product.php?action=edition&id=$tableau[0]\">Edit.</a><br/> \n"; ?> </td>
			<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_product.php?action=suppression&id=$tableau[0]\">Suppr.</a><br/> \n"; ?> </td>
		<?php } ?>
	</tr>
<?php }

echo "</table> \n";
echo "<br/> \n" ;

$document->endSection();
$document->end();
?>
