<?php
session_start();
require_once('view/document.php');
$document=new Document();
// acces aux utilisateur de niveaux 0 et supérieurs
if (!$document->begin(0)) die();
$document->beginSection("Login");

if(isset($_GET['essai']) && $_GET['essai'] == 'true')
	echo "Login/password incorrect <br/> <br/> \n";
	
$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/controller/controller?action=login_user';

if($_SESSION["userLevel"] != 0 && $_SESSION["userId"] != 0) 
	echo "Vous etes deja connecte(e), deconnectez vous avant ! <br/><br/> \n";
else {
?>

<form action="<?php echo $url?>" method="post">
    <label for="cu_email">E-mail : </label>
              <input type="text" name="cu_email"><br/>
    <label for="cu_password">Password : </label>
              <input type="password" name="cu_password"><br/>
	<br/>
	<input type="submit" value="Login">
</form>
<br/>

<?php 
$url='http://'.$_SERVER['HTTP_HOST'].'/';
echo "<a href=\"$url/controller/controller.php?action=inscription\">S'enregistrer</a><br/> <br/>\n";
}

$document->endSection();
$document->end();
?>
