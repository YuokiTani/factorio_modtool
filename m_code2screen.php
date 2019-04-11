<?php

/*
 * @title
 * @author	Yuoki Tani (SSA)
 * @date	215-Jul-15
 * @version	0.01
 * @projekt	
 * 
 * version 0.02 - 215Jul16 change all to media accepted
 * version 0.03 - 215Jul17 change all to media accepted
 * version 0.04 - 219Jan26 Anpassung fuel-type
 */
// $version = "| fMT-Export (c)YT v0.03-215Jul17";
$version = "| fMT-Export (c)YT v0.04-216Mrz03"; // added file-date included

function itemgroups_cfg($mid, $dbco, $media = 0) {

	$groups = new c_groups($dbco);
	$sgroups = new c_subgroups($dbco);

	$p = "control view -> locale/en/item-groups.cfg";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$file = "\n[item-group-name]";
	$stmt = $groups->getGroupList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->comment";
	}
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $file . $a;
	}
	else {
		return $file;
	}
}

function proto_itemgroups_lua($mid, $dbco, $media = 0) {
	global $version;
	$groups = new c_groups($dbco);
	$sgroups = new c_subgroups($dbco);
	$rc = new c_recipecat($dbco);
	$p = "control view -> prototypes/item-groups.lua";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$file = "\n--automatically generated file $version";
	$file .= "\n--export-date: " . date("Y-M-d", time()) . "\n\n";
	$file .= "data:extend({\n";
	$stmt = $groups->getGroupList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if (strlen($obj->comment) > 1) {
			$file .= "\n	--$obj->comment";
		}
		$file .= "\n	{ type=\"item-group\", name=\"$obj->name\", icon_size=64, icon=\"$obj->icon\", inventory_order=\"y\", order=\"$obj->orders\" },";
	}
	$file .= "\n";
	// sub-groups-part
	$stmt = $sgroups->getSubGroupList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$groups->getGroupData($obj->ref_group);
		$file .= "\n	{ type=\"item-subgroup\", group=\"$groups->name\", order=\"$obj->orders\", name=\"$obj->name\", },";
		if (strlen($obj->comment) > 1) {
			$file .= " --$obj->comment";
		}
	}
	$file .= "\n";
	// recipe-categorys-part
	$stmt = $rc->getRCList($mid, 1);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n	{ type=\"recipe-category\", name=\"$obj->name\" }, --$obj->comment";
	}
	$file .= "\n\n})";
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $file . $a;
	}
	else {
		return $file;
	}
}

function items_cfg($mid, $dbco, $media = 0) {
	$group = new c_groups($dbco);
	$items = new c_items($dbco);
	$fluid = new c_fluids($dbco);
	$p = "control view -> locale/en/item-names.cfg";
	$p .= "<div class='frame'><pre class='gray size9'>";

	$file = "\n[item-group-name]";
	$stmt = $group->getGroupList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->comment";
	}
	$file .= "\n";
	$file .= "\n[item-name]";
	$stmt = $items->getItemList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->ig_name";
	}
	$file .= "\n";
	$file .= "\n[item-description]";
	$stmt = $items->getItemList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->ig_desc";
	}
	$file .= "\n";
	$file .= "\n[fluid-name]";
	$stmt = $fluid->getFluidList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->ig_name";
	}
	$file .= "\n";
	$file .= "\n[fluid-description]";
	$stmt = $fluid->getFluidList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		$file .= "\n$obj->name=$obj->ig_desc";
	}
	$file .= "\n";
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $file . $a;
	}
	else {
		return $file;
	}
}

