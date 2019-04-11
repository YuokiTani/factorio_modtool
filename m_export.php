<?php

include 'm_core.php';
//$version = "v0.01 - 215-Jul-13";
//$version = "v0.02 - 215-Jul-15"; // basic directory-structure and file-output works
//$version = "v0.03 - 215-Aug-17"; // changed to mod-directory file-output
$version = "v0.04 - 216-Mrz-03"; // added file-date included

pHeader("Export");

if ($action == "export") {
	// build the whole f. mod
	if ($mid > 0) {
		$wr=0;		
		//$directory="_export_";
		$minfo = new c_mod($dbco);
		$minfo->getMod($mid);
		$directory=$minfo->directory;
		
		echo "<div class='mono'>";					
		echo "<br>make directorys ... ";
		mkdir("$directory");
		mkdir("$directory/locale");
		mkdir("$directory/locale/en");
		mkdir("$directory/prototypes");
		mkdir("$directory/graphics");
		mkdir("$directory/graphics/icons");
		echo "<span class='green'>done !</span>";

		include "m_code2screen.php";

		echo "<br>create info.json ... ";
		$handle = fopen("$directory/info.json", 'w');
		$i = "\n{";
		$i.= "\n\"name\": \"$minfo->name\",";
		$i.= "\n\"title\": \"$minfo->title\",";
		$i.= "\n\"version\": \"$minfo->version\",";
		$i.= "\n\"date\": \"$minfo->datum\",";
		$i.= "\n\"author\": \"$minfo->author\",";
		$i.= "\n\"dependencies\": [$minfo->dependencies],";
		$i.= "\n\"description\": \"$minfo->description\"";
		$i.= "\n}";
		$wr=fwrite($handle, $i);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create data.lua ... ";
		$handle = fopen("$directory/data.lua", 'w');
		$i = "\n";
		$i.="\n require \"util\"";
		$i.="\n require (\"prototypes.groups\")";
		$i.="\n require (\"prototypes.items\")";
		$i.="\n require (\"prototypes.fluids\")";
		$i.="\n require (\"prototypes.recipes\")";
		$i.="\n --require (\"prototypes._entitys\")";
		$i.="\n --require (\"prototypes._externs\")";
		$i.="\n --require (\"prototypes.technology\")";
		$wr=fwrite($handle, $i);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create prototypes/z_groups.lua ... ";
		$handle = fopen("$directory/prototypes/z_groups.lua", 'w+');
		$fw=proto_itemgroups_lua($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create prototypes/z_items.lua ... ";
		$handle = fopen("$directory/prototypes/z_items.lua", 'w+');
		$fw=proto_items_lua($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create prototypes/z_fluids.lua ... ";
		$handle = fopen("$directory/prototypes/z_fluids.lua", 'w+');
		$fw=  proto_fluids_lua($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create prototypes/z_recipes.lua ... ";
		$handle = fopen("$directory/prototypes/z_recipes.lua", 'w+');
		$fw=proto_recipes_lua($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		/*
		echo "<br>create prototypes/technology.lua ... ";
		$handle = fopen("$directory/prototypes/technology.lua", 'w+');
		$fw="--technology";
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";
		*/	

		echo "<br>create locale/en/item-names.cfg ... ";
		$handle = fopen("$directory/locale/en/item-names.cfg", 'w+');
		$fw = items_cfg($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		echo "<br>create locale/en/recipe-names.cfg ... ";
		$handle = fopen("$directory/locale/en/recipe-names.cfg", 'w+');
		$fw = recipes_cfg($mid, $dbco, 1);
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";

		/*
		echo "<br>create locale/en/technology-names.cfg ... ";
		$handle = fopen("$directory/locale/en/technology-names.cfg", 'w+');
		$fw="[technology-name]";
		$fw.="[technology-description]";
		$wr=fwrite($handle, $fw);
		fclose($handle);
		$sum_bytes+=$wr;
		echo "<span class='green'>done !</span> (".nf0($wr)." Bytes written)";
		*/

		echo "<br><br>";
		echo "<span class='green'>in total ".nf0($sum_bytes)." Bytes written</span> ... yes thats a lot of tiny little bits ... <span class='yellow'>now fingers crossed for work ...</span><br>";
		echo "all created files are in <span class='green'><a href='$directory' target='_blank'>$directory</a> <- click for check</span>";
		echo "</div>";
	}
	else {
		echo "<p class='fehler'>You need to select a mod first.</p>";
	}
}
else {
	echo "You need write-access to your directory where this program is installed !";		
	echo "<div class='title'><a href='m_export.php?mid=$mid&action=export'><img src='icons/package-up.png' height='32px'>Create Mod-Files from DB</a></div>";
}	

pFooter($version);
?>
