<?php
session_start();

require_once('view/document.php');
$document=new Document();
if (!$document->begin(1)) die();
$document->beginSection("Passer une commande");

$url='http://'.$_SERVER['HTTP_HOST'];
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$res=$pdo->query("SELECT * FROM product;");

if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Vous n'avez rien choisi ! <br/> <br/> \n";
if(isset($_GET['essai']) && $_GET['essai'] == 'trueId')
	echo "L'id du client n'existe pas ! <br/> <br/> \n";
?>

<form action="<?php echo $url.'/jdvd/controller/controller_command?action=add'?>" method="post">
	<table>
		<tr>
			<th>Id</th>
			<th>Label</th>
			<th>Qte stock</th>
			<th>Prix unitaire</th>
		</tr>
			<?php while($tableau=$res->fetch(PDO::FETCH_NUM)){ ?>
				<tr>
					<td> <?php echo "$tableau[0]"; ?> </td>
					<td> <?php echo "$tableau[1]"; ?> </td>
					<td> <?php echo "$tableau[2]"; ?> </td>
					<td> <?php echo "$tableau[3]"; ?> </td>
					<td class="lien_tab"> <select name="<?php echo "cp_qty_".$tableau[0] ?>">
						<?php for($i=0; $i<=$tableau[2]; $i++){ ?>
								<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
						<?php } ?>
					</select> </td>
				</tr>
			<?php } ?>
	</table>
		<?php if($_SESSION['userLevel'] == 4){ ?>
			<br/>
			<label for="cu_id">Id du client : </label>
				<input type="text" name="cu_id" value="<?php echo $_SESSION['userId']; ?>"><br/>
		<?php } ?>
		<br/>
	<input type="submit" value="Envoyer">
</form>
<br/>

<?php 
$document->endSection();
$document->end();
?>
