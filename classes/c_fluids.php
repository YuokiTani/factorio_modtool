<?php

/**
 * Description of c_fluids
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-16
 * @version	0.01
 * 
 */
class c_fluids {
	private $dbcon;
	var $id;
	var $name;
	var $icon;
	var $dtemp;
	var $mtemp;
	var $heatc;
	var $pts;  // pressure_speed
	var $flowe;  // flow energy
	var $bcolor; // base-color
	var $fcolor; // flow-color
	var $orders;
	var $subgroup;
	var $ref_mod;
	var $comment;
	var $ig_name;  // for locale-en
	var $ig_desc;  // for locale-en

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getFluidList($mid = 0, $z = 0) {
		if ($mid > 0 and $z == 0) {
			$query = "SELECT * FROM fluids WHERE ref_mod='$mid' ORDER BY name";
		}
		else if ($z == 1) {
			$query = "SELECT * FROM fluids WHERE ref_mod<>'$mid' ORDER BY name";
		}
		else {
			$query = "SELECT * FROM fluids ORDER BY id";
		}
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	public function getFluidData($id) {
		if ($id > 0) {
			$this->id = $id;
			$query = "SELECT * FROM fluids WHERE id = '$id' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->id = $id;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->name = $obj->name;
				$this->icon = $obj->icon;
				$this->dtemp = $obj->dtemp;
				$this->mtemp = $obj->mtemp;
				$this->heatc = $obj->heatc;
				$this->pts = $obj->pressure_speed;
				$this->flowe = $obj->flow_energy;
				$this->bcolor = $obj->bcolor;
				$this->fcolor = $obj->fcolor;
				$this->orders = $obj->orders;
				$this->subgroup = $obj->ref_subgroup;
				$this->ref_mod = $obj->ref_mod;
				$this->comment = $obj->comment;
				$this->ig_name = $obj->ig_name;
				$this->ig_desc = $obj->ig_desc;
			}
			else {
				echo "<p class='fehler'>no matching fluid found</p>";
				exit;
			}
		}
		else {
			echo "";
		}
	}
	public function updateFluid() {
		$query = "UPDATE fluids SET "
				. "name='$this->name', "
				. "icon='$this->icon', "
				. "dtemp='$this->dtemp', "
				. "mtemp='$this->mtemp', "
				. "heatc='$this->heatc', "
				. "pressure_speed='$this->pts', "
				. "flow_energy='$this->flowe', "
				. "bcolor='$this->bcolor', "
				. "fcolor='$this->fcolor', "
				. "orders='$this->orders', "
				. "ref_subgroup='$this->subgroup', "
				. "ref_mod='$this->ref_mod', "
				. "comment='$this->comment', "
				. "ig_name='$this->ig_name', "
				. "ig_desc='$this->ig_desc' "
				. "WHERE id = '$this->id' ";
		//echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function insertFluid($name, $ref_mod) {
		$query = "INSERT INTO fluids (name,ref_mod)"
				. " VALUES('$name', '$ref_mod') ";
		//echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function removeFluid($tid) {
		// before remove need all reference checked !!
		$query = "DELETE FROM fluids WHERE id = '$tid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
		echo "todo, because reference/used -check needed.";
	}
}
