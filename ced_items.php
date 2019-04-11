<?php

include 'm_core.php';
//$version = "v0.01 - 215-Jul-15"; 
//$version = "v0.02 - 215-Dez-25"; // added direct recipe-generation via link
$version = "v0.03 - 219-Jan-26"; // Anpassung fuel-type

pHeader("Items");
echo "switch to mod you want - right-top-corner - this shows all items assigned to this mod<br>";
echo "column in () are optional - make sure you know what you do ... some spot's have mouseover-info (i needed to shorten some things)<br>";
echo "this item-list is also shown in recipes but only with name, icon and en-name - can you then remember this items ?";

if ($mid > 0) {
	
	$minfo = new c_mod($dbco);
	$recipe = new c_recipe($dbco);
	$item = new c_items($dbco);

	if ($action == "Update") {
		// update
		$item->getItemData($id);
		$item->name = get("name");
		$item->icon = get("icon");
		$item->ref_mod = $mid;
		$item->group = get("group");
		$item->subgroup = get("subgroup");
		$item->order = get("order");
		$item->stack_size = get("stack");
		$item->def_request = get("defrequest");
		$item->place_result = get("place");
		$item->fuel_value = get("fuel");
		$item->fuel_type = get('fuel_type');
		$item->comment = get("comment");
		$item->ig_name = get("ig_name");
		$item->ig_desc = get("ig_desc");
		$item->updateItem();
		$id = 0;
	}

	if ($action == "clone") {
		// update
		$item->getItemData($id);
		$item->insertItem("!_".$item->name, $item->icon, $item->group, $item->subgroup, $item->order, $item->stack_size, $item->place_result, $item->fuel_value, $item->type, $item->comment, $item->ref_mod, $item->ig_name);		
		$id = 0;
	}



	if ($action == 'additem') {
		//$item->insertItem($name, $icon, $group, $subgroup, $order, $stack_size, $place_result, $fuel_value, $comment, $ref_mod, $ig_name, $ig_desc);
		
		$minfo->getMod($mid);
		$path = $minfo->directory;
		$item->insertItem("!_name", "$path/graphics/icons/big_smile.png", 0, 0, "a", 100, "", 0,"", "", $mid, "ig_name", "ig_desc");
	}

	if ($action == 'addrecipe') {
		$item->getItemData($id);
		$rec_name = $item->name . "_recipe";
		$recipe->insertRecipe("$rec_name", "$item->icon", "1", "true", "0", "$item->group", "0", "$item->order", "", $mid, "$item->ig_name", "$item->ig_desc");
		echo "<p>done (added $rec_name)";
		$id = 0;
	}

	if ($action == 'remove') {
		echo "<p class='fehler'> Really ? If you click <a href='ced_items.php?id=$id&mid=$mid&action=delete'><b>Shred</b></a> this data will scrap</p>";
	}
	if ($action == 'delete') {
		echo "<p class='erfolg'>scraping item succesful ... now you in a better place - powerful item ...</p>";
		$item->removeItem($id);
	}

	$p = "<p>";
	if ($mid > 0) {
		$p.="<a href='ced_items.php?mid=$mid&action=additem' class='but-s'>+ New/Insert Item</a>";
	}
	$p.="<a href='ced_items.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";
	echo $p;

	$x = "";
	$sort = get("sort", "");
	// only from selected mod
	$stmt = $item->getItemList($mid, 0, $sort);
	if ($stmt->rowCount() > 0) {
		$x = "<table class='lst'>";
		$x.="<tr>";
		//$x.="<th>X</th>";
		$x.="<th>id</th>";
		$x.="<th>Mod</th>";
		$x.="<th>Group</th>";
		$x.="<th>SubGroup</th>";
		$x.="<th><a href='ced_items.php?mid=$mid&sort=name'>Name</a></th>";
		$x.="<th>Icon</th>";
		$x.="<th><a href='ced_items.php?mid=$mid&sort=order'>Order</a></th>";
		$x.="<th>Stack</th>";
		$x.="<th title='default request value'>DefRq</th>";
		$x.="<th title='fills place_result'>(Entity-Name)</th>";
		$x.="<th title='only used if fuel'>(Fuel-V)</th>";
		$x.="<th title='only used if fuel'>(Fuel-Type)</th>";
		$x.="<th>Comment</th>";
		$x.="<th>EN-Name</th>";
		$x.="<th>EN-Desc</th>";
		$x.="<th colspan='3'>-</th>";
		$x.="</tr>";
		$lfd = 0;
		while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
			if ($id == $obj->id) {
				// edit-mode
				$x.="<form action='' method='get'>";
				$x.="<tr>";				
				$x.="<td>$obj->id</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td title='$rmo->title'>? ($obj->ref_mod)</td>";
				//$x.="<td><input type='text' name='ref_mod' value='$obj->ref_mod' size='3' class='small ic'></td>";

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

				$sgrp = new c_subgroups($dbco);
				$res = $sgrp->getSubGroupList($mid);
				$p = "<select name='subgroup' class='eingabe'>";
				while ($sgrp_o = $res->fetch(PDO::FETCH_OBJ)) {
					if ($sgrp_o->id == $obj->ref_subgroup) {
						$p.="<option selected value='$sgrp_o->id' class='eingabe'>$sgrp_o->name</option>";
					}
					else {
						$p.="<option value='$sgrp_o->id'>$sgrp_o->name</option>";
					}
				}
				$p.="</select>";								
				$x.="<td>$p</td>";
				$x.="<td><input type='text' name='name' value='$obj->name' size='22' class='small'></td>";
				$x.="<td><input type='text' name='icon' value='$obj->icon' size='70' class='pre size9'></td>";
				$x.="<td><input type='text' name='order' value='$obj->orders' size='8' class='small'></td>";
				$x.="<td><input type='text' name='stack' value='$obj->stack_size' size='6' class='small ic'></td>";
				$x.="<td><input type='text' name='defrequest' value='$obj->def_request' size='4' class='small ic'></td>";
				$x.="<td><input type='text' name='place' value='$obj->place_result' size='22' class='small'></td>";
				$x.="<td><input type='text' name='fuel' value='$obj->fuel_value' size='6' class='small ic'></td>";
				$x.="<td><input type='text' name='fuel_type' value='$obj->fuel_type' size='6' class='small ic'></td>";
				$x.="<td><input type='text' name='comment' value='$obj->comment' size='18' class='small'></td>";
				$x.="<td><input type='text' name='ig_name' value='$obj->ig_name' size='18' class='small'></td>";
				$x.="<td><input type='text' name='ig_desc' value='$obj->ig_desc' size='18' class='small'></td>";
				$x.="<td colspan='3'><input type='submit' name='action' value='Update' class='button'></td>";
				$x.="</tr>";
				$x.="<input type='hidden' name='id' value='$obj->id'>";
				//$x.="<input type='hidden' name='ref_mod' value='$obj->ref_mod'>";
				$x.="<input type='hidden' name='mid' value='$mid'>";
				$x.="</form>";
			}
			else {
				$lfd++;
				$x.="<tr>";				
				$x.="<td align='right'>$lfd</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td title='$rmo->title'>? ($obj->ref_mod)</td>";

				$grp = new c_groups($dbco);
				$grp->getGroupData($obj->ref_group);
				$x.="<td>$grp->name</td>";

				$sgrp = new c_subgroups($dbco);
				$sgrp->getSubGroupData($obj->ref_subgroup);
				$x.="<td>$sgrp->name</td>";

				$x.="<td><a href='ced_items.php?id=$obj->id&mid=$mid&action=edit'>$obj->name</a></td>";
				$x.="<td><img src='$obj->icon' height='24px'></td>";
				$x.="<td align='center'>$obj->orders</td>";
				$x.="<td align='center'>$obj->stack_size</td>";
				$x.="<td align='center'>$obj->def_request</td>";
				$x.="<td>$obj->place_result</td>";
				$x.="<td align='center'>$obj->fuel_value</td>";
				$x.="<td align='center'>$obj->fuel_type</td>";
				$x.="<td>$obj->comment</td>";
				$x.="<td>$obj->ig_name</td>";
				$x.="<td title='$obj->ig_desc'>" . strcut($obj->ig_desc, 20) . "</td>";
				$x.="<form action='' method='get'>";
				
				$x.="<td><a href='ced_items.php?id=$obj->id&mid=$mid&action=clone' title='clone'><img src='icons/plus.png' class='but' height='16px'></a></td>";
				$rec_name = $obj->name . "_recipe";
				if ($recipe->checkRecipe($rec_name) == 0) {
					$x.="<td><a href='ced_items.php?id=$obj->id&mid=$mid&action=addrecipe' title='$rec_name'><img src='icons/32px-Nuvola_apps_ktip.png' class='but' height='16px'></a></td>";
				}	
				else {
					$x.="<td>&nbsp;</td>";
				}
				$x.="<td><a href='ced_items.php?id=$obj->id&mid=$mid&action=remove'><img src='icons/edit-delete32.png' class='but' height='16px'></a></td>";
				$x.="</tr>";
			}
		}
		$x.="</table><br>";
	}
	echo $x;
	if ($action == "codeview") {
		include "m_code2screen.php";
		echo items_cfg($mid, $dbco);
		echo proto_items_lua($mid, $dbco);
	}
}
else {
	echo "<p class='red'>no modifikation selected - go to MOD-Core </p>";
}
pFooter($version);
?>
