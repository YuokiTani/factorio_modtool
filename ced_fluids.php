<?php

include 'm_core.php';
$version = "v0.01 - 215-Jul-13";

pHeader("Fluids");
echo "slippery wet ... <br>";
echo "<br>";

if ($mid > 0) {
	$fluid = new c_fluids($dbco);

	if ($action == "Update") {
		// update
		$fluid->getFluidData($id);
		$fluid->name = get("name");
		$fluid->icon = get("icon");
		$fluid->dtemp = get("dtemp");
		$fluid->mtemp = get("mtemp");
		$fluid->heatc = get("heatc");
		$fluid->pts = get("pts");
		$fluid->flowe = get("flowe");
		$fluid->bcolor = get("bcolor");
		$fluid->fcolor = get("fcolor");
		$fluid->orders = get("order");
		$fluid->subgroup = get("subgroup");
		$fluid->ref_mod = $mid;
		$fluid->comment = get("comment");
		$fluid->ig_name = get("ig_name");
		$fluid->ig_desc = get("ig_desc");
		$fluid->updateFluid();
		$id = 0;
	}

	if ($action == 'addFluid') {
		$fluid->insertFluid("!_flu-name", $mid);
	}
	if ($action == 'removeFluid') {
		echo "<p class='fehler'> Really ? If you click <a href='ced_fluids.php?id=$id&mid=$mid&action=deleteFluid'><b>Wuuschh</b></a> this data will flow away</p>";
	}
	if ($action == 'deleteFluid') {
		echo "<p class='erfolg'>all is flow away ...</p>";
		$fluid->removeFluid($id);
		$id=0; 
	}

	$p = "<p>";
	if ($mid > 0) {
		$p.="<a href='ced_fluids.php?mid=$mid&action=addFluid' class='but-s'>+ New/Insert Fluid</a>";
	}
	$p.="<a href='ced_fluids.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";
	echo $p;

	if ($id > 0) {
		$fluid->getFluidData($id);
		
		// edit-mode
		$x = "<table class='lst'>";
		$x.="<form action='' method='get'>";		
		$rmo = new c_mod($dbco);
		$rmo->getMod($fluid->ref_mod);
		$x.="<tr><td>Mod</td><td>$rmo->title</td></tr>";
		
		$sgrp = new c_subgroups($dbco);
		$res = $sgrp->getSubGroupList($mid);
		$p = "<select name='subgroup' class='eingabe'>";
		while ($sgrp_o = $res->fetch(PDO::FETCH_OBJ)) {
			if ($sgrp_o->id == $fluid->subgroup) {
				$p.="<option selected value='$sgrp_o->id' class='eingabe'>$sgrp_o->name</option>";
			}
			else {
				$p.="<option value='$sgrp_o->id'>$sgrp_o->name</option>";
			}
		}
		$p.="</select>";
		$x.="<tr><td>Subgroup</td><td>$p</td></tr>";

		$x.="<tr><td>Icon-Path</td><td><input type='text' name='icon' value='$fluid->icon' size='60' class='cnds'></td></tr>";
		$x.="<tr><td>internal Name</td><td><input type='text' name='name' value='$fluid->name' size='16' class='small'></td></tr>";
		$x.="<tr><td>lowest Temperature</td><td><input type='text' name='dtemp' value='$fluid->dtemp' size='2' class='small ic'> °C</td></tr>";
		$x.="<tr><td>highest Temperature</td><td><input type='text' name='mtemp' value='$fluid->mtemp' size='2' class='small ic'> °C</td></tr>";
		$x.="<tr><td>Heat Capacity</td><td><input type='text' name='heatc' value='$fluid->heatc' size='6' class='small ic'> needs a J/KJ after number</td></tr>";
		$x.="<tr><td>pressure_to_speed_ratio</td><td><input type='text' name='pts' value='$fluid->pts' size='6' class='small ic'></td></tr>";
		$x.="<tr><td>flow_to_energy_ratio</td><td><input type='text' name='flowe' value='$fluid->flowe' size='6' class='small ic'></td></tr>";
		$x.="<tr><td>base-color</td><td><input type='text' name='bcolor' value='$fluid->bcolor' size='20' class='small ic'></td></tr>";
		$x.="<tr><td>flow-color</td><td><input type='text' name='fcolor' value='$fluid->fcolor' size='20' class='small ic'></td></tr>";
		$x.="<tr><td>sort-order</td><td><input type='text' name='order' value='$fluid->orders' size='10' class='small ic'></td></tr>";
		$x.="<tr><td>(code-comment)</td><td><input type='text' name='comment' value='$fluid->comment' size='20' class='small'></td></tr>";
		$x.="<tr><td>ingame-name</td><td><input type='text' name='ig_name' value='$fluid->ig_name' size='40' class='cnds'></td></tr>";
		$x.="<tr><td>ingame-description</td><td><input type='text' name='ig_desc' value='$fluid->ig_desc' size='40' class='cnds'></td></tr>";
		$x.="<tr><td colspan='2' align='right'><input type='submit' name='action' value='Update' class='button'></td></tr>";		
		$x.="<input type='hidden' name='id' value='$fluid->id'>";
		//$x.="<input type='hidden' name='ref_mod' value='$obj->ref_mod'>";
		$x.="<input type='hidden' name='mid' value='$mid'>";
		$x.="</form>";
		$x.="</table><br>";
	}
	else {
		// only from selected mod
		$stmt = $fluid->getFluidList($mid);
		if ($stmt->rowCount() > 0) {
			$x = "<table class='lst'>";
			$x.="<tr>";
			$x.="<th>id</th>";
			$x.="<th>Mod</th>";
			$x.="<th>SubGroup</th>";
			$x.="<th>Icon</th>";
			$x.="<th>Name</th>";

			$x.="<th>D-Temp</th>";
			$x.="<th>M-Temp</th>";
			$x.="<th>HeatC</th>";
			$x.="<th>PTS</th>";
			$x.="<th>Flow-E</th>";
			$x.="<th>B-Color</th>";
			$x.="<th>F-Color</th>";

			$x.="<th>Order</th>";
			$x.="<th>Comment</th>";
			$x.="<th>EN-Name</th>";
			$x.="<th>EN-Desc</th>";
			$x.="<th>-</th>";
			$x.="</tr>";
			while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				$x.="<tr>";
				$x.="<td>$obj->id</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td>$rmo->name</td>";
				$sgrp = new c_subgroups($dbco);
				$sgrp->getSubGroupData($obj->ref_subgroup);
				$x.="<td>$sgrp->name</td>";
				$x.="<td><img src='$obj->icon' height='24px'></td>";
				$x.="<td><a href='ced_fluids.php?id=$obj->id&mid=$mid&action=edit'>$obj->name</a></td>";
				$x.="<td align='center'>$obj->dtemp °C</td>";
				$x.="<td align='center'>$obj->mtemp °C</td>";
				$x.="<td align='center'>$obj->heatc</td>";
				$x.="<td align='center'>$obj->pressure_speed</td>";
				$x.="<td>$obj->flow_energy</td>";
				$x.="<td>$obj->bcolor</td>";
				$x.="<td>$obj->fcolor</td>";
				$x.="<td align='center'>$obj->orders</td>";
				$x.="<td title='$obj->comment'>" . strcut($obj->comment, 20) . "</td>";
				$x.="<td>$obj->ig_name</td>";
				$x.="<td title='$obj->ig_desc'>" . strcut($obj->ig_desc, 20) . "</td>";
				$x.="<form action='' method='get'>";
				$x.="<td><a href='ced_fluids.php?id=$obj->id&mid=$mid&action=removeFluid'><img src='icons/edit-delete32.png' class='but' height='22px'></a></td>";
				$x.="</tr>";
			}
			$x.="</table><br>";
		}
	}
	echo $x;
	if ($action == "codeview") {
		include "m_code2screen.php";
		echo items_cfg($mid, $dbco);		
		echo proto_fluids_lua($mid, $dbco);
	}
}
else {
	echo "<p class='red'>no modifikation selected - go to MOD-Core </p>";
}

pFooter($version);
?>
