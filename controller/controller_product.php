<?php
session_start();

Class controller_product{
	//variables
	protected $action;
	protected $destination;
	
	public function add_product(){
		//test si bien defini
		if(isset($_POST['pr_unit_price']) && isset($_POST['pr_label']) && isset($_POST['pr_stock_qty'])
			&& $_POST['pr_unit_price'] != '' && $_POST['pr_label'] != '' && $_POST['pr_stock_qty'] != ''){
				
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			$requete=$pdo->prepare("INSERT INTO product (pr_label, pr_stock_qty, pr_unit_price) VALUES (:lb, :stck, :up);");
			
			//param requete
			$label=$_POST['pr_label'];
			$stock=$_POST['pr_stock_qty'];
			$prix=$_POST['pr_unit_price'];

			//construction requete
			$requete->bindParam(':lb',$label,PDO::PARAM_STR);
			$requete->bindParam(':stck',$stock,PDO::PARAM_INT);
			$requete->bindParam(':up',$prix,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
			$this->destination="product.php";	
		}else
			$this->destination="ajout_produit.php?essai=true";
	}
	
	public function update_product(){
		//test si bien défini
		if(isset($_POST['pr_unit_price']) && isset($_POST['pr_label']) && isset($_POST['pr_stock_qty']) && isset($_POST['pr_id']) 
			&& $_POST['pr_unit_price'] != '' && $_POST['pr_label'] != '' && $_POST['pr_stock_qty'] != '' && $_POST['pr_id'] != ''){		
		
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			$requete=$pdo->prepare("UPDATE product SET pr_label = :lb, pr_stock_qty = :stck, pr_unit_price = :up WHERE pr_id = :id");
		
			//param requete
			$label=$_POST['pr_label'];
			$stock=$_POST['pr_stock_qty'];
			$prix=$_POST['pr_unit_price'];
			$id=$_POST['pr_id'];
			
			//construction requete
			$requete->bindParam(':lb',$label,PDO::PARAM_STR);
			$requete->bindParam(':stck',$stock,PDO::PARAM_INT);
			$requete->bindParam(':up',$prix,PDO::PARAM_INT);
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
			$this->destination="product.php";
		}else
			$this->destination="edition_product.php?essai=true&id=".$_POST['pr_id'];
	}
	
	public function delete_product(){
		//test si bien défini
		if(isset($_POST['pr_id']) && $_POST['pr_id'] != ''){
			
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			$requete=$pdo->prepare("DELETE FROM product WHERE pr_id = :id");
		
			//param requete
			$id=$_POST['pr_id'];
			
			//construction requete
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
		}
		$this->destination="product.php";
	}
	
	public function process(){
		$this->action=$_GET['action'];
			
		//ajout	
		if($this->action=="ajout")
			$this->destination="ajout_product.php";
		else if($this->action=="add")
			$this->add_product();
			
		//edition	
		else if($this->action=="edition")
			$this->destination="edition_product.php";
		else if($this->action=="update")
			$this->update_product();
			
		//suppression
		else if($this->action=="suppression")
			$this->destination="suppression_product.php";		
		else if($this->action=="delete")
			$this->delete_product();	
			
		//autre
		else $this->destination="product.php";
		
		//sauvegarde du get
		if(isset($_GET['id']))
			$this->destination=$this->destination.'?id='.$_GET['id'];
			
		//redirection	
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/';
		$url=$url.$this->destination;
		header('Location:'.$url);
	}
}

$ctr=new controller_product();
$ctr->process();

?>
