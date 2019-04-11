<?php

/**
 * Description of c_mod
 *
 * @author		yuokitani (SSA)
 * @date		2015-Jul-13
 * @version	0.01	
 */
class c_mod {
	private $dbcon;
	var $mid;
	var $name;
	var $title;
	var $version;
	var $datum;
	var $author;
	var $dependencies;
	var $description;
	var $directory;

	public function __construct($dbco) {
		$this->dbcon = $dbco;
	}
	public function getMod($mid) {
		if ($mid > 0) {
			$this->mid = $mid;
			$query = "SELECT * FROM modc WHERE id = '$mid' ";
			$stmt = $this->dbcon->prepare($query);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$this->mid = $mid;
				//return $stmt->fetch(PDO::FETCH_OBJ);
				$obj = $stmt->fetch(PDO::FETCH_OBJ);
				$this->name = $obj->name;
				$this->title = $obj->title;
				$this->version = $obj->version;
				$this->datum = $obj->datum;
				$this->author = $obj->author;
				$this->dependencies = $obj->dependencies;
				$this->description = $obj->description;
				$this->directory = $obj->directory;
			}
			else {
				echo "<p class='fehler'>no matching found</p>";
				exit;
			}
		}
		else {
			echo "";
		}
	}
	public function updateMod() {
		$query = "UPDATE modc SET name='$this->name',title='$this->title', version='$this->version', datum='$this->datum', author='$this->author', dependencies='$this->dependencies', description='$this->description', directory='$this->directory' WHERE id = '$this->mid' ";
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
	public function newMod() {
		$date = date("Y-m-d", time());
		$query = "INSERT INTO modc (id, name, title, version, datum, author, dependencies, description, directory) VALUES ('0','_name', '_title', '0.0.1', '$date', '_author', '\"base >= 0.12\"', '_description','_directory')";
		echo $query;
		$stmt = $this->dbcon->prepare($query);
		$stmt->execute();
	}
}
