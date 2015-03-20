<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'password.php';

if(isset($_POST['logout'])) {
	unset($_SESSION['username']);
	unset($_SESSION['id']);
	unset($_SESSION['numsubs']);
	$_SESSION['loggedin'] = 0;
}

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 1) {
	header("Location: http://web.engr.oregonstate.edu/~tulipanh/Final/login.php");
	die();
}

if(isset($_POST['text']) && isset($_POST['title'])) {
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
	if($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	$_SESSION['numsubs'] = ($_SESSION['numsubs']+1);

	$filename = './subtext/' . $_SESSION['id'] . '-' . $_SESSION['numsubs'] . '.txt';
	$contents = $_POST['text'];

	$outfile = fopen($filename, 'w');
	fputs($outfile, $contents);
	fclose($outfile);

	$mysqli->query("UPDATE users SET numsubs = " . $_SESSION['numsubs'] . " WHERE id = " . $_SESSION['id']);
	$numimgs = 1;
	$active = 1;
	$date = date("Y-m-d");

	if (!($stmt = $mysqli->prepare("INSERT INTO submission(userid, subnum, numimgs, title, date, active) VALUES (?, ?, ?, ?, ?, ?)"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if (!$stmt->bind_param("iiissi", $_SESSION['id'], $_SESSION['numsubs'], $numimgs, $_POST['title'], $date, $active)) {
    	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	header("Location: http://web.engr.oregonstate.edu/~tulipanh/Final/submitimage.php");
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Game Exchange Home</title>
	<style>
	* {padding:0;margin:0;}
	.title {font-family: 'Roboto', sans-serif;color:white;text-align:center;font-size: 4.0em;width:700px;margin-left:auto;margin-right:auto;}
	.text {font-family: 'Roboto', sans-serif;color:white;text-align:center;background-color:#837eb1;}
	.page {width:100%;height:2000px;background-color:#5a5494;}
	.top {height:120px;}
	.middle {width:100%;height:1000px;}
	.formelement {margin-top:5px;margin-bottom:5px;}
	.histab {border:2px solid #aaaaaa;background-color:#ffffcc;margin-left:auto;margin-right:auto;}
	.histab > td {border:1px solid #aaaaaa;}
	.itempic {max-height:100%;width:auto;}
	.pics {height:120px;width:90px;float:left;}
	.mainad {font-size:1.5em;font-family: 'Roboto', sans-serif;margin-top:20px;}
	#subform {width:600px;margin-left:auto;margin-right:auto;}
	#main {float:left;width:85%;}
	#mainhead {margin-left:auto;margin-right:auto;width:250px;height:30px;margin-top:10px;margin-bottom:10px;}
	#maintable {margin-right:20px;margin-left:20px;}
	#logout {width:60px;height:20px;border:2px solid #aaaaaa;background-color:#837eb1;cursor:pointer;cursor:hand;}
	#logdiv {width:70px;height:20px;margin-right:auto;margin-left:auto;margin-top:5px;margin-bottom:5px;}
	#subdiv {width:120px;height:20px;margin-right:auto;margin-left:auto;margin-top:5px;margin-bottom:5px;}
	#sub {width:115px;height:20px;border:2px solid #aaaaaa;background-color:#837eb1;cursor:pointer;cursor:hand;}
	#subtoimg {width:250px;height:20px;border:2px solid #aaaaaa;background-color:#837eb1;cursor:pointer;cursor:hand;}
	#subtoimgdiv {width:250px;height:20px;margin-right:auto;margin-left:auto;margin-top:5px;margin-bottom:5px;}
	#history{margin-top:10px;}
	#who {margin: 5px 0 5px 0;}
	#user {border:2px solid #aaaaaa;margin-left:auto;margin-right:auto;cursor:default;}
	#error {float:left;width:100%;height:100%;background-color:#837eb1;}
	#sidebar {float:left;width:15%;height:100%;background-color:#0d083b;}
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
				<div id="error"></div>
			</div>
		</div>
		<div class="middle">
			<div id="sidebar">
				<div id="logdiv"><form method="post"><button id="logout" class="text" action="submit" name="logout" value="1">Log Out</button></form></div>
				<div id="who">
					<table id="user" class="text" >
						<tr>
							<td>Logged In As:</td>
							<td>
								<?php
								echo $_SESSION['username'];
								?>
							</td>
						</tr>
					</table>
				</div>
				<div id="subdiv"><button onclick="location.href = 'http://web.engr.oregonstate.edu/~tulipanh/Final/submit.php';" id="sub" class="text">Submit New Ad</button></div>
				<div id="history">
					<div><h3 class="text">Submission History</h3></div>
					<div>
						<table class="histab">
<?php
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "tulipanh-db", $myPassword, "tulipanh-db");
	if($mysqli->connect_errno) {
		echo '<script> document.getElementById("error").innerHTML = "Failed to connect to MySQL: (' . $mysqli->connect_errno . ')";</script>';
	}

if (!($stmt = $mysqli->prepare("SELECT subnum, title FROM submission WHERE userid = " . $_SESSION['id'] . " AND active = 1"))) {
    echo '<script> document.getElementById("error").innerHTML = "Prepare failed: (' . $mysqli->errno . ')";</script>';
}

if (!$stmt->execute()) {
    echo '<script> document.getElementById("error").innerHTML = "Execute failed: (' . $mysqli->errno . ')";</script>';
}

if (!$stmt->bind_result($vusub, $title)) {
    echo '<script> document.getElementById("error").innerHTML = "Binding output parameters failed: (' . $stmt->errno . ')"</script>';
}

while($stmt->fetch()) {
	echo '<tr class="histab"><td class="histab"><li><a href="http://web.engr.oregonstate.edu/~tulipanh/Final/content.php?vuid=' . $_SESSION['id'] .'&vusub=' . $vusub . '">' . $title . '</a></li></td><tr>';
}
?>
						</table>
					</div>
				</div>
			</div>		
			<div id="main">
				<div id="mainhead">
					<h2 class="text">Submission Form</h2>
				</div>
				<div id="maintable">
					<div id="subform">
						<form method="post">
						<textarea name="title" rows="1" cols="80" placeholder="Title"></textarea>
						<textarea name="text" rows="10" cols="80">
						</textarea><br /><br /> 
						<div id="subtoimgdiv"><button action="submit" id="subtoimg" class="text">Save Text/Go To Upload Images</button></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
