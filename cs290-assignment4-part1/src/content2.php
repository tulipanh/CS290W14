<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Second Content Page</title>
</head>
<body>';

if(isset($_SESSION['username'])){
	echo $_SESSION['username'] . ' click <A HREF="content1.php">here</A> to return to the first page of content.';
}

if(!isset($_SESSION['username'])){
	echo 'You need a username to access this content. Click <A HREF="login.php">here</A> to go to the login screen.';
}

echo '</body>
</html>';
?>