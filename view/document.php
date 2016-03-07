<?php

class Document{ 
	
	//variables
	private $userId; 
	private $userLevel;
	private $accessLevel;
	
	//methodes
	public function __construct($css="",$dojoRequire="",$meta=""){
		$this->userId=0;
		$this->userLevel=0;
		$this->accessLevel=0;
		
		foreach($_POST as $key => $value) {
			if (substr($key,0,8)=="session_") {
				$_SESSION[substr($key,8)]=$value;
			}
		}

		if (isset($_SESSION["userId"])) {
			$this->userId=$_SESSION["userId"];
		}
		if (isset($_SESSION["userLevel"])) {
			$this->userLevel=$_SESSION["userLevel"];
		}

		$this->htmlHeader($css,$dojoRequire,$meta);
	}

	
	public function begin($level=0) {
		if (is_string($level)) {
			$backup=$level;
			$level=0;
			foreach($this->levels as $key => $item) {
				if ($item==$backup) {
					$level=$key;
					break;
				}
			}
		}
		$this->accessLevel=$level;
		$this->header();
		if (!$this->menu()) {
			$this->notAllowed();
			$this->end();
			return false;
		} 
		return true;
	}
	
	private function htmlHeader($css="",$dojoRequire="",$meta=""){
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
		echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
		echo "<head> \n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/> \n";
		echo $meta;
		echo "<meta name=\"Author\" content=\"Defaye Johan Valerian De Leeuw\" /> \n";
		echo "<meta name=\"description\" content=\"site web projet TP\" /> \n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url/css/global.css\" /> \n";
		echo $css;
		echo "</head> \n";
	}
		
	protected function header(){
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
		echo "<body>\n";
		echo "<div id=\"bandeau-site\">\n";
		echo "<p id=\"titre-bandeau\">JD & VD Online\n";
		if ($this->userId != 0)
			echo "<a href=\"$url/controller/controller.php?action=logout\"><button name=\"LOGOUT\">LOGOUT</button></a>\n";
		else 
			echo "<a href=\"$url/controller/controller.php?action=login\"><button name=\"LOGIN\">LOGIN</button></a>\n";
		echo "<a href=\"$url/controller/controller.php?action=index\"><button name=\"HOME\">HOME</button></a>\n";
		echo "<a href=\"$url/controller/controller.php?action=about\"><button name=\"ABOUT\">ABOUT</button></a>\n";
		echo "</p>\n";
		echo "</div>\n";
	}
	
	public function menu(){
		echo "<div id=\"site\">\n";
		echo "<div id=\"menu_contextuel\">\n";
		echo "<h3>MENU</h3>\n";
		if ($this->userLevel < $this->accessLevel) {
		} else {
			$menuFunction="menuLevel".strtolower($this->userLevel);
			$this->$menuFunction();
		}
		echo "</div>\n";
		return ($this->userLevel >= $this->accessLevel);
	}
	
	public function beginSection($title){
		echo "<title>$title</title> \n"; 
		echo "<div id=\"sujet\">\n";
		echo "<h2>$title</h2>\n";
	}
	
	public function endSection(){
		echo "</div> \n";
		echo "</div> \n";
	}
	
	public function end(){
		echo "<div id=\"footer-site\"> \n";
		echo "<p>&copy; 2015 Defaye Johan &amp; De Leeuw Val&eacute;rian</p>\n";
		echo "<p><a href=\"http://validator.w3.org/check?uri=referer\">Valid XHTML</a> \n";
		echo "<a href=\"http://jigsaw.w3.org/css-validator/\">Valid CSS</a></p> \n";
		echo "</div> \n";
		echo "</body> \n";
		echo "</html> \n";
	}

	public function notAllowed() {
		echo "<title>Erreur</title> \n"; 
		echo "<div id=\"sujet\">\n";
		echo "<h2>Erreur !</h2>\n";
		echo "<p>Vous n'avez pas les droits suffisants pour voir cette page!</p>";
		echo "</div> \n";
		echo "</div> \n";
	}

	protected function menuLevel0() {
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
		echo "<p> Connectez vous ! <br/> <br/> \n";
		echo "<a href=\"$url/controller/controller.php?action=login\">Login</a></p>\n";
	}
	
	protected function menuLevel1() {
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
		echo "<p> Bonjour Client ! <br/> <br/> \n";
		echo "<a href=\"$url/controller/controller.php?action=product\">Liste des produits</a><br/> <br/>\n";
		echo "<a href=\"$url/controller/controller.php?action=logout\">Logout</a></p>\n";
	}
	
	protected function menuLevel4() {
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd';
		echo "<p> Bonjour Administrateur ! <br/> <br/> \n";
		echo "<a href=\"$url/controller/controller.php?action=product\">Interface des produits</a><br/>\n";
		echo "<a href=\"$url/controller/controller.php?action=customer\">Interface des clients</a><br/>\n";
		echo "<a href=\"$url/controller/controller.php?action=command\">Interface des commandes</a> <br/> <br/>\n";
		echo "<a href=\"$url/controller/controller.php?action=logout\">Logout</a></p>\n";
	}

}

?>
