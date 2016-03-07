<?php
session_start();

Class controller_command{
	//variables
	protected $action;
	protected $destination;
	
	public function add_command(){
		//connexion
		$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
		
		//recuperation de tout les id
		$requete=$pdo->prepare("SELECT pr_id FROM product");
		$requete->execute();
		$tabReq = $requete->fetchall(PDO::FETCH_NUM);
		
		//sauvegarde
		$i=0;
		foreach($tabReq as $value){
			$idTmp[$i]=$value[0];
			$i++;
		}

		$i=0;
		$vide=true;
		foreach($idTmp as $value){
			$qtyTmp[$i]=$_POST['cp_qty_'.$idTmp[$i]];
			if($qtyTmp[$i] != '0')
				$vide=false;
			$i++;
		}
		
		//test si bien defini
		if(!$vide){
			
			if(isset($_POST['cu_id']) && $_POST['cu_id'] > 0)
				$cuid=$_POST['cu_id'];
			else
				$cuid=$_SESSION['userId'];
			
			//test si customer existe
			$requete = $pdo->prepare("SELECT cu_id FROM customer WHERE cu_id = :cuid");
			$requete->bindParam(':cuid', $cuid, PDO::PARAM_INT);
			$requete->execute();

			if($requete->rowCount() != 0){
				//construction tableau commande
				$nbProDiff=0;
				$taille = count($idTmp);
				for($i=0;$i<$taille;$i++){
					if($qtyTmp[$i] != 0){
						$qty[$nbProDiff]=$qtyTmp[$i];
						$id[$nbProDiff]=$idTmp[$i];
						$nbProDiff++;
					}
				}
				
				//calcul prix total
				$prixTotal=0;
				$taille = count($id);
				$requete=$pdo->prepare("SELECT pr_unit_price FROM product WHERE pr_id = :id");
				for($i=0;$i<$taille;$i++){
					$requete->bindParam(':id',$id[$i],PDO::PARAM_INT);
					$requete->execute();
					$tabReq = $requete->fetch(PDO::FETCH_NUM);
					$prixTotal = $prixTotal + ($tabReq[0] * $qty[$i]); 
				}
				
				//param requete
				$today=date("Y-m-d");
				$userId=$cuid;

				//construction requete
				$requete=$pdo->prepare("INSERT INTO command (co_date, co_cu_id, co_total_price, co_nb_cmdlines) VALUES (:date, :id, :prix, :nbProd)");
				$requete->bindParam(':date',$today,PDO::PARAM_STR);
				$requete->bindParam(':id',$userId,PDO::PARAM_INT);
				$requete->bindParam(':prix',$prixTotal,PDO::PARAM_INT);
				$requete->bindParam(':nbProd',$nbProDiff,PDO::PARAM_INT);
				
				//execution requete
				$requete->execute();
				
				//recherche id max
				$requete=$pdo->prepare("SELECT MAX(co_id) FROM command");	
				$requete->execute();			
				$tabReq = $requete->fetch(PDO::FETCH_NUM);
				$idCommand=$tabReq[0];
				
				//commprod
				for($i=0;$i<$nbProDiff;$i++){
					//param requete
					$idProduct=$id[$i];
					$qtyProduct=$qty[$i];
					
					//construction requete
					$requete=$pdo->prepare("INSERT INTO commprod (cp_co_id, cp_pr_id, cp_qty) VALUES (:coid, :prid, :qty)");
					$requete->bindParam(':coid',$idCommand,PDO::PARAM_INT);
					$requete->bindParam(':prid',$idProduct,PDO::PARAM_INT);
					$requete->bindParam(':qty',$qtyProduct,PDO::PARAM_INT);
				
					//execution requete
					$requete->execute();
					
					//ĉalcul du stock
					$requete=$pdo->prepare("SELECT pr_stock_qty FROM product WHERE pr_id = :prid");
					$requete->bindParam(':prid',$idProduct,PDO::PARAM_INT);
					$requete->execute();			
					$tabReq = $requete->fetch(PDO::FETCH_NUM);
					$newStock = $tabReq[0] - $qtyProduct;
					
					//construction requete
					$requete=$pdo->prepare("UPDATE product SET pr_stock_qty = :newQty WHERE pr_id = :prid");
					$requete->bindParam(':prid',$idProduct,PDO::PARAM_INT);
					$requete->bindParam(':newQty',$newStock,PDO::PARAM_INT);
					$requete->execute();
				}				
				$this->destination="product.php?prix=$prixTotal";
			}else
				$this->destination="ajout_command.php?essai=trueId";
		}else
			$this->destination="ajout_command.php?essai=true";
	}

	public function update_command(){
		//test si bien défini
		if(isset($_POST['co_total_price']) && $_POST['co_total_price'] != '' && isset($_POST['co_cu_id']) && $_POST['co_cu_id'] != ''){
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			
			$requete = $pdo->prepare("SELECT cu_id FROM customer WHERE cu_id = :coid");
			$requete->bindParam(':coid', $_POST['co_cu_id'], PDO::PARAM_INT);
			$requete->execute();

			if($requete->rowCount() != 0){
				//construction requete
				$requete=$pdo->prepare("UPDATE command SET co_total_price = :prix, co_cu_id = :cuid  WHERE co_id = :id");
				$requete->bindParam(':prix',$_POST['co_total_price'],PDO::PARAM_INT);
				$requete->bindParam(':cuid',$_POST['co_cu_id'],PDO::PARAM_INT);
				$requete->bindParam(':id',$_POST['co_id'],PDO::PARAM_INT);
				
				//execution requete
				$requete->execute();
				$this->destination="command.php";
			}else
				$this->destination="edition_command.php?essai=trueCu&id=".$_POST['co_id'];
		}else
			$this->destination="edition_command.php?essai=true&id=".$_POST['co_id'];
	}
	
	public function delete_command(){
		//test si bien défini
		if(isset($_POST['co_id']) && $_POST['co_id'] != ''){
			
			//connexion
			$pdo = new PDO("mysql:host=localhost;dbname=jdvdbd","jdvdbd", "jdvdbd");
			
			//param requete
			$id=$_POST['co_id'];	
			
			//remise en stock
			if($_POST['co_retour']){
				$requete=$pdo->prepare("SELECT cp_pr_id FROM commprod WHERE cp_co_id = :id");
				$requete->bindParam(':id',$id,PDO::PARAM_INT);
				$requete->execute();
				$tabReq = $requete->fetchall(PDO::FETCH_NUM);	
		
				$taille = count($tabReq);
				for($i=0;$i<$taille;$i++){
					//ancien stock
					$requete=$pdo->prepare("SELECT pr_stock_qty FROM product WHERE pr_id = :id");
					$requete->bindParam(':id',$tabReq[$i][0],PDO::PARAM_INT);
					$requete->execute();
					$tabStock = $requete->fetch(PDO::FETCH_NUM);	
					$stock = $tabStock[0];
					
					//nouveau stock
					$requete=$pdo->prepare("SELECT cp_qty FROM commprod WHERE cp_pr_id = :prid AND cp_co_id = :id");
					$requete->bindParam(':prid',$tabReq[$i][0],PDO::PARAM_INT);
					$requete->bindParam(':id',$id,PDO::PARAM_INT);
					$requete->execute();
					$newTabStock = $requete->fetch(PDO::FETCH_NUM);	
					$newStock = $newTabStock[0] + $stock;
					$requete=$pdo->prepare("UPDATE product SET pr_stock_qty = :newqty WHERE pr_id = :id");
					$requete->bindParam(':id',$tabReq[$i][0],PDO::PARAM_INT);
					$requete->bindParam(':newqty',$newStock,PDO::PARAM_INT);
					$requete->execute();
				}
			}
			
			//connexion
			$requete=$pdo->prepare("DELETE FROM command WHERE co_id = :id");
			
			//construction requete
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
			
			//connexion
			$requete=$pdo->prepare("DELETE FROM commprod WHERE cp_co_id = :id");
			
			//construction requete
			$requete->bindParam(':id',$id,PDO::PARAM_INT);
			
			//execution requete
			$requete->execute();
			
			
		}
		
		//redirection
		$this->destination="command.php";
	}
	
	public function process(){
		$this->action=$_GET['action'];
			
		//ajout	
		if($this->action=="ajout")
			$this->destination="ajout_command.php";
		else if($this->action=="add")
			$this->add_command();
		
		//edition	
		else if($this->action=="edition")
			$this->destination="edition_command.php";
		else if($this->action=="update")
			$this->update_command();
			
		//suppression
		else if($this->action=="suppression")
			$this->destination="suppression_command.php";		
		else if($this->action=="delete")
			$this->delete_command();	
			
		//autre
		else $this->destination="command.php";
		
		//sauvegarde du get
		if(isset($_GET['id']))
			$this->destination=$this->destination.'?id='.$_GET['id'];
			
		//redirection
		$url='http://'.$_SERVER['HTTP_HOST'].'/jdvd/';
		$url=$url.$this->destination;
		header('Location:'.$url);
	}
}

$ctr=new controller_command();
$ctr->process();

?>
