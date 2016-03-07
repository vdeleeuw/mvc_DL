<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(4)) die();
$document->beginSection("Edition d'un client");

$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller_customer.php?action=update';
$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
$requete=$pdo->query('SELECT * FROM customer WHERE cu_id ='.$pdo->quote($_GET['id'], PDO::PARAM_INT).';');
$record=$requete->fetch(PDO::FETCH_NUM);

if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Une des modifications est vide ! <br/> <br/> \n";
?>

<form action="<?php echo $url?>" method="post">
	<label for="cu_id">Id : </label>
		<input type="text" name="cu_id" value="<?php echo "$record[0]" ?>" readonly><br/>
    <label for="cu_first_name">Prenom : </label>
		<input type="text" name="cu_first_name" value="<?php echo "$record[1]" ?>"><br/>
    <label for="cu_last_name">Nom : </label>
        <input type="text" name="cu_last_name" value="<?php echo "$record[2]" ?>"><br/>	
    <label for="cu_email">E-mail : </label>
        <input type="text" name="cu_email" value="<?php echo "$record[3]" ?>"><br/>
    <label for="cu_password">Password : </label>
        <input type="text" name="cu_password" value="<?php echo "$record[4]" ?>"><br/>
    <label for="cu_level">Niveau : </label>
        <input type="text" name="cu_level" value="<?php echo "$record[5]" ?>"><br/>
	<br/>
	<input type="submit" value="Envoyer">
</form>
<br/>

<?php
$document->endSection();
$document->end();
?>
