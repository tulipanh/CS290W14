<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'password.php';

if(isset($_SESSION['loggedin'])) {
	if($_SESSION['loggedin'] == 1) {
		header("Location: http://web.engr.oregonstate.edu/~tulipanh/Final/homepage.php");
		die();
	}
}
else {
	$_SESSION['loggedin'] = 0;
}

if(isset($_POST['uname']) && isset($_POST['password'])) {
	$un = $_POST['uname'];
	$password = $_POST['password'];

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
	if($mysqli->connect_errno) {
		echo '<script> document.getElementById("error").innerHTML = "Failed to connect to MySQL: (' . $mysqli->connect_errno . ')";</script>';
	}

	if (!$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = '" . $un . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($userExist)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
	}

	while($stmt->fetch());

	if (!$stmt = $mysqli->prepare("SELECT password, numsubs, id FROM users WHERE username = '" . $un . "'")) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
    	echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
	}

	if (!$stmt->bind_result($pw, $numsubs, $id)) {
    	echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $state->errno . ')"</script>';
	}

	$stmt->fetch();

	if($userExist == 0) echo '<script> document.getElementById("error").innerHTML = "That Username does not exist.";</script>';
	else if($pw != $password) {
		echo '<script> document.getElementById("error").innerHTML = "That Password is incorrect.";</script>';
	}
	else {
		$_SESSION['loggedin'] = 1;
		$_SESSION['username'] = $un;
		$_SESSION['id'] = $id;
		$_SESSION['numsubs'] = $numsubs;

		header("Location: http://web.engr.oregonstate.edu/~tulipanh/Final/homepage.php");
		die();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<style>
	* {padding:0;margin:0;}
	.title {font-family: 'Roboto', sans-serif;color:white;text-align:center;font-size: 4.0em;width:700px;margin-left:auto;margin-right:auto;}
	.page {width:100%;height:100%;background-color:#5a5494;}
	.top {height:120px;}
	.middle {width:100%;height:1000px;}
	.formelement {margin-top:5px;margin-bottom:5px;}
	#error {float:left;width:100%;height:100%;background-color:#837eb1;}
	#titleban {height:70%;width:100%;padding: 10px 10px 10px 10px;background-color:#0d083b;}
	#errban {height:30%;}
	#login {height:300px;width:300px;margin-top:40px;margin-left:auto;margin-right:auto;background-color:#ffffcc;border:2px solid #000000}
	#centerbox {margin-top:30px;margin-bottom:auto;margin-right:auto;margin-left:auto;height:200px;width:200px;}
	</style>
</head>
<body>
	<div class="page">
		<div class="top">
			<div id="titleban">
				<h1 class="title">The Game Exchange</h1>
			</div>
			<div id="errban">
				<div id="error">
				</div>
			</div>
		</div>
		<div class="middle">
			<div id="login">
				<div id="centerbox">
					<h3>Login:</h3>
					<form method="post">
						<p class="formelement"><input type="text" name="uname" placeholder="Username"></p>
						<p class="formelement"><input type="password" name="password" placeholder="Password"></p>
						<p class="formelement"><button action="submit">Log In</button></p>
					</form>
					<h4>Don't have and acount?</h4>
					<a href="http://web.engr.oregonstate.edu/~tulipanh/Final/register.php">Register Here</a>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
