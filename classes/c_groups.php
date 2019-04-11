<?php

/**
 * Description of c_groups
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-14
 * @version	0.01
 * 
 */
class c_groups {
	private $dbcon;
	var $id;
	var $name;
	var $icon;
	var $sort;
	var $ref_mod;
	var $comment;
	var $descrip;

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getGroupList($mid = 0) {
		if ($mid > 0) {
			//$query = "SELECT * FROM groups WHERE ref_mod>0 ORDER BY orders";
			$query = "SELECT * FROM groups WHERE ref_mod='$mid' ORDER BY orders";
		}
		else {
			$query = "SELECT * FROM groups WHERE ref_mod>0 ORDER BY orders";
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			return $stmt;
		}
		else {
			echo "<p class='fehler'>no matching group-data found</p>";
			exit;
		}
	}
	
	public function getGroupData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM groups WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);

				$this->name = $obj->name;
				$this->icon = $obj->icon;
				$this->sort = $obj->orders;
				$this->ref_mod = $obj->ref_mod;
				$this->comment = $obj->comment;
				$this->descrip = $obj->descrip;
			}
			else {
				echo "<p class='fehler'>no matching group found</p>";
				exit;
			}
		}
		else {
			echo "";
		}
	}
	public function updateGroup() {
		$query = "UPDATE groups SET name='$this->name', icon='$this->icon', orders='$this->sort', ref_mod=$this->ref_mod, comment='$this->comment', descrip='$this->descrip' WHERE id = $this->id ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();        
        //echo "<br>$query";
	}
	public function insertGroup($name="_name",$icon="_gfx/ph64.png",$order="a",$ref_mod=1,$comment="",$descrip="") {
		$query = "INSERT INTO groups (name,icon,orders,ref_mod,comment,descrip) VALUES('$name', '$icon', '$order', '$ref_mod', '$comment', '$descrip') ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeGroup($tid) {
		$query = "DELETE FROM groups WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	
	
}
