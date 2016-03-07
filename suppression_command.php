<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 4 et supérieurs
if (!$document->begin(4)) die();
$document->beginSection("Suppression d'une commande");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller_command.php?action=delete';
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$requete=$pdo->query('SELECT * FROM command WHERE co_id ='.$pdo->quote($_GET['id'], PDO::PARAM_INT).';');
$record=$requete->fetch(PDO::FETCH_NUM);
?>

<p>
Etes vous sur de vouloir supprimer la commande suivante :
</p>

<form action="<?php echo $url?>" method="post">
	<label for="co_id">Id : </label>
		<input type="text" name="co_id" value="<?php echo "$record[0]" ?>" readonly><br/>
    <label for="co_date">Date : </label>
		<input type="text" name="co_date" value="<?php echo "$record[1]" ?>" readonly><br/>
    <label for="co_cu_id">Id du client : </label>
        <input type="text" name="co_cu_id" value="<?php echo "$record[2]" ?>" readonly><br/>	
    <label for="co_total_price">Prix total : </label>
        <input type="text" name="co_total_price" value="<?php echo "$record[3]" ?>" readonly><br/>
    <label for="co_nb_cmdlines">Nb prod. diff. : </label>
        <input type="text" name="co_nb_cmdlines" value="<?php echo "$record[4]" ?>" readonly><br/>
	<select class="select_form" name="co_retour">
		<option value="true"> Remise en stock </option>
		<option value="false"> Non remis en stock </option>
	</select>
	<br/>

<?php
$requete=$pdo->query("SELECT * FROM commprod WHERE cp_co_id =".$pdo->quote($record[0], PDO::PARAM_INT).";");
$tableau=$requete->fetch(PDO::FETCH_NUM);
?>

<h2>Detail de la commande</h2>
<table>
	<tr>
		<th>Id</th>
		<th>Id commande</th>
		<th>Id produit</th>
		<th>Quantite produit</th>
	</tr>
	
<?php
do{ ?>
	<tr>
		<td> <?php echo "$tableau[0]"; ?> </td>
		<td> <?php echo "$tableau[1]"; ?> </td>
		<td> <?php echo "$tableau[2]"; ?> </td>
		<td> <?php echo "$tableau[3]"; ?> </td>
	</tr>
<?php }while($tableau=$requete->fetch(PDO::FETCH_NUM));
echo "</table> \n";
echo "<br/> \n" ;
?>

<input type="submit" value="Oui">
</form>
<br/>

<?php
$document->endSection();
$document->end();
?>
