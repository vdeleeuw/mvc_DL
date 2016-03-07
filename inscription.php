<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("Inscription");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller_customer?action=add';
if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Une des saisies est vide ! <br/> <br/> \n";
if(isset($_GET['essai']) && $_GET['essai'] == 'trueEx')
	echo "E-mail deja existant ! <br/> <br/> \n";
?>

<form action="<?php echo $url?>" method="post">
    <label for="cu_first_name">Prenom : </label>
		<input type="text" name="cu_first_name"><br/>
    <label for="cu_last_name">Nom : </label>
        <input type="text" name="cu_last_name"><br/>	
    <label for="cu_email">E-mail : </label>
        <input type="text" name="cu_email"><br/>
    <label for="cu_password">Password : </label>
        <input type="password" name="cu_password"><br/>
	<?php
		if($_SESSION['userLevel'] == 4){
			?> 	 <select class="select_form" name="cu_level">
					<option value="1"> Client </option>
					<option value="4"> Administrateur </option>
				</select>
				<br/>
	<?php } ?>
	<br/>
	<input type="submit" value="Envoyer">
</form>
<br/>

<?php
$document->endSection();
$document->end();
?>
