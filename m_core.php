<?php


error_reporting(E_ALL | E_STRICT); 
error_reporting(E_ERROR); 


//include "../../../connect.inc.php";
include "classes/c_mod.php";
include "classes/c_groups.php";
include "classes/c_subgroups.php";
include "classes/c_recipecat.php";
include "classes/c_items.php";
include "classes/c_fluids.php";
include "classes/c_recipe.php";
include "classes/c_technology.php";

$MySQL_User="root";
$MySQL_Passw="";

// db-connection
try {	
	$dbco = new PDO('mysql:host=localhost;dbname=factorio', $MySQL_User, $MySQL_Passw);
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

// Standard URL-Abgriff
$id = get("id", 0);
$mid = get("mid", 0);
$action = get("action", "");
$target = get("target", "");

// Standard Variablen
// Page-Header, -Content, -Footer

function pHeader($title = "", $menu = "main_menu.php", $style = "css/style_st.css") {		
	global $mid, $dbco;
	print "<!DOCTYPE html>\n";
	print "<html lang='de'>\n";
	print "<head>\n";
	print "<title>F-Modder</title>\n";
	print "<meta http-equiv='content-type' content='text/html; charset=ISO-8859-1'>\n";
	print "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";	
	?>
	<link href='http://fonts.googleapis.com/css?family=Maven+Pro|Telex|Cabin+Condensed' rel='stylesheet' type='text/css'>						
	<link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono|Oxygen+Mono' rel='stylesheet' type='text/css'>	
	<?php
	print "<link type='text/css' href='$style' rel='stylesheet' media='screen'>\n";
	print "</head>\n";
	print "<body>\n";

	// main-menü 
	echo "<div class='header'>";
	include $menu;
	echo "</div>";
	echo "<p class='title'><u>$title</u></p>";
}
function pFooter($version = "") {
	echo "<div class='footer'>$version</div>";
	echo "</body></html>";
}
function get($param, $default = 0) {
	//if (!isset($default)) { $default=0; }
	if (isset($_GET[$param])) {
		$x = $_GET[$param];
	}
	else {
		$x = $default;
	}
	if (isset($_POST[$param])) {
		$x = $_POST[$param];
	}
	return $x;
}
function strcut($string, $laenge) {
	if (strlen($string) > ($laenge + 4)) {
		$string = substr($string, 0, $laenge) . " ...";
	}
	return $string;
}
function nf0($wert,$stellen=0) {
	$zahl = number_format($wert, $stellen, ',', '.');
	return $zahl;
}
function fnull($zahl, $stellen) {
	$s = "";
	if ($zahl < 1000000 && $stellen > 6) {
		$s.="0";
	}		
	if ($zahl < 100000 && $stellen > 5) {
		$s.="0";
	}	
	if ($zahl < 10000 && $stellen > 4) {
		$s.="0";
	}
	if ($zahl < 1000 && $stellen > 3) {
		$s.="0";
	}
	if ($zahl < 100 && $stellen > 2) {
		$s.="0";
	}
	if ($zahl < 10 && $stellen > 1) {
		$s.="0";
	}
	$s.=$zahl;
	return $s;
}
function ers($txt) {
	preg_replace("/ß/", "&szlig;", $txt);
	preg_replace("/ü/", "&uuml;", $txt);
	preg_replace("/ö/", "&ouml;", $txt);
	preg_replace("/ä/", "&auml;", $txt);
	return $txt;
}



?>
