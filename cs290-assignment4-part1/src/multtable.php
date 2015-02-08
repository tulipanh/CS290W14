<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Multiplication Table</title>
</head>
<body>';

$valid = True;

if(ctype_digit($_GET['min-multiplicand'])) $minMC = intval($_GET['min-multiplicand']);
elseif(!isset($_GET['min-multiplicand'])){
	echo '<p>Missing parameter min-multiplicand.</p>';
	$valid = False;
}
else{
	echo '<p>min-multiplicand must be an integer.</p>';
	$valid = False;
}

if(ctype_digit($_GET['max-multiplicand'])) $maxMC = intval($_GET['max-multiplicand']);
elseif(!isset($_GET['max-multiplicand'])){
	echo '<p>Missing parameter max-multiplcand.</p>';
	$valid = False;
}
else{
	echo '<p>max-multiplicand must be an integer.</p>';
	$valid = False;
}

if(ctype_digit($_GET['min-multiplier'])) $minML = intval($_GET['min-multiplier']);
elseif(!isset($_GET['min-multiplier'])){
	echo '<p>Missing parameter min-multiplier.</p>';
	$valid = False;
}
else{
	echo '<p>min-multiplier must be an integer.</p>';
	$valid = False;
}

if(ctype_digit($_GET['max-multiplier'])) $maxML = intval($_GET['max-multiplier']);
elseif(!isset($_GET['max-multiplier'])){
	echo '<p>Missing parameter max-multiplier.</p>';
	$valid = False;
}
else{
	echo '<p>max-multiplier must be an integer.</p>';
	$valid = False;
}

if($valid && ($minMC > $maxMC)){
	echo '<p>Minimum multiplicand larger than maximum.</p>';
	$valid = False;
}

if($valid && ($minML > $maxML)){
	echo '<p>Minimum multiplier larger than maximum</p>';
	$valid = False;
}

if($valid){
	echo "<table border=\"1\">";
	for($i = 0; $i < ($maxMC - $minMC + 2); $i++){
		echo "<tr>";
		for($j = 0; $j < ($maxML - $minML + 2); $j++){
			if($i == 0){
				if($j == 0) echo "<td></td>";
				else echo "<td>" . ($minML + $j - 1) . "</td>";
			}
			else{
				if($j == 0) echo "<td>" . ($minMC + $i - 1) . "</td>";
				else echo "<td>" . ($minML + $j - 1)*($minMC + $i - 1) . "</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
}

echo '</body>
</html>';
?>