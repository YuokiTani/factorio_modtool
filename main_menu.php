<?php

$minfo = new c_mod($dbco);
$minfo->getMod($mid);

echo "<a href='index.php' class='but'>Start</a>";
echo "<a href='ced_mcore.php?mid=$mid' class='but'>Mod-Core</a>";
echo "<a href='ced_groups.php?mid=$mid' class='but'>Groups</a>";
echo "<a href='ced_subgroups.php?mid=$mid' class='but'>Subgroups</a>";
echo "<a href='ced_reccat.php?mid=$mid' class='but'>Recipe-Categorys</a>";
echo "<a href='ced_items.php?mid=$mid' class='but'>Items</a>";
echo "<a href='ced_fluids.php?mid=$mid' class='but'>Fluids</a>";
echo "<a href='ced_recipes.php?mid=$mid' class='but'>Recipes</a>";
echo "<a href='ced_tech.php?mid=$mid' class='but'>Technology</a>";
echo "<a href='m_tree.php?mid=$mid' class='but'>Tree/Info</a>";
echo "<a href='m_export.php?mid=$mid' class='but'>Export</a>";
echo "<a href='m_about.php?mid=$mid' class='but'>About</a>";

if ($mid > 0) {	
	echo "<span class='sright'>";
	echo "<form action='' method='get'>";	
	echo "<select name='mid' class='eingabe'>";	
	$query = "SELECT * FROM modc ORDER BY id";
	$stmt = $dbco->prepare($query);
	$stmt->execute();
	while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
		if ($obj->id==$mid) {
		echo "<option selected value='$obj->id' class='eingabe'>($obj->id) $obj->name";
		}
		else {
		echo "<option value='$obj->id'>($obj->id) $obj->name";
		}
	}
	echo "</select>";
	echo "<button title='switch to mod'>S</button>";
	//echo "<b>$mid</b> $minfo->name, ";
}
else {
	echo "<span class='sright'> MOD???, ";
}
echo " - ".date("d.m.Y", time());
echo "</form>";
echo "</span>";
