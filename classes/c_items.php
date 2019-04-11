<?php

/**
 * Description of c_items
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-14
 * @version	0.01
 * 
 * 
 * version 0.02 - 219Jan26 Anpassung fuel-type
 */
class c_items {
	private $dbcon;
	var $id;
	var $name;
	var $icon;
	var $group;
	var $subgroup;
	var $order;
	var $stack_size;
	var $def_request;
	var $place_result;
	var $fuel_value = 0;
	var $fuel_type = 'chemical';
	var $comment;
	var $ref_mod;
	var $ig_name;  // for locale-en
	var $ig_desc;  // for locale-en

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getItemList($mid = 0, $z = 0, $sort = "") {
		// Standard
		$query = "SELECT * FROM items ORDER BY name,id";
		// varianten		
		if (strlen($sort) > 1) {			
			if ($sort=='name') {
				$query = "SELECT * FROM items WHERE ref_mod='$mid' ORDER BY name";
			}
			if ($sort=='order') {
				$query = "SELECT * FROM items WHERE ref_mod='$mid' ORDER BY ref_subgroup, orders";
			}
		}
		else {
			if ($mid > 0 and $z == 0) {
				$query = "SELECT * FROM items WHERE ref_mod='$mid' ORDER BY id DESC";
			}
			else if ($z == 1) {
				$query = "SELECT * FROM items WHERE ref_mod<>'$mid' ORDER BY name";
			}
			else {
				$query = "SELECT * FROM items ORDER BY id";
			}
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	public function getItemData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM items WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->name = $obj->name;
				$this->icon = $obj->icon;
				$this->group = $obj->ref_group;
				$this->subgroup = $obj->ref_subgroup;
				$this->order = $obj->orders;
				$this->stack_size = $obj->stack_size;				
				$this->place_result = $obj->place_result;
				$this->fuel_value = $obj->fuel_value;
				$this->fuel_type = $obj->fuel_type;
				$this->def_request= $obj->def_request;
				$this->comment = $obj->comment;
				$this->ref_mod = $obj->ref_mod;
				$this->ig_name = $obj->ig_name;
				$this->ig_desc = $obj->ig_desc;
			}
			else {
				echo "<p class='fehler'>no matching item ($id) found</p>";
			}
		}
		else {
			echo "";
		}
	}
	public function updateItem() {
		$query = "UPDATE items SET "
				. "name='$this->name', "
				. "icon='$this->icon', "
				. "ref_group='$this->group', "
				. "ref_subgroup='$this->subgroup', "
				. "orders='$this->order', "
				. "stack_size='$this->stack_size', "
				. "place_result='$this->place_result', "
				. "fuel_value='$this->fuel_value', "
				. "fuel_type='$this->fuel_type', "
				. "def_request='$this->def_request', "
				. "comment='$this->comment', "
				. "ref_mod='$this->ref_mod', "
				. "ig_name='$this->ig_name', "
				. "ig_desc='$this->ig_desc' "
				. "WHERE id = '$this->id' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function insertItem($name, $icon, $group, $subgroup, $order = "a", $stack_size, $place_result, $fuel_value, $fuel_type, $comment = "", $ref_mod, $ig_name, $ig_desc = "") {
		$query = "INSERT INTO items (name,icon,ref_group,ref_subgroup,orders,stack_size,place_result,fuel_value,fuel_type,def_request,comment,ref_mod,ig_name,ig_desc)"
				. " VALUES('$name', '$icon', '$group', '$subgroup', '$order', '$stack_size', '$place_result', '$fuel_value','$fuel_type','5', '$comment', '$ref_mod', '$ig_name', '$ig_desc') ";
		echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeItem($tid) {
		// before remove need all reference checked !!
		$query = "DELETE FROM items WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		echo "todo, because reference/used -check needed.";
	}
}
