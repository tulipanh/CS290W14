<?php
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if(isset($_POST['name'])) {
	$name = $_POST['name'];

	if (!$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = '" . $name . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($userExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	while($stmt->fetch());

	if($userExist > 0) echo '0';
	else echo '1';
}
else if(isset($_POST['email'])) {
	$email = $_POST['email'];

	if (!$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = '" . $email . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($emailExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	while($stmt->fetch());

	if($emailExist > 0) echo '0';
	else echo '1';
}
?>