<?php

include 'm_core.php';
$version = "v0.01 - 215-Jul-15";

pHeader("Recipes");
echo "be your own sorcerer and create recipes<br>";
echo "";

if ($mid > 0) {
	$recipe = new c_recipe($dbco);
	if ($action == "Update") {
		// update
		echo "Update";
		$recipe->getRecipeData($id);
		$recipe->name = get("name");
		$recipe->icon = get("icon");
		$recipe->ref_mod = $mid;
		$recipe->category = get("category");
		$recipe->group = get("group");
		$recipe->subgroup = get("subgroup");
		$recipe->order = get("order");
		$recipe->enabled = get("enabled");
		$recipe->energy = get("energy");
		$recipe->comment = get("comment");
		$recipe->ig_name = get("ig_name");
		$recipe->ig_desc = get("ig_desc");
		$recipe->lngdesc = get("lng_desc");
		$recipe->updateRecipe();
		$id = 0;
	}

	if ($action == 'addrecipe') {
		//$recipe->insertRecipe($name, $icon, $energy, $enabled, $category, $group, $subgroup, $order, $comment, $ref_mod, $ig_name, $ig_desc);
		$recipe->insertRecipe("!_name", "icons/colba.png", "1", "true", "0", "0", "0", "0", "", $mid, "", "");
	}
	if ($action == 'remove') {
		echo "<p class='fehler'> Really ? If you click <a href='ced_recipes.php?id=$id&mid=$mid&action=delete'><b>I know</b></a> this data will forgotten</p>";
	}
	if ($action == 'delete') {
		echo "<p class='erfolg'>recipe forget succesful ... what i have deleted ? ...</p>";
		$recipe->removeRecipe($id);
	}
	if ($action == "addingred") {
		// type = i-ingred, r-result ... source i-items, f-fluid
		$item_id = get("item", 0);
		$item_type = get("item_type", "i");
		$query = "INSERT INTO _ingres_items (recipe, ref_item, number, type, source) VALUES ('$id', '$item_id', '1', 'i','$item_type')";
		$stmt = $dbco->prepare($query);
		$stmt->execute();
	}
	if ($action == "addresult") {
		$item_id = get("item", 0);
		$item_type = get("item_type", "i");
		$query = "INSERT INTO _ingres_items (recipe, ref_item, number, type, source) VALUES ('$id', '$item_id', '1', 'r','$item_type')";
		$stmt = $dbco->prepare($query);
		$stmt->execute();
	}
	if ($action == "rmvitem") {
		$lstid = get("lstid", 0);
		$query = "DELETE FROM _ingres_items WHERE id=$lstid";
		$stmt = $dbco->prepare($query);
		$stmt->execute();
	}
	if ($action == "edit-c") {
		$count = get("count", 1);
		$id_ingr = get("id_ingr", 0);
		$query = "UPDATE _ingres_items set number='$count' WHERE id='$id_ingr'";
		$stmt = $dbco->prepare($query);
		$stmt->execute();
		$action = 'edit';
	}
	if ($action =="clone") {
		$recipe->getRecipeData($id);
		$recipe->insertRecipe("!_".$recipe->name, $recipe->icon, $recipe->energy, "true", $recipe->category, $recipe->group, $recipe->subgroup, $recipe->order, "", $mid, $recipe->ig_name, $recipe->ig_desc);
		$id=0;
	}


	$p = "<p>";
	if ($mid > 0) {
		$p.="<a href='ced_recipes.php?mid=$mid&action=addrecipe' class='but-s'>+ Create New Recipe</a>";
	}
	$p.="<a href='ced_recipes.php?mid=$mid&action=codeview' class='but-s'>view Code</a></p>";
	echo $p;

	$x = "";
	// only from selected mod
	if ($id > 0) {
		// edit-view
		$recipe->getRecipeData($id);

		// edit-mode
		$x = "<table class='lst'>";
		$x.="<form action='' method='get'>";

		$rmo = new c_mod($dbco);
		$rmo->getMod($recipe->ref_mod);
		$x.="<tr><td>Mod</td><td>$rmo->title</td></tr>";
		$grp = new c_groups($dbco);
		$res = $grp->getGroupList(0);
		$p = "<select name='group' class='eingabe'>";
		while ($grp_o = $res->fetch(PDO::FETCH_OBJ)) {
			if ($grp_o->id == $recipe->group) {
				$p.="<option selected value='$grp_o->id' class='eingabe'>$grp_o->name</option>";
			}
			else {
				$p.="<option value='$grp_o->id'>$grp_o->name</option>";
			}
		}
		$p.="</select>";
		$x.="<tr><td>Group</td><td>$p</td></tr>";

		$sgrp = new c_subgroups($dbco);
		$res = $sgrp->getSubGroupList(0);
		$p = "<select name='subgroup' class='eingabe'>";
		while ($sgrp_o = $res->fetch(PDO::FETCH_OBJ)) {
			if ($sgrp_o->id == $recipe->subgroup) {
				$p.="<option selected value='$sgrp_o->id' class='eingabe'>$sgrp_o->name</option>";
			}
			else {
				$p.="<option value='$sgrp_o->id'>$sgrp_o->name</option>";
			}
		}
		$p.="</select>";
		$x.="<tr><td>SubGroup - Line</td><td>$p</td></tr>";

		$rec = new c_recipecat($dbco);
		$res = $rec->getRCList();
		$rm = new c_mod($dbco);
		$p = "<select name='category' class='eingabe'>";
		$a = "";
		while ($rec_o = $res->fetch(PDO::FETCH_OBJ)) {
			$rm->getMod($rec_o->ref_mod);
			if ($rec_o->id == $recipe->category) {
				$p.="<option selected value='$rec_o->id' class='eingabe'>$rec_o->name [$rm->name]</option>";
				$a = strcut($rec_o->comment, 20);
			}
			else {
				$p.="<option value='$rec_o->id'>$rec_o->name [$rm->name]</option>";
			}
		}
		$p.="</select>";
		$x.="<tr><td>recipe-category</td><td>$p ($a)</td></tr>";
		$x.="<tr><td>recipe-internal-Name</td><td><input type='text' name='name' value='$recipe->name' size='40' class='small'></td></tr>";
		$x.="<tr><td>icon</td><td><input type='text' name='icon' value='$recipe->icon' size='60' class='pre size9'></td></tr>";
		$x.="<tr><td>order</td><td><input type='text' name='order' value='$recipe->order' size='12' class='small'></td></tr>";
		$x.="<tr><td>craft time</td><td><input type='text' name='energy' value='$recipe->energy' size='6' class='small ic'></td></tr>";
		$p = "<tr><td>enabled</td><td><select name='enabled' class='eingabe'>";
		if ($recipe->enabled == "true") {
			$p.="<option selected value='true' class='eingabe'>true</option>";
			$p.="<option value='false' class='eingabe'>false</option>";
		}
		else {
			$p.="<option value='true' class='eingabe'>true</option>";
			$p.="<option selected value='false' class='eingabe'>false</option>";
		}
		$p.="</select> (false = need research)</td></tr>";
		//$x.="<tr><td>enabled</td><td><input type='text' name='enabled' value='$recipe->enabled' size='6' class='small ic'></td></tr>";
		$x.=$p;
		$x.="<tr><td>source-code comment</td><td><input type='text' name='comment' value='$recipe->comment' size='50' class='small'></td></tr>";
		$x.="<tr><td>EN-igName</td><td><input type='text' name='ig_name' value='$recipe->ig_name' size='50' class='small'></td></tr>";
		$x.="<tr><td>EN-igDescription</td><td><input type='text' name='ig_desc' value='$recipe->ig_desc' size='50' class='small'></td></tr>";
		$x.="<tr><td>EN-Long Desc</td><td><textarea name='lng_desc' class='small' cols='40' rows='20'>$recipe->lngdesc</textarea></td></tr>";
		$x.="<tr><td colspan=2' align='right'><input type='submit' name='action' value='Update' class='button'></td>";
		$x.="</tr>";
		$x.="<input type='hidden' name='id' value='$recipe->id'>";
		$x.="<input type='hidden' name='mid' value='$mid'>";
		$x.="</form>";
		$x.="</table><br>";
	}
	else {
		$stmt = $recipe->getRecipeList($mid);
		if ($stmt->rowCount() > 0) {
			$x = "<table class='lst'>";
			$x.="<tr>";
			//$x.="<th>id</th>";
			$x.="<th>Mod</th>";
			$x.="<th>Group</th>";
			$x.="<th>SubGroup</th>";
			$x.="<th>R-Category</th>";
			$x.="<th>Name</th>";
			$x.="<th>Icon</th>";
			$x.="<th>Order</th>";
			$x.="<th><img src='icons/user-away_32.png' height='22px' title='energy_required = Crafting Time'></th>";
			$x.="<th title='true/false'></th>";
			$x.="<th>Comment</th>";
			$x.="<th>EN-Name</th>";
			$x.="<th>EN-Desc</th>";
			$x.="<th colspan='3'>-</th>";
			$x.="</tr>";
			while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				$x.="<tr>";
				//$x.="<td>$obj->id</td>";
				$rmo = new c_mod($dbco);
				$rmo->getMod($obj->ref_mod);
				$x.="<td title='$rmo->title'>? ($obj->ref_mod)</td>";
				$grp = new c_groups($dbco);
				$grp->getGroupData($obj->ref_group);
				$x.="<td>$grp->name</td>";
				$sgrp = new c_subgroups($dbco);
				$sgrp->getSubGroupData($obj->ref_subgroup);
				$x.="<td>$sgrp->name</td>";
				$rec = new c_recipecat($dbco);
				$rec->getRCData($obj->ref_category);
				$x.="<td>$rec->name</td>";
				$x.="<td><a href='ced_recipes.php?id=$obj->id&mid=$mid&action=edit'><b><span class='cnds'>$obj->name</span></b></a></td>";

				if ($obj->icon == 'icons/colba.png') {
					//search for result
				}

				$x.="<td><img src='$obj->icon' height='24px'></td>";
				$x.="<td>$obj->orders</td>";
				$x.="<td align='center'>$obj->energy_required</td>";
				if ($obj->enabled=='false') {
					$x.="<td align='center'><img src='icons/Ball-yellow-128.png' class='but' height='16px' title='benötigt forschung'></td>";						
				}
				else {
					$x.="<td align='center'><img src='icons/mail-mark-notjunk_22.png' height='16px' title='ohne forschung'></td>";	
				}				
				$x.="<td>$obj->comment</td>";
				$x.="<td>$obj->ig_name</td>";
				$x.="<td title='$obj->ig_desc'>" . strcut($obj->ig_desc, 20) . "</td>";
				$x.="<form action='' method='get'>";
				$x.="<td><a href='ced_recipes.php?id=$obj->id&mid=$mid&action=remove'><img src='icons/edit-delete32.png' class='but' height='22px'></a></td>";

				// check for ingredients & results
				$qry = "SELECT * FROM _ingres_items WHERE recipe=$obj->id AND type='i'";
				$xtm = $dbco->prepare($qry);
				$xtm->execute();
				if ($xtm->rowCount() > 0) {
					$x.="<td><img src='icons/mail-mark-notjunk_22.png' height='16px' title='ingredient defined'></td>";
				}
				else {
					$x.="<td><img src='icons/Ball-yellow-128.png' height='16px' title='missing ingredient'></td>";
				}
				$qry = "SELECT * FROM _ingres_items WHERE recipe=$obj->id AND type='r'";
				$xtm = $dbco->prepare($qry);
				$xtm->execute();
				if ($xtm->rowCount() > 0) {
					$x.="<td><img src='icons/mail-mark-notjunk_22.png' height='16px' title='result defined'></td>";
				}
				else {
					$x.="<td><img src='icons/Ball-yellow-128.png' height='16px' title='missing result'></td>";
				}
				$x.="<td><a href='ced_recipes.php?id=$obj->id&mid=$mid&action=clone'>Clone</a></td>";	
				$x.="</tr>";
			}
			$x.="</table><br>";
		}
	}
	$t_details = "";
	$t_itemlist = "";

	if ($id > 0) {
		//clear var for calcs
		$inps = 0;
		$outs = 0;
		// show recipes details
		$t_details.="<table class='lst' width='180px'>";
		$query = "SELECT * FROM _ingres_items WHERE recipe=$id ORDER BY type, source";
		$stmt = $dbco->prepare($query);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$t_details.="<tr><th>Item</th><th>Amount</th><th>-</th></tr>";
			while ($it = $stmt->fetch(PDO::FETCH_OBJ)) {
				if ($it->source == 'i') {
					$ud = new c_items($dbco);
					$ud->getItemData($it->ref_item);
					if ($it->type == 'i') {
						$t_details.="<tr class='red'>";
						$t_details.="<td><img src='$ud->icon' height='32px' title='ingred: $ud->name'></td>";
						$t_details.="<form action='' method='get'>";
						$t_details.="<input type='hidden' name='id' value='$id'>";
						$t_details.="<input type='hidden' name='mid' value='$mid'>";
						$t_details.="<input type='hidden' name='action' value='edit-c'>";
						$t_details.="<input type='hidden' name='id_ingr' value='$it->id'>";
						$t_details.="<td><input type='text' name='count' value='$it->number' class='eingabe ic mono' size='8'><button class='imgbut'><img src='icons/refresh.png' height='16px'></button></td>";
						$t_details.="</form>";

						$inps++;
						$inp[$inps]['sr'] = 'i';
						$inp[$inps]['ty'] = 'i';
						$inp[$inps]['id'] = $it->id;
						$inp[$inps]['nu'] = $it->number;
					}
					if ($it->type == 'r') {
						$t_details.="<tr class='green'>";
						$t_details.="<td><img src='$ud->icon' height='32px' title='result: $ud->name'></td>";
						$t_details.="<form action='' method='get'>";
						$t_details.="<input type='hidden' name='id' value='$id'>";
						$t_details.="<input type='hidden' name='mid' value='$mid'>";
						$t_details.="<input type='hidden' name='action' value='edit-c'>";
						$t_details.="<input type='hidden' name='id_ingr' value='$it->id'>";
						$t_details.="<td><input type='text' name='count' value='$it->number' class='eingabe ic mono' size='8'><button class='imgbut'><img src='icons/refresh.png' height='16px'></button></td>";
						$t_details.="</form>";

						$outs++;
						$inp[$outs]['sr'] = 'i';
						$inp[$outs]['ty'] = 'r';
						$inp[$outs]['id'] = $it->id;
						$inp[$outs]['nu'] = $it->number;
					}
					$t_details.="<td><a href='ced_recipes.php?id=$id&mid=$mid&action=rmvitem&lstid=$it->id'><img src='icons/edit-delete32.png' height='16px'></td>";
					$t_details.="</tr>";
				}
				if ($it->source == 'f') {
					$fd = new c_fluids($dbco);
					$fd->getFluidData($it->ref_item);
					if ($it->type == 'i') {
						$t_details.="<tr class='red'>";
						$t_details.="<td><img src='$fd->icon' height='32px' title='ingred: $fd->name'></td>";
						$t_details.="<form action='' method='get'>";
						$t_details.="<input type='hidden' name='id' value='$id'>";
						$t_details.="<input type='hidden' name='mid' value='$mid'>";
						$t_details.="<input type='hidden' name='action' value='edit-c'>";
						$t_details.="<input type='hidden' name='id_ingr' value='$it->id'>";
						$t_details.="<td><input type='text' name='count' value='$it->number' class='eingabe ic mono' size='8'><button class='imgbut'><img src='icons/refresh.png' height='16px'></button></td>";
						$t_details.="</form>";

						$inps++;
						$inp[$inps]['sr'] = 'f';
						$inp[$inps]['ty'] = 'i';
						$inp[$inps]['id'] = $it->id;
						$inp[$inps]['nu'] = $it->number;
					}
					if ($it->type == 'r') {
						$t_details.="<tr class='green'>";
						$t_details.="<td><img src='$fd->icon' height='32px' title='result: $fd->name'></td>";
						$t_details.="<form action='' method='get'>";
						$t_details.="<input type='hidden' name='id' value='$id'>";
						$t_details.="<input type='hidden' name='mid' value='$mid'>";
						$t_details.="<input type='hidden' name='action' value='edit-c'>";
						$t_details.="<input type='hidden' name='id_ingr' value='$it->id'>";
						$t_details.="<td><input type='text' name='count' value='$it->number' class='eingabe ic mono' size='8'><button class='imgbut'><img src='icons/refresh.png' height='16px'></button></td>";
						$t_details.="</form>";

						$outs++;
						$inp[$outs]['sr'] = 'f';
						$inp[$outs]['ty'] = 'r';
						$inp[$outs]['id'] = $it->id;
						$inp[$outs]['nu'] = $it->number;
					}
					$t_details.="<td><a href='ced_recipes.php?id=$id&mid=$mid&action=rmvitem&lstid=$it->id'><img src='icons/edit-delete32.png' height='16px'></td>";
					$t_details.="</tr>";
				}
			}
		}
		else {
			$t_details.="<tr><th><p class='fehler'>missing ingredients/results</th></tr>";
		}
		$t_details.="</table>";

		// get all aviable items/fluid
		$t_itemlist.="<table><tr><td valign='top'>";
		$t_itemlist.="<table class='lst'>";
		$items = new c_items($dbco);
		$ires = $items->getItemList($mid);
		if ($ires->rowCount() > 0) {
			$t_itemlist.= "<tr>";
			//$t_itemlist.= "<th><img src='icons/colba.png' height='22px' title='Require'></th>";
			$t_itemlist.= "<th colspan='4'>Mod-Items</th>";
			//$t_itemlist.= "<th><img src='icons/go-bottom_32.png' height='22px' title='Result'></th>";
			$t_itemlist.= "</tr>";
			while ($iobj = $ires->fetch(PDO::FETCH_OBJ)) {
				$t_itemlist.= "<tr>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addingred&item=$iobj->id&item_type=i'><img src='icons/Fleche droite rouge.png' height='18px' class='imgbut' title='add as ingredient'></a></td>";
				$t_itemlist.= "<td><img src='$iobj->icon' height='22px'></td><td>$iobj->name</td>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addresult&item=$iobj->id&item_type=i'><img src='icons/Fleche droite vert.png' height='18px' class='imgbut' title='add as result'></a></td>";
				$t_itemlist.= "</tr>";
			}
		}
		$fluid = new c_fluids($dbco);
		$fres = $fluid->getFluidList($mid);
		if ($fres->rowCount() > 0) {
			$t_itemlist.= "<tr>";
			//$t_itemlist.= "<th><img src='icons/colba.png' height='22px' title='Require'></th>";
			$t_itemlist.= "<th colspan='4'>Mod-Fluids</th>";
			//$t_itemlist.= "<th><img src='icons/go-bottom_32.png' height='22px' title='Result'></th>";
			$t_itemlist.= "</tr>";
			while ($fobj = $fres->fetch(PDO::FETCH_OBJ)) {
				$t_itemlist.= "<tr>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addingred&item=$fobj->id&item_type=f'><img src='icons/Fleche droite rouge.png' height='18px' class='imgbut' title='add as ingredient'></a></td>";
				$t_itemlist.= "<td><img src='$fobj->icon' height='22px'></td><td>$fobj->name</td>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addresult&item=$fobj->id&item_type=f'><img src='icons/Fleche droite vert.png' height='18px' class='imgbut' title='add as result'></a></td>";
				$t_itemlist.= "</tr>";
			}
		}
		$t_itemlist.="</table></td>";

		$t_itemlist.="<td valign='top'><table class='lst'>";
		// all items+fluids
		$items = new c_items($dbco);
		$ires = $items->getItemList($mid, 1);
		if ($ires->rowCount() > 0) {
			$t_itemlist.= "<tr>";
			//$t_itemlist.= "<th><img src='icons/colba.png' height='22px' title='Require'></th>";
			$t_itemlist.= "<th colspan='4'>all-Items</th>";
			//$t_itemlist.= "<th><img src='icons/go-bottom_32.png' height='22px' title='Result'></th>";
			$t_itemlist.= "</tr>";
			$sp = 0;

			$t_itemlist.= "<tr>";
			while ($iobj = $ires->fetch(PDO::FETCH_OBJ)) {
				$sp++;

				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addingred&item=$iobj->id&item_type=i'><img src='icons/Fleche droite rouge.png' height='18px' class='imgbut' title='add as ingredient'></a></td>";
				$t_itemlist.= "<td><img src='$iobj->icon' height='22px'></td><td>$iobj->name</td>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addresult&item=$iobj->id&item_type=i'><img src='icons/Fleche droite vert.png' height='18px' class='imgbut' title='add as result'></a></td>";
				if ($sp == 3) {
					$t_itemlist.= "</tr><tr>";
					$sp = 0;
				}
			}
			$t_itemlist.= "</tr>";
		}
		$fluid = new c_fluids($dbco);
		$fres = $fluid->getFluidList($mid, 1);
		if ($fres->rowCount() > 0) {
			$t_itemlist.= "<tr>";
			//$t_itemlist.= "<th><img src='icons/colba.png' height='22px' title='Require'></th>";
			$t_itemlist.= "<th colspan='4'>all-Fluids</th>";
			//$t_itemlist.= "<th><img src='icons/go-bottom_32.png' height='22px' title='Result'></th>";
			$t_itemlist.= "</tr>";
			while ($fobj = $fres->fetch(PDO::FETCH_OBJ)) {
				$t_itemlist.= "<tr>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addingred&item=$fobj->id&item_type=f'><img src='icons/Fleche droite rouge.png' height='18px' class='imgbut' title='add as ingredient'></a></td>";
				$t_itemlist.= "<td><img src='$fobj->icon' height='22px'></td><td>$fobj->name</td>";
				$t_itemlist.= "<td align='center'><a href='ced_recipes.php?id=$id&mid=$mid&action=addresult&item=$fobj->id&item_type=f'><img src='icons/Fleche droite vert.png' height='18px' class='imgbut' title='add as result'></a></td>";
				$t_itemlist.= "</tr>";
			}
		}
		$t_itemlist.="</table></td></tr></table>";
	}
	// framed tables
	$z = "";
	$z.="<table><tr><td valign='top'>";
	$z.=$x;
	$z.="</td><td valign='top'>";
	$z.=$t_details;
	$z.="</td><td valign='top'>";
	$z.=$t_itemlist;
	$z.="</td></tr></table>";
	echo $z;

	if ($action == "codeview") {
		include "m_code2screen.php";
		echo recipes_cfg($mid, $dbco);
		echo proto_recipes_lua($mid, $dbco);
	}
}
else {
	echo "<p class='red'>no modifikation selected - go to MOD-Core </p>";
}

pFooter($version);
?>
