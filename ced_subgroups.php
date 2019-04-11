<?php

include 'm_core.php';
//$version = "v0.01 - 215-Jul-13";
$version = "v0.02 - 215-Jul-14";

pHeader("Subgroups");

echo "can be used to orginize recipes-lines or use for information";
echo "<br>";

$groups = new c_groups($dbco);
$sgroups = new c_subgroups($dbco);

if ($action == "Update") {
	// update
	$sgroups->getSubGroupData($id);
	$sgroups->group = get("group");
	$sgroups->name = get("name");
	$sgroups->order = get("order");
	$sgroups->ref_mod = get("ref_mod");
	$sgroups->comment = get("comment");
	$sgroups->updateSubGroup();
	$id = 0;
}

if ($action == 'addsubgroup') {
	$sgroups->insertSubGroup(0, "_name", "_order", $mid);
}
if ($action == 'remove') {
	echo "<p class='fehler'> Really ? If you click <a href='ced_subgroups.php?id=$id&mid=$mid&action=delete'><b>YES</b></a> this data will destroyed</p>";
}
if ($action == 'delete') {
	echo "<p class='erfolg'>destroying data succesful ... r.i.p. little bit's & bytes ...</p>";
	$sgroups->removeSubGroup($id);
}



// show all groups for now
echo "<p>";
if ($mid > 0) {
	echo "<a href='ced_subgroups.php?mid=$mid&action=addsubgroup' class='but-s'>+ New/Insert Subgroup</a>";
}
echo "<a href='ced_subgroups.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";


$x = "";
$stmt = $sgroups->getSubGroupList($mid);
if ($stmt->rowCount() > 0) {
	$x.= "<table class='lst'>";
	$x.="<tr>";
	$x.="<th>id</th>";
	$x.="<th>Mod (Code)</th>";
	$x.="<th>Group (ig Tab)</th>";
	$x.="<th width='250px'>Name</th>";
	$x.="<th width='100px'>Order</th>";
	$x.="<th>Comment</th>";
	$x.="<th>-</th>";
	$x.="</tr>";
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if ($id == $obj->id) {
			// edit-mode
			$x.="<form action='' method='get'>";
			$x.="<tr>";
			$x.="<td>$obj->id</td>";
			$rmo = new c_mod($dbco);
			$rmo->getMod($obj->ref_mod);
			$x.="<td>$rmo->title</td>";
			$grp = new c_groups($dbco);
			$res = $grp->getGroupList($mid);
			$p = "<select name='group' class='eingabe'>";
			while ($grp_o = $res->fetch(PDO::FETCH_OBJ)) {
				if ($grp_o->id == $obj->ref_group) {
					$p.="<option selected value='$grp_o->id' class='eingabe'>$grp_o->name</option>";
				}
				else {
					$p.="<option value='$grp_o->id'>$grp_o->name</option>";
				}
			}
			$p.="</select>";
			$x.="<td>$p</td>";
			$x.="<td><input type='text' name='name' value='$obj->name' size='32' class='small'></td>";
			$x.="<td><input type='text' name='order' value='$obj->orders' size='8' class='small'></td>";
			$x.="<td><input type='text' name='comment' value='$obj->comment' size='24' class='small'></td>";
			$x.="<td align='center'><input type='submit' name='action' value='Update' class='button'></td>";
			//$x.="<input type='submit' name='action' value='View Lua' class='button'></td>";
			$x.="</tr>";
			$x.="<input type='hidden' name='id' value='$obj->id'>";
			$x.="<input type='hidden' name='ref_mod' value='$obj->ref_mod'>";
			$x.="<input type='hidden' name='mid' value='$mid'>";
			$x.="</form>";
		}
		else {
			$x.="<tr>";
			$x.="<td>$obj->id</td>";
			$rmo = new c_mod($dbco);
			$rmo->getMod($obj->ref_mod);
			$x.="<td>$rmo->title</td>";
			$groups->getGroupData($obj->ref_group);
			$x.="<td>$groups->name</td>";
			$x.="<td><a href='ced_subgroups.php?id=$obj->id&mid=$mid&action=edit'>$obj->name</a></td>";
			$x.="<td>$obj->orders</td>";
			$x.="<td>$obj->comment</td>";
			$x.="<form action='' method='get'>";
			$x.="<td align='center'><a href='ced_subgroups.php?id=$obj->id&mid=$mid&action=remove'><img src='icons/edit-delete32.png' class='but' height='22px'></a></td>";
			$x.="</tr>";
		}
	}
	$x.="</table><br>";
}
else {
	echo "<p class='white'>no subgroups assigned to this mod defined/found - suggestion: create new or switch mod</p>";
}
echo $x;

if ($action == "codeview") {
	include "m_code2screen.php";
	echo itemgroups_cfg($mid, $dbco);
	echo proto_itemgroups_lua($mid, $dbco);
}

pFooter($version);
?>