function proto_items_lua($mid, $dbco, $media = 0) {
	global $version;
	$groups = new c_groups($dbco);
	$sgroups = new c_subgroups($dbco);
	$items = new c_items($dbco);
	$p = "control view -> prototypes/item.lua";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$file = "\n--automatically generated file $version";
	$file .= "\n--export-date: " . date("Y-M-d", time()) . "\n\n";
	$file .= "data:extend({\n";
	$stmt = $items->getItemList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if ($obj->comment == "NE") {
			// No Export
		}
		else {
			if (strlen($obj->comment) > 1) {
				$file .= "\n--ID:$obj->id $obj->comment";
			}
			else {
				$file .= "\n--ID:$obj->id";
			}

			$file .= "\n{";
			/*	
			if (strlen($obj->place_result) > 1) {
				$file .= "\n   type=\"item\", name=\"$obj->name\", icon_size=32, icon=\"$obj->icon\", flags={\"goes-to-quickbar\"}, ";
			}
			else {
				$file .= "\n   type=\"item\", name=\"$obj->name\", icon_size=32, icon=\"$obj->icon\", flags={\"goes-to-main-inventory\"}, ";
			}
			 * 
			 */
			$file .= "\n   type=\"item\", name=\"$obj->name\", icon_size=32, icon=\"$obj->icon\", ";
			$groups->getGroupData($obj->ref_group);
			$sgroups->getSubGroupData($obj->ref_subgroup);
			$file .= "\n   group=\"$groups->name\", subgroup=\"$sgroups->name\", order=\"$obj->orders\",  ";
			$file .= "\n   stack_size = $obj->stack_size, default_request_amount = $obj->def_request,";
			if (strlen($obj->fuel_value) > 1) {
				$file .= "\n   fuel_value=\"$obj->fuel_value\", fuel_category = \"$obj->fuel_type\",";
			}
			if (strlen($obj->place_result) > 1) {
				$file .= "\n   place_result=\"$obj->place_result\", ";
			}
			$file .= "\n},";
		}
	}
	$file .= "\n\n})";
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $file . $a;
	}
	else {
		return $file;
	}
}

function proto_fluids_lua($mid, $dbco, $media = 0) {
	global $version;
	$groups = new c_groups($dbco);
	$sgroups = new c_subgroups($dbco);
	$fluid = new c_fluids($dbco);
	$p = "control view -> prototypes/fluids.lua";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$file = "\n--automatically generated file $version";
	$file .= "\n--export-date: " . date("Y-M-d", time()) . "\n\n";
	$file .= "data:extend({\n";
	$stmt = $fluid->getFluidList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if (strlen($obj->comment) > 1) {
			$file .= "\n    --ID:$obj->id $obj->comment ";
		}
		else {
			$file .= "\n    --ID:$obj->id";
		}
		$file .= "\n    {";
		$sgroups->getSubGroupData($obj->ref_subgroup);
		$file .= "\n		type = \"fluid\", ";
		$file .= "\n		name = \"$obj->name\", ";
		$file .= "\n		icon_size = \"32\", ";
		$file .= "\n		icon = \"$obj->icon\", ";
		$file .= "\n		default_temperature = $obj->dtemp, ";
		$file .= "\n		max_temperature = $obj->mtemp, ";
		$file .= "\n		heat_capacity = \"$obj->heatc\", ";
		$file .= "\n		base_color = { $obj->bcolor }, ";
		$file .= "\n		flow_color = { $obj->fcolor }, ";
		$file .= "\n		pressure_to_speed_ratio = $obj->pressure_speed, ";
		$file .= "\n		flow_to_energy_ratio = $obj->flow_energy, ";
		$file .= "\n		order = \"$obj->orders\", ";
		$file .= "\n		subgroup = \"$sgroups->name\", ";
		$file .= "\n    },";
	}
	$file .= "\n\n})";
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $file . $a;
	}
	else {
		return $file;
	}
}

function recipes_cfg($mid, $dbco, $media = 0) {
	$rec = new c_recipe($dbco);
	$p = "<br>";
	$p .= "control view -> locale/en/recipe-names.cfg";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$f = "\n[recipe-name]";
	$stmt = $rec->getRecipeList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if (strlen($obj->ig_name) > 1) {
			$f .= "\n$obj->name=$obj->ig_name";
		}
	}
	$f .= "\n";
	$f .= "\n[recipe-description]";
	$stmt = $rec->getRecipeList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if (strlen($obj->ig_desc) > 1) {
			$f .= "\n$obj->name=$obj->ig_desc";
		}
	}
	$a = "<br>";
	$a .= "</pre>";
	$a .= "</div>";
	if ($media == 0) {
		return $p . $f . $a;
	}
	else {
		return $f;
	}
}

