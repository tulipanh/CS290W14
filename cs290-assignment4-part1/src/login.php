<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();
session_unset();

echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login Page</title>
</head>
<body>';

echo '<form action="content1.php" method="post">
<div>Enter Username: <input type="text" name="username"></div>
<div><input type="submit" value="Login"></div>';
echo '</body>
</html>';
?>