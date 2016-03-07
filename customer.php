<?php
session_start();

require_once('view/document.php');
$document=new Document();
if (!$document->begin(4)) die();
$document->beginSection("Liste des clients");

$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$res=$pdo->query("SELECT * FROM customer;");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
echo "<a href=\"$url/controller/controller_customer.php?action=inscription\">Inserer client</a><br/> <br/>\n";
?>

<table>
	<tr>
		<th>Id</th>
		<th>Prenom</th>
		<th>Nom</th>
		<th>E-mail</th>
		<th>Mot de passe</th>
		<th>Level</th>
	</tr>

<?php

while($tableau=$res->fetch(PDO::FETCH_NUM)){ ?>
	<tr>
		<td> <?php echo "$tableau[0]"; ?> </td>
		<td> <?php echo "$tableau[1]"; ?> </td>
		<td> <?php echo "$tableau[2]"; ?> </td>
		<td> <?php echo "$tableau[3]"; ?> </td>
		<td> <?php echo "$tableau[4]"; ?> </td>
		<td> <?php echo "$tableau[5]"; ?> </td>
		<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_customer.php?action=edition&id=$tableau[0]\">Edit.</a><br/> \n"; ?> </td>
		<td class="lien_tab"> <?php echo "<a href=\"$url/controller/controller_customer.php?action=suppression&id=$tableau[0]\">Suppr.</a><br/> \n"; ?> </td>
	</tr>
<?php }

echo "</table> \n";
echo "<br/> \n" ;

$document->endSection();
$document->end();
?>
