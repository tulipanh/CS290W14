<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'password.php';

$username = 'tulipanh';

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
	if($mysqli->connect_errno) {
		echo '<script> document.getElementById("error").innerHTML = "Failed to connect to MySQL: (' . $mysqli->connect_errno . ')";</script>';
	}

	if (!($stmt = $mysqli->prepare("SELECT numimgs FROM submission WHERE subid = 1"))) {
    	echo '<script> document.getElementById("error").innerHTML = "Prepare failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($userExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	echo 'hello' . $userExist;
?>