function proto_recipes_lua($mid, $dbco, $media = 0) {
	global $version;
	$groups = new c_groups($dbco);
	$sgroups = new c_subgroups($dbco);
	$reccat = new c_recipecat($dbco);
	$item = new c_items($dbco);
	$fluid = new c_fluids($dbco);
	$rec = new c_recipe($dbco);

	$p = "<br>";
	$p .= "control view -> prototypes/recipes.lua";
	$p .= "<div class='frame'><pre class='gray size9'>";
	$x = "\n--automatically generated file $version";
	$x .= "\n--export-date: " . date("Y-M-d", time()) . "\n\n";
	$x .= "data:extend({\n";
	$stmt = $rec->getRecipeList($mid);
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if ($obj->enabled == 'true') {

			$groups->getGroupData($obj->ref_group);
			$sgroups->getSubGroupData($obj->ref_subgroup);
			$reccat->getRCData($obj->ref_category);
			$x .= "\n	--ID:$obj->id $obj->comment";
			$x .= "\n	{";
			$x .= "\n	  type = \"recipe\",";
			$x .= "\n	  name = \"$obj->name\",";
			$x .= "\n	  category = \"$reccat->name\", -- $reccat->comment";
			$x .= "\n	  enabled = \"$obj->enabled\",";
			$x .= "\n	  energy_required = $obj->energy_required,";
			$x .= "\n	  ingredients = {";
			// ingredients-list
			$query = "SELECT * FROM _ingres_items WHERE recipe='$obj->id' AND type='i'";
			$res = $dbco->prepare($query);
			$res->execute();
			while ($iob = $res->fetch(PDO::FETCH_OBJ)) {
				if ($iob->source == 'i') {
					$item->getItemData($iob->ref_item);
					$x .= "\n		{ type = \"item\", name = \"$item->name\" , amount = $iob->number, },";
				}
				else {
					// fluid
					$fluid->getFluidData($iob->ref_item);
					$x .= "\n		{ type = \"fluid\", name = \"$fluid->name\" , amount = $iob->number, },";
				}
			}
			$x .= "\n	  },";
			// results-list
			$x .= "\n	  results = {";
			$query = "SELECT * FROM _ingres_items WHERE recipe='$obj->id' AND type='r'";
			$res = $dbco->prepare($query);
			$res->execute();
			$mp = 0;
			while ($rob = $res->fetch(PDO::FETCH_OBJ)) {
				if ($rob->source == 'i') {
					$item->getItemData($rob->ref_item);
					$x .= "\n		{ type = \"item\", name = \"$item->name\", amount = $rob->number, },";
					if ($mp == 0) {
						$mp_string = "\"$item->name\"";
						$mp++;
					}
				}
				else {
					// fluid
					$fluid->getFluidData($rob->ref_item);
					$x .= "\n		{ type = \"fluid\", name = \"$fluid->name\", amount = $rob->number, },";
					if ($mp == 0) {
						$mp_string = "\"$fluid->name\"";
						$mp++;
					}
				}
			}
			$x .= "\n	  },";
			$x .= "\n	  main_product = " . $mp_string . ",";
			if (strlen($obj->icon) > 1 AND $obj->icon != "icons/colba.png") {
				$x .= "\n	  icon = \"$obj->icon\",";
				$x .= "\n	  icon_size = 32,  ";
			}
			$x .= "\n	  order = \"$obj->orders\", group = \"$groups->name\", subgroup = \"$sgroups->name\",";
			$x .= "\n	},";
			$x .= "\n";
		}
	}
	$x .= "\n})";
	$a = "</pre></div>";
	if ($media == 0) {
		return $p . $x . $a;
	}
	else {
		return $x;
	}
}

?>
