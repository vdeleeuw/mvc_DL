<?php
session_start();

Class controller_customer{
	//variables
	protected $action;
	protected $destination;
	
	public function add_customer(){
		//test si bien defini
		if(isset($_POST['cu_email']) && isset($_POST['cu_password']) && isset($_POST['cu_first_name']) && isset($_POST['cu_last_name'])
			&& $_POST['cu_email'] != '' && $_POST['cu_password'] != '' && $_POST['cu_first_name'] != '' && $_POST['cu_last_name'] != ''){
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			
			//test si email existe deja
			$requete = $pdo->prepare("SELECT cu_id FROM customer WHERE cu_email = :mail");
			$requete->bindParam(':mail', $_POST['cu_email'], PDO::PARAM_STR);
			$requete->execute();
			if($requete->rowCount() == 0){
				//ajout utilisateur
				$requete=$pdo->prepare("INSERT INTO customer (cu_first_name, cu_last_name, cu_email, cu_password, cu_level) VALUES (:fn, :ln, :em, :pw, :lvl);");
				
				//param requete
				$firstname=$_POST['cu_first_name'];
				$lastname=$_POST['cu_last_name'];
				$email=$_POST['cu_email'];
				$password=md5($_POST['cu_password']);
				$level=1;	
			
				//construction requete
				$requete->bindParam(':fn',$firstname,PDO::PARAM_STR);
				$requete->bindParam(':ln',$lastname,PDO::PARAM_STR);
				$requete->bindParam(':em',$email,PDO::PARAM_STR);
				$requete->bindParam(':pw',$password,PDO::PARAM_STR);
				
				//test si admin
				if(isset($_SESSION["userId"]) && isset($_SESSION["userId"]) && $_SESSION["userLevel"] == 4){
					$this->destination="customer.php";
					if(isset($_POST['cu_level']) && $_POST['cu_level'] != '')
						$level=$_POST['cu_level'];
				}else
					$this->destination="login.php";
				
				//execution requete
				$requete->bindParam(':lvl',$level,PDO::PARAM_INT);
				$requete->execute();
			}else
				$this->destination="inscription.php?essai=trueEx";	
		}else
			$this->destination="inscription.php?essai=true";	
	}
	
	public function update_customer(){
		//test si bien défini
		if(isset($_POST['cu_email']) && isset($_POST['cu_password']) && isset($_POST['cu_first_name']) && isset($_POST['cu_last_name']) && isset($_POST['cu_level']) && isset($_POST['cu_id']) 
			&& $_POST['cu_email'] != '' && $_POST['cu_password'] != '' && $_POST['cu_first_name'] != '' && $_POST['cu_last_name'] != '' && $_POST['cu_level'] != '' && $_POST['cu_id'] != ''){		
		
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			$requete=$pdo->prepare("UPDATE customer SET cu_first_name = :fn, cu_last_name = :ln, cu_email = :em, cu_password = :pw, cu_level = :lvl WHERE cu_id = :id");
		
			//param requete
			$firstname=$_POST['cu_first_name'];
			$lastname=$_POST['cu_last_name'];
			$email=$_POST['cu_email'];
			$password=$_POST['cu_password'];
			$level=$_POST['cu_level'];
			$id=$_POST['cu_id'];
			
			//construction requete
			$requete->bindParam(':fn',$firstname,PDO::PARAM_STR);
			$requete->bindParam(':ln',$lastname,PDO::PARAM_STR);
			$requete->bindParam(':em',$email,PDO::PARAM_STR);
			$requete->bindParam(':pw',$password,PDO::PARAM_STR);
			$requete->bindParam(':lvl',$level,PDO::PARAM_INT);
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
			$this->destination="customer.php";	
		}else
			$this->destination="edition_customer.php?essai=true&id=".$_POST['cu_id'];
	}
	
	public function delete_customer(){
		//test si bien défini
		if(isset($_POST['cu_id']) && $_POST['cu_id'] != ''){
			
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			$requete=$pdo->prepare("DELETE FROM customer WHERE cu_id = :id");
			
			//param requete
			$id=$_POST['cu_id'];
			
			//construction requete
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
		}
		$this->destination="customer.php";
	}
	
	public function process(){
		$this->action=$_GET['action'];
			
		//ajout	
		if($this->action=="inscription")
			$this->destination="inscription.php";
		else if($this->action=="add")
			$this->add_customer();
			
		//edition	
		else if($this->action=="edition")
			$this->destination="edition_customer.php";
		else if($this->action=="update")
			$this->update_customer();
			
		//suppression
		else if($this->action=="suppression")
			$this->destination="suppression_customer.php";		
		else if($this->action=="delete")
			$this->delete_customer();	
			
		//autre
		else $this->destination="customer.php";
		
		//sauvegarde du get
		if(isset($_GET['id']))
			$this->destination=$this->destination.'?id='.$_GET['id'];
			
		//redirection	
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/';
		$url=$url.$this->destination;
		header('Location:'.$url);
	}
}

$ctr=new controller_customer();
$ctr->process();

?>
