<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include 'password.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//This section alters the database if the user chose to add an item

$nnvalid = True;//is new name valid?
$ncvalid = True;//is new category valid?
$nlvalid = True;//is new length valid?

if(isset($_GET['newname']) && isset($_GET['newcategory']) && isset($_GET['newlength'])) {
	if(strlen($_GET['newname']) > 255) {
		echo "Error: Name entry too long!<br>";
		$nnvalid = False;
	}
	else if(strlen($_GET['newname']) == 0) {
		echo "Error: Name entry is empty!<br>";
		$nnvalid = False;
	}
	else $addName = $_GET['newname'];

	if(strlen($_GET['newcategory']) > 255) {
		echo "Error: Category entry too long!<br>";
		$ncvalid = False;
	}
	else if(strlen($_GET['newcategory']) == 0) {
		$addCat = NULL;
	}
	else $addCat = $_GET['newcategory'];

	if(strlen($_GET['newlength']) == 0) {
		$addLen = NULL;
	}
	else if(!is_numeric($_GET['newlength'])) {
		echo "Error: length entry is not numeric!<br>";
		$nlvalid = False;
	}
	else if(!(floatval($_GET['newlength']) == intval($_GET['newlength']))) {
		echo "Error: length entry is not an integer<br>";
		$nlvalid = False;
	}
	else if(intval($_GET['newlength']) < 0) {
		echo "Error: Length entry less than zero!<br>";
		$nlvalid = False;
	}
	else $addLen = intval($_GET['newlength']);

	if($nnvalid && $ncvalid && $nlvalid) {
		$rented = 0;
		
		if (!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length, rented) VALUES (?, ?, ?, ?)"))) {
    		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("ssii", $addName, $addCat, $addLen, $rented)) {
    		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
    		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
}

//This section alters the database if the user chose to delete one of the videos
if(isset($_GET['delete_this'])) {
	$mysqli->query("DELETE FROM videos WHERE id = " . $_GET['delete_this']);
}

//This section alters the database if the user chose to checkout or return one of the videos
if(isset($_GET['checkout_this'])) {
	$mysqli->query("UPDATE videos SET rented = " . 1 . " WHERE id = " . $_GET['checkout_this']);
}

if(isset($_GET['return_this'])) {
	$mysqli->query("UPDATE videos SET rented = " . 0 . " WHERE id = " . $_GET['return_this']);
}

//This section deletes and reinitializes the database if the user chose to delete all of the videos
if(isset($_GET['delete_all'])) {
	$mysqli->query("DROP TABLE IF EXISTS videos");
	$mysqli->query("CREATE TABLE  `tulipanh-db`.`videos` (
	`id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`name` VARCHAR( 255 ) NULL DEFAULT NULL ,
	`category` VARCHAR( 255 ) NULL DEFAULT NULL ,
	`length` INT( 3 ) NULL DEFAULT NULL ,
	`rented` TINYINT( 1 ) NULL DEFAULT NULL
	)");
}

//Start of the HTML content
echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Database Test</title>
</head>
<body>';

//Form for adding new items to the database
echo '<form method="get">
<fieldset>
<legend>Add Video:</legend>
<div>Item Name: <input type="text" name="newname"></div>
<div>Item Category: <input type="text" name="newcategory"></div>
<div>Item Length: <input type="text" name="newlength"></div>
<div><input type="submit" value="Add"></div>
</fieldset>
</form>';

//Setting up mysqli queries for building the categories drop-down
if (!($catstmt = $mysqli->prepare("SELECT category FROM videos"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$catstmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$out_cat = NULL;

if (!$catstmt->bind_result($out_cat)) {
    echo "Binding output parameters failed: (" . $catstmt->errno . ") " . $catstmt->error;
}

//Form for category filtering
echo '<form method="get">
<fieldset>
<legend>Show by Category:</legend>
<div>Category: 
<action="">
<select name="show_cat">';
echo '<option value="all">Show All</option>';

//Looping to populate category list
$catarr = array(NULL);
$already = False;
while ($catstmt->fetch()) {
	foreach($catarr as $value){
		if($out_cat == $value) $already = True;
	}
	if(!$already) {
		echo '<option value="' . $out_cat . '">' . $out_cat . '</option>';
		array_push($catarr, $out_cat);
	}
	$already = False;
}

echo '</select>
</div>
<div><input type="submit" value="Filter Table"</div>
</fieldset>
</form>';


//This section provides an option for the user to delete all entries from the table
echo '<form method="get">
<fieldset>
<legend>Delete All:</legend>
<div><button name="delete_all" type="submit" value="yes">Delete All Videos</button></div>
</fieldset>
</form>';

//Setting up mysqli queries and other variables for populating the table
if (!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM videos"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$out_id = NULL;
$out_name = NULL;
$out_category = NULL;
$out_length = NULL;
$out_rented = NULL;

if (!$stmt->bind_result($out_id, $out_name, $out_category, $out_price, $out_rented)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

//Building the table, including filtering categories if necessary
echo '<fieldset>
<legend>Item Table:</legend>';
echo '<table border="1">
<tr><td>Id</td><td>Name</td><td>Category</td><td>Length</td><td>Status</td></tr>';
$showCat = 'all';
if(isset($_GET['show_cat'])) $showCat = $_GET['show_cat'];
while ($stmt->fetch()) {
	if(($out_category == $showCat) || ($showCat == 'all')) {
		echo '<tr><td>' . $out_id . '</td><td>' . $out_name . '</td><td>' . $out_category . '</td><td>' . $out_price . '</td>';
		if($out_rented == 1) {
			echo '<td>Checked Out</td>';
			echo '<td><form method = "get"><button name="return_this" type="submit" value="' . $out_id . '">Check-In Item</button></form></td>';
		}
		else {
			echo '<td>Available</td>';
			echo '<td><form method = "get"><button name="checkout_this" type="submit" value="' . $out_id . '">Check-Out Item</button></form></td>';
		}
		echo '<td><form method = "get"><button name="delete_this" type="submit" value="' . $out_id . '">Delete Item</button></form></td></tr>';
	}
}
echo '</fieldset>';

echo '</body>
</html>';
?>
