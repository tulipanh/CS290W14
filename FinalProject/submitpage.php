<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'password.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if(!isset($_POST['name'])) {
	echo '<span class="badinput">Please enter a username.</span>';
}
else if(!isset($_POST['pw'])) {
	echo '<span class="badinput">Please enter a password.</span>';
}
else if(!isset($_POST['pwc'])) {
	echo '<span class="badinput">Please enter the password twice.</span>';
}
else if(!isset($_POST['email'])) {
	echo '<span class="badinput">Please enter an email.</span>';
}
else {
	$name = $_POST['name'];
	$password = $_POST['pw'];
	$pwc = $_POST['pwc'];
	$email = $_POST['email'];

	if(strlen($name) > 255) echo '<span class="badinput">That username is too long.</span>';
	else if(strlen($name) == 0) echo '<span class="badinput">Please enter a username./span>';
	else $addname = $name;
	if(strlen($password) > 255) echo '<span class="badinput">That password is too long.</span>';
	else if(strlen($password) == 0) echo '<span class="badinput">Please enter a password.</span>';
	else $addpass = $password;
	if(strlen($email) > 255) echo '<span class="badinput">That email is too long.</span>';
	else if(strlen($email) == 0) echo '<span class="badinput">Please enter an email.</span>';
	else $addmail = $email;
	$numSubs = 0;

	$valid = true;
	if (!$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = '" . $addname . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($userExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	while($stmt->fetch());

	if($userExist > 0) {
		echo '<span class="badinput">That username (' . $addname . ') is unavailable.</span>';
		$valid = false;
	}

	if (!$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = '" . $addmail . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($emailExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	while($stmt->fetch());

	if($emailExist > 0) {
		echo '<span class="badinput">That Email (' . $addmail . ') is unavailable.</span>';
		$valid = false;
	}

	if($addpass != $pwc) {
		echo '<span class="badinput">Those passwords you entered do not match.</span>';
		$valid = false;
	}

	$success = true;
	if($valid){
		if (!($stmt = $mysqli->prepare("INSERT INTO users (username, password, email, numsubs) VALUES (?, ?, ?, ?)"))) {
    		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    		$success = false;
		}
		if (!$stmt->bind_param("sssi", $addname, $addpass, $addmail, $numSubs)) {
    		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    		$success = false;
		}
		if (!$stmt->execute()) {
    		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    		$success = false;
		}
		if($success) echo '<span class="goodinput">Registration Successful! <a href="login.php">Return to Login</a></span>';
	}
}

?>

</body>
</html>
