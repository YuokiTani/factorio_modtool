<?php

include 'm_core.php';
$version = "v0.01 - 215-Jul-15";

pHeader("Recipe-Categorys");

echo "recipe-category allow assign recipes to modded assemblys<br>";

if ($mid > 0) {
	$rc = new c_recipecat($dbco);

	if ($action == "Update") {
		// update
		$rc->getRCData($id);
		$rc->name = get("name");
		$rc->ref_mod = get("ref_mod");
		$rc->comment = get("comment");
		$rc->updateRecCat();
		$id = 0;
	}

	if ($action == 'addreccat') {
		$rc->insertRecCat("!_name", "$mid", "");
	}
	if ($action == 'remove') {
		echo "<p class='fehler'> Really ? If you click <a href='ced_reccat.php?id=$id&mid=$mid&action=delete'><b>Yeah</b></a> this data will vanish</p>";
	}
	if ($action == 'delete') {
		echo "<p class='erfolg'>vanishing bytes succesful ... r.i.p. little recipe-category ...</p>";
		$rc->removeRecCat($id);		
	}

	$p = "<p>";
	if ($mid > 0) {
		$p.="<a href='ced_reccat.php?mid=$mid&action=addreccat' class='but-s'>+ New/Insert Recipe Category</a>";
	}
	$p.="<a href='ced_reccat.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";
	echo $p;

	// only from selected mod
	$stmt = $rc->getRCList($mid);	
	if ($stmt->rowCount() > 0) {
		$x = "<table class='lst'>";
		$x.="<tr>";
		//$x.="<th>id</th>";
		$x.="<th>Mod (Code)</th>";
		$x.="<th width='250px'>Recipe-Category</th>";
		$x.="<th>Comment</th>";
		$x.="<th>-</th>";
		$x.="</tr>";
		while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
			if ($id == $obj->id) {
				// edit-mode
				$x.="<form action='' method='get'>";
				$x.="<tr>";
				//$x.="<td>$obj->id</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td>$rmo->title</td>";				
				//$x.="<td><input type='text' name='ref_mod' value='$obj->ref_mod' size='3' class='small ic'></td>";
				$x.="<td><input type='text' name='name' value='$obj->name' size='32' class='small'></td>";
				$x.="<td><input type='text' name='comment' value='$obj->comment' size='32' class='small'></td>";
				$x.="<td><input type='submit' name='action' value='Update' class='button'></td>";
				$x.="</tr>";
				$x.="<input type='hidden' name='id' value='$obj->id'>";
				$x.="<input type='hidden' name='ref_mod' value='$obj->ref_mod'>";
				$x.="<input type='hidden' name='mid' value='$mid'>";
				$x.="</form>";
			}
			else {
				$x.="<tr>";
				//$x.="<td>$obj->id</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td>$rmo->title</td>";
				$x.="<td><a href='ced_reccat.php?id=$obj->id&mid=$mid&action=edit'>$obj->name</a></td>";
				$x.="<td>$obj->comment</td>";
				$x.="<form action='' method='get'>";
				$x.="<td><a href='ced_reccat.php?id=$obj->id&mid=$mid&action=remove'><img src='icons/edit-delete32.png' class='but' height='22px'></a></td>";
				$x.="</tr>";
			}
		}
		$x.="</table><br>";
	}
	echo $x;
	if ($action == "codeview") {
		include "m_code2screen.php";
		echo itemgroups_cfg($mid, $dbco);
		echo proto_itemgroups_lua($mid, $dbco);
	}
}
else {
	echo "<p class='red'>no modifikation selected - go to MOD-Core </p>";
}

pFooter($version);
?>
