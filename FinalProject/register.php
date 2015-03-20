<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE>
<html>
	<head>
		<title>Account Registration</title>
		<style>
		* {padding:0;margin:0;}
		.goodinput {background-color:#00CC66;margin-top:5px;margin-bottom:5px;}
		.badinput {background-color:#CC6666;margin-top:5px;margin-bottom:5px;}
		.title {font-family: 'Roboto', sans-serif;color:white;text-align:center;font-size: 4.0em;width:700px;margin-left:auto;margin-right:auto;}
		.page {width:100%;height:100%;background-color:#5a5494;}
		.top {height:120px;}
		.middle {width:100%;height:1000px;}
		.formelement {margin-top:5px;margin-bottom:5px;}
		#error {float:left;width:100%;height:100%;background-color:#837eb1;}
		#titleban {height:70%;width:100%;padding: 10px 10px 10px 10px;background-color:#0d083b;}
		#errban {height:30%;}
		#registration {height:300px;width:300px;margin-top:40px;margin-left:auto;margin-right:auto;background-color:#ffffcc;border:2px solid #000000}
		#centerbox {margin-top:30px;margin-bottom:auto;margin-right:auto;margin-left:auto;height:200px;width:200px;}
		</style>
		<script>
		function checkSame(x) {
			var pw = document.getElementById("pw");
			if(x.value.length == 0) {
				x.style.backgroundColor = "#FFFFFF";
				pw.style.backgroundColor = "#FFFFFF";
			}
			else if(x.value == pw.value) {
				x.style.backgroundColor = "#00CC66";
				pw.style.backgroundColor = "#00CC66";
			}
			else {
				x.style.backgroundColor = "#CC6666";
				pw.style.backgroundColor = "#CC6666";
			}
		}
		function checkNameAvailable(x) {
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            		if(x.value.length == 0) x.style.backgroundColor = "#ffffff";
            		else if(xmlhttp.responseText == "1") x.style.backgroundColor = "#00CC66";
            		else x.style.backgroundColor = "#CC6666";
            	}
        	}
			xmlhttp.open("POST","testregister.php",true);

			var params = "name="+x.value;

			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        	xmlhttp.send(params);
		}
		function checkEmailAvailable(x) {
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            		if(x.value.length == 0) x.style.backgroundColor = "#ffffff";
            		else if(xmlhttp.responseText == "1") x.style.backgroundColor = "#00CC66";
            		else x.style.backgroundColor = "#CC6666";
            	}
        	}
			xmlhttp.open("POST", "testregister.php", true);

			var params = "name="+x.value;

			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        	xmlhttp.send(params);
		}
		function submitInfo() {
			console.log("Function called");
			var name = document.getElementById("newname").value;
			var pass = document.getElementById("pw").value;
			var email = document.getElementById("newemail").value;
			var pwc = document.getElementById("pwc").value;
			console.log("variables bound");
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					console.log("ajax called");
					document.getElementById("error").innerHTML = xmlhttp.responseText;
					console.log(xmlhttp.responseText);
				}
			}
			xmlhttp.open("POST", "submitpage.php", true);

			var params = "name="+name+"&pw="+pass+"&pwc="+pwc+"&email="+email;

			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send(params);
		}
		</script>
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
				<div id="registration">
					<div id="centerbox">
						<h4>Register:</h4>
						<div class="formelement" id="nameBox"><input id="newname" type="text" placeholder="Username" onchange="checkNameAvailable(this)"><span style="{opacity:0}"></span></div>
						<div class="formelement"><input id="pw" type="password" placeholder="Password"></div>
						<div class="formelement"><input id="pwc" type="password" placeholder="Re-enter Password" onchange="checkSame(this)"><span></span></div>
						<div class="formelement" id="emailBox"><input id="newemail" type="text" placeholder="Email" onchange="checkEmailAvailable(this)"><span style="{opacity:0}"></span></div>
						<div class="formelement"><button onclick="submitInfo()">Register</button></div>
						<a href="http://web.engr.oregonstate.edu/~tulipanh/Final/login.php">Back to Login</a>
					</div>
				</div>

			</div>
	</div>
	</body>
</html>