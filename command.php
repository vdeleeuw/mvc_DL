<?php
session_start();

require_once('view/document.php');
$document=new Document();
if (!$document->begin(4)) die();
$document->beginSection("Liste des commandes");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$res=$pdo->query("SELECT * FROM command;");

echo "<a href=\"$url/controller/controller_command.php?action=ajout\">Inserer une commande</a> <br/> <br/> \n";
?>

<table>
	<tr>
		<th>Id</th>
		<th>Date</th>
		<th>Id client</th>
		<th>Prix total</th>
		<th>Nb prod diff</th>
	</tr>

<?php
while($tableau=$res->fetch(PDO::FETCH_NUM)){ ?>
	<tr>
		<td> <?php echo "$tableau[0]"; ?> </td>
		<td> <?php echo "$tableau[1]"; ?> </td>
		<td> <?php echo "$tableau[2]"; ?> </td>
		<td> <?php echo "$tableau[3]"; ?> </td>
		<td> <?php echo "$tableau[4]"; ?> </td>
		<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_command.php?action=edition&id=$tableau[0]\">Edit.</a><br/> \n"; ?> </td>
		<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_command.php?action=suppression&id=$tableau[0]\">Suppr.</a><br/> \n"; ?> </td>
	</tr>
<?php }

echo "</table> \n";
echo "<br/> \n" ;
$res=$pdo->query("SELECT * FROM commprod;");
?>

<h2>Detail des commandes</h2>
<table>
	<tr>
		<th>Id</th>
		<th>Id commande</th>
		<th>Id produit</th>
		<th>Quantite produit</th>
	</tr>
	
<?php
while($tableau=$res->fetch(PDO::FETCH_NUM)){ ?>
	<tr>
		<td> <?php echo "$tableau[0]"; ?> </td>
		<td> <?php echo "$tableau[1]"; ?> </td>
		<td> <?php echo "$tableau[2]"; ?> </td>
		<td> <?php echo "$tableau[3]"; ?> </td>
	</tr>
<?php } ?>

</table>
<br/>

<p>
Nous vous recommandons de supprimer une commande et d'en refaire une pour le client si vous devez changer une partie seulement de celle-ci.<br/>
Le mieux est encore de l'annuler pour permettre au client de la refaire a sa convenance.
</p>

<?php
$document->endSection();
$document->end();
?>
