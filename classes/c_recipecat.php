<?php

/**
 * Description of c_recipecat
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-15
 * @version	0.01
 * 
 */
class c_recipecat {
	private $dbcon;
	var $id;
	var $name;
	var $ref_mod;
	var $comment;

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getRCList($mid = 0, $switch=0) {
		if ($mid > 0 and $switch==0) {
			$query = "SELECT * FROM recipecat WHERE ref_mod='$mid' OR ref_mod=1 ORDER BY name";
		}
		elseif ($mid > 0 and $switch==1) {
			$query = "SELECT * FROM recipecat WHERE ref_mod='$mid' ORDER BY name";
		}
		else {
			$query = "SELECT * FROM recipecat ORDER BY name";
			//echo "<p class='fehler'>you need a mod selected to work with</p>";
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	
	public function getRCData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM recipecat WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->name = $obj->name;
				$this->ref_mod = $obj->ref_mod;
				$this->comment = $obj->comment;
			}
			else {
				echo "<p class='fehler'>no matching recipe-category found</p>";
				exit;
			}
		}
		else {
			echo "";
		}
	}
	public function updateRecCat() {
		$query = "UPDATE recipecat SET name='$this->name', ref_mod=$this->ref_mod, comment='$this->comment' WHERE id = $this->id ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();        
        //echo "<br>$query";
	}
	public function insertRecCat($name,$ref_mod=1,$comment="") {
		$query = "INSERT INTO recipecat (name,ref_mod,comment) VALUES('$name', '$ref_mod', '$comment') ";
		echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeRecCat($tid) {
		$query = "DELETE FROM recipecat WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	
	
}
