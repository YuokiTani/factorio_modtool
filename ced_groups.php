<?php

include 'm_core.php';
//$version = "v0.01 - 215-Jul-13";
$version = "v0.02 - 215-Jul-14";

pHeader("Groups");

echo "if you want your own recipe-tab or view information about - base-groups hidden<br>";

$groups = new c_groups($dbco);

if ($action == "Update") {
	// update
	$groups->getGroupData($id);
	$groups->name = get("name");
	$groups->icon = get("icon");
	$groups->sort = get("order");
	$groups->ref_mod = get("ref_mod");
	$groups->comment = get("comment");
	$groups->descrip = get("descrip");
	$groups->updateGroup();
	$id = 0;
}

if ($action == 'addgroup') {
	$groups->insertGroup("_name", "_icon", "a", "$mid");
}

if ($action == 'remove') {
	echo "<p class='fehler'> Really ? If you click <a href='ced_groups.php?id=$id&mid=$mid&action=delete'><b>YES</b></a> this data will destroyed</p>";
}
if ($action == 'delete') {
	echo "<p class='erfolg'>destroying data succesful ... r.i.p. little bit's & bytes ...</p>";
	$groups->removeGroup($id);
}

$x = "<p>";
if ($mid > 0) {
	$x.="<a href='ced_groups.php?mid=$mid&action=addgroup' class='but-s'>+ New/Insert Group</a>";
}
$x.="<a href='ced_groups.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";

$x.= "<table class='lst'>";
$x.="<tr>";
$x.="<th>id</th>";
$x.="<th>Mod (Code)</th>";
$x.="<th>Group (ig Tab)</th>";
$x.="<th>Icon</th>";
$x.="<th>Order</th>";
$x.="<th>Comment</th>";
$x.="<th>Description</th>";
$x.="<th>-</th>";
$x.="</tr>";

// show all groups for now
$stmt = $groups->getGroupList();
while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
	if ($id == $obj->id) {
		// edit-mode
		$x.="<form action='' method='get'>";
		$x.="<tr>";
		$x.="<td>$obj->id</td>";
		$x.="<td><input type='text' name='ref_mod' value='$obj->ref_mod' size='3' class='small ic'></td>";
		$x.="<td><input type='text' name='name' value='$obj->name' size='16' class='small'></td>";
		$x.="<td><input type='text' name='icon' value='$obj->icon' size='48' class='small'></td>";
		$x.="<td><input type='text' name='order' value='$obj->orders' size='16' class='small'></td>";
		$x.="<td><input type='text' name='comment' value='$obj->comment' size='24' class='small'></td>";
		$x.="<td><input type='text' name='descrip' value='$obj->descrip' size='24' class='small'></td>";
		$x.="<td align='center'><input type='submit' name='action' value='Update' class='button'></td>";
		//$x.="<input type='submit' name='action' value='View Lua' class='button'></td>";
		$x.="</tr>";
		$x.="<input type='hidden' name='id' value='$obj->id'>";
		$x.="<input type='hidden' name='mid' value='$mid'>";
		$x.="</form>";
	}
	else {
		$x.="<tr>";
		$x.="<td>$obj->id</td>";
		$rmo = new c_mod($dbco);
		$rmo->getMod($obj->ref_mod);
		$x.="<td>$rmo->title ($obj->ref_mod)</td>";
		$x.="<td><a href='ced_groups.php?id=$obj->id&mid=$mid&action=edit'>$obj->name</a></td>";
		$x.="<td><img src='$obj->icon' height='32px'></td>";
		$x.="<td>$obj->orders</td>";
		$x.="<td>$obj->comment</td>";
		$x.="<td>$obj->descrip</td>";
		$x.="<form action='' method='get'>";
		$x.="<td align='center'><a href='ced_groups.php?id=$obj->id&mid=$mid&action=remove'><img src='icons/edit-delete32.png' class='but' height='22px'></a></td>";
		$x.="</tr>";
	}
}
$x.="</table><br>";
echo $x;

if ($action == "codeview") {
	include "m_code2screen.php";
	echo itemgroups_cfg($mid, $dbco);
	echo proto_itemgroups_lua($mid, $dbco);
}

pFooter($version);
?>
