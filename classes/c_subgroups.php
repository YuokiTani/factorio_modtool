<?php

/**
 * Description of c_subgroups
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-14
 * @version	0.01
 * 
 */
class c_subgroups {
	private $dbcon;
	var $id;
	var $group;
	var $name;
	var $icon;
	var $order;
	var $ref_mod;
    var $comment;

	//var $comment;
	//var $descrip;

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getSubGroupList($mid = 0) {
		if ($mid > 0) {
			$query = "SELECT * FROM subgroups WHERE ref_mod='$mid' ORDER BY ref_group,name";
		}
		else {
			$query = "SELECT * FROM subgroups ORDER BY name";
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	
	public function getSubGroupData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM subgroups WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->group = $obj->ref_group;
				$this->name = $obj->name;
				$this->order = $obj->orders;
				$this->ref_mod = $obj->ref_mod;
				$this->comment = $obj->comment;
			}
			else {
				echo "<p class='fehler'>no matching subgroup found</p>";
				exit;
			}
		}
		else {
			echo "";
		}
	}
	public function updateSubGroup() {
		$query = "UPDATE subgroups SET ref_group=$this->group, name='$this->name', orders='$this->order', ref_mod=$this->ref_mod, comment='$this->comment' WHERE id = $this->id ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function insertSubGroup($group = 0, $name = "_name", $order = "a", $ref_mod = 0, $comment = "") {
		$query = "INSERT INTO subgroups (ref_group,name,orders,ref_mod,comment) VALUES('$group', '$name', '$order', '$ref_mod', '$comment') ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeSubGroup($tid) {
		$query = "DELETE FROM subgroups WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
}
