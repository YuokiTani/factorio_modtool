<?php

include 'm_core.php';
$version = "v0.01 - 215-Jul-13";

pHeader("Modifikation Options/Information");

echo "Select/Change Mod";

$minfo = new c_mod($dbco);
if ($action == 'New') {
    $minfo->newMod();    
    
}
if ($action=="Delete") {
    echo "<p class='fehler'>no way ... ";
}


$query = "SELECT * FROM modc ORDER BY id";
$stmt = $dbco->prepare($query);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    //return $stmt->fetch(PDO::FETCH_OBJ);
    while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
        echo "<li><a href='ced_mcore.php?mid=$obj->id'>$obj->title ($obj->name) #$obj->id#</a>";
    }
	echo "<p><a href='ced_mcore.php?action=New' class='but-s'>Create New Mod</a>";
}
else {
    echo "<p class='fehler'>no mod found in database</p>";
    exit;
}

// show Modinfo if Mod Selected
if ($mid > 0) {
    $minfo->getMod($mid);

    if ($action == 'Update') {
        $minfo->name = get("name");
        $minfo->title = get("title");
        $minfo->version = get("version");
        $minfo->datum = get("date");
        $minfo->author = get("author");
        $minfo->dependencies = get("depend");
        $minfo->description = get("descrp");
		$minfo->directory = get("directory");
        $minfo->updateMod();
    }

    echo "<p>Mod-Header";
    echo "<form action='' method='get'>";
    echo "<table class='lst'>";
    echo "<tr><td>Name:		</td><td><input type='text' name='name' value='$minfo->name' size='24'></td></tr>";
    echo "<tr><td>Title:	</td><td><input type='text' name='title' value='$minfo->title' size='64'></td></tr>";
    echo "<tr><td>Version:	</td><td><input type='text' name='version' value='$minfo->version' size='12' class='ic'></td></tr>";
    echo "<tr><td>Date:		</td><td><input type='text' name='date' value='$minfo->datum' size='12' class='ic'></td></tr>";
    echo "<tr><td>Author:	</td><td><input type='text' name='author' value='$minfo->author' size='32'></td></tr>";
    echo "<tr><td>Dependencies:	</td><td><input type='text' name='depend' value='$minfo->dependencies' size='64' placeholder='\"base >= 0.11\"'></td></tr>";
    echo "<tr><td>Description:	</td><td><input type='text' name='descrp' value='$minfo->description' size='64'></td></tr>";
	echo "<tr><td>Mod-Path:	</td><td><input type='text' name='directory' value='$minfo->directory' size='64'></td></tr>";
    echo "<input type='hidden' name='mid' value='$mid'>";
    echo "<tr><td align='left'></td></td><td align='right'><input type='submit' name='action' value='Update' class='button'></td></tr>";
    echo "</table>";
    echo "</form>";
    echo "<br>";
    //echo "<a href='ced_mcore.php?mid=$mid&action=create' target='_blank'>create info.json</a>";


    echo "control view -> info.json";
    echo "<div class='gray frame'>";
    echo "<tt>";
    echo "{";
    echo "<br>\"name\": \"$minfo->name\",";
    echo "<br>\"title\": \"$minfo->title\",";
    echo "<br>\"version\": \"$minfo->version\",";
    echo "<br>\"date\": \"$minfo->datum\",";
    echo "<br>\"author\": \"$minfo->author\",";
    echo "<br>\"dependencies\": [$minfo->dependencies],";
    echo "<br>\"description\": \"$minfo->description\",";
    echo "<br>}";
    echo "</tt>";
    echo "</div>";
}

pFooter($version);
?>
