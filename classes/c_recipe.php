<?php

/**
 * Description of c_items
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-14
 * @version	0.01
 * 
 * 215-1225 - 0.02 - search for name
 * 
 */
class c_recipe {
	private $dbcon;
	var $id;
	var $name;
	var $icon;
	var $ref_mod;
	var $category;
	var $group;
	var $subgroup;
	var $order;
	var $comment;
	var $energy;
	var $enabled;
	var $ig_name;  // for locale-en
	var $ig_desc;  // for locale-en
	var $lngdesc;  // for handbook

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getRecipeList($mid = 0) {
		if ($mid > 0) {
			$query = "SELECT * FROM recipes WHERE ref_mod='$mid' ORDER BY id DESC";			
		}
		else {
			$query = "SELECT * FROM recipes ORDER BY orders, name";
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	public function getRecipeData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM recipes WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();

			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->name = $obj->name;
				$this->icon = $obj->icon;
				$this->ref_mod = $obj->ref_mod;
				$this->category = $obj->ref_category;
				$this->group = $obj->ref_group;
				$this->subgroup = $obj->ref_subgroup;
				$this->order = $obj->orders;
				$this->comment = $obj->comment;
				$this->energy = $obj->energy_required;
				$this->enabled = $obj->enabled;
				$this->ig_name = $obj->ig_name;
				$this->ig_desc = $obj->ig_desc;
				$this->lngdesc = $obj->lngdesc;
			}
			else {
				echo "<p class='fehler'>no matching recipe found</p>";
				exit;
			}
		}
		else {
			echo "<p class='fehler'>id is missing</p>";
		}
	}
	public function updateRecipe() {
		$query = "UPDATE recipes SET "
				. "name='$this->name', "
				. "icon='$this->icon', "
				. "energy_required='$this->energy', "
				. "enabled='$this->enabled', "
				. "ref_category='$this->category', "
				. "ref_group='$this->group', "
				. "ref_subgroup='$this->subgroup', "
				. "orders='$this->order', "
				. "comment='$this->comment', "
				. "ref_mod='$this->ref_mod', "
				. "ig_name='$this->ig_name', "
				. "ig_desc='$this->ig_desc', " 
				. "lngdesc='$this->lngdesc' "
				. "WHERE id = '$this->id' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function insertRecipe($name, $icon, $energy, $enabled, $category, $group, $subgroup, $order = "a", $comment = "", $ref_mod, $ig_name, $ig_desc = "") {
		$query = "INSERT INTO recipes (name, icon, energy_required, enabled, ref_category, ref_group, ref_subgroup, orders, comment, ref_mod, ig_name, ig_desc)"
				. " VALUES('$name', '$icon', '$energy', '$enabled', '$category', '$group', '$subgroup', '$order', '$comment', '$ref_mod', '$ig_name', '$ig_desc') ";
		echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeRecipe($tid) {
		echo "todo, prerequiste-technology -check needed.";
		$query = "DELETE FROM recipes WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function checkRecipe($name) {
		$query = "SELECT * FROM recipes WHERE name = '$name' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			return 1;
		}
		else {
			return 0;
		}
	}
	
}
