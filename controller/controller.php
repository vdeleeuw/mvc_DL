<?php
session_start();

Class controller{
	//variables
	protected $action;
	protected $destination;
	
	public function __construct(){
		$this->action="";
		$this->destination="index.php";
	}
	
	public function login_user(){
		//test si bien defini
		if (isset($_POST['cu_email']) && isset($_POST['cu_password'])){
			
			//connection
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			
			//execution requete
			$requete=$pdo->query('SELECT * FROM customer WHERE cu_email ='.$pdo->quote($_POST['cu_email'], PDO::PARAM_STR).';');
			$record=$requete->fetch(PDO::FETCH_NUM);
			$pw_table = $record[4];

			//test de connection
			if (($pw_table == md5($_POST['cu_password'])) && isset($pw_table) && $pw_table != ''){
				
				//ouverture de session
				$_SESSION["userId"] = $record[0];
				$_SESSION["userLevel"] = $record[5];
				$this->destination='index.php';
			} else 
				$this->destination='login.php?essai=true';
		} else 
			$this->destination='login.php?essai=true';
	}
	
	public function logout_user(){
		//fermeture de session
		$_SESSION["userId"] = 0;
		$_SESSION["userLevel"] = 0;
		$this->destination="logout.php";
	}
	
	public function process(){
		$this->action=$_GET['action'];
	
		//connection
		if($this->action=="login")
			$this->destination="login.php";
		else if($this->action=="login_user")
			$this->login_user();
	
		//deconnection
		else if($this->action=="logout"){
			$this->logout_user();		
			$this->destination="logout.php";
		}
		
		//index
		else if($this->action=="index")
			$this->destination="index.php";
			
		//about
		else if($this->action=="about")
			$this->destination="about.php";
			
		//client
		else if($this->action=="customer")
			$this->destination="customer.php";
			
		//produit
		else if($this->action=="product")
			$this->destination="product.php";
			
		//commande
		else if($this->action=="command")
			$this->destination="command.php";
			
		//inscription
		else if($this->action=="inscription")
			$this->destination="inscription.php";
			
		//autre
		else $this->destination="index.php";
		
		//redirection	
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/';
		$url=$url.$this->destination;
		header('Location:'.$url);
	}
}

$ctr=new controller();
$ctr->process();

?>
