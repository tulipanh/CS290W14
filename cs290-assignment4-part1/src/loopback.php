<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Return JSON String</title>
</head>
<body>';


if($_SERVER['REQUEST_METHOD'] === 'GET'){
	$params = $_GET;
	
	if(empty($params)) $params = null;

	$getstring = array(
		"Type" => "GET",
		"parameters" => $params
		);
	$return = json_encode($getstring);
	echo '<p>' . $return . '</p>';
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
	$params = $_POST;

	if(empty($params)) $params = null;

	$poststring = array(
		"Type" => "POST",
		"parameters" => $params
		);
	$return = json_encode($poststring);
	echo '<p>' . $return . '</p>';
}

echo '</body>
</html>';
?>