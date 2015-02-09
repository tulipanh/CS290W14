<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

$valid = True;

if(!(isset($_GET['action']) && $_GET['action'] == 'end')){
	if(!isset($_SESSION['username'])){
		if($_POST['username'] == '' || $_POST['username'] == null){
			echo '<p>A username must be entered. Click <A HREF="login.php">here</A> to return to the login screen.</p>';
			$valid = False;
		}
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'end'){
	$_SESSION = array();
	session_destroy();
	header("Location: login.php", true);
	die();
}

echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>First Content Page</title>
</head>';

echo '<body>';


if(isset($_POST['username'])){
	$_SESSION['username'] = $_POST['username'];
}

if(!isset($_SESSION['visits'])){
	$_SESSION['visits'] = -1;
}

if($valid){
	$_SESSION['visits']++;
	echo '<p>Hello ' . $_SESSION['username'] . ' you have visited this page ' . $_SESSION['visits'] . ' times before.
	Click <A HREF="content1.php?action=end">here</A> to logout.</p>';
	echo '<p>Otherwise, click <A HREF="content2.php">here</A> to go to the second page of content!</p>';
}

echo '</body>
</html>';
?>