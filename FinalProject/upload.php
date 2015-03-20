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

if(isset($_FILES["fileToUpload"])) {
    $target_dir = "./images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $success = 0;
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo '<script> document.getElementById("error").innerHTML = "File is an image - " . $check["mime"] . ".";</script>';
            $uploadOk = 1;
        } else {
            echo '<script> document.getElementById("error").innerHTML = "File is not an image.";</script>';
            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        echo '<script> document.getElementById("error").innerHTML = "Sorry, file already exists.";</script>';
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo '<script> document.getElementById("error").innerHTML = "Sorry, your file is too large.";</script>';
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo '<script> document.getElementById("error").innerHTML = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";</script>';
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo '<script> document.getElementById("error").innerHTML = "Sorry, your file was not uploaded.";</script>';
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            rename($target_file, '' . $target_dir . $_SESSION['id'] . '-' . $_SESSION['numsubs'] . '-1.png');
            echo '<script> document.getElementById("error").innerHTML = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";</script>';
            $success = 1;
        } 
        else {
            echo '<script> document.getElementById("error").innerHTML = "Sorry, there was an error uploading your file.";</script>';
        }
    }
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
    .badinput {background-color:#CC6666;margin-top:5px;margin-bottom:5px;}
    #filetab {margin-left:auto;margin-right:auto;background-color:#ffffcc;border:2px solid #aaaaaa;}
    #subform {width:600px;margin-left:auto;margin-right:auto;}
    #main {float:left;width:85%;}
    #mainhead {margin-left:auto;margin-right:auto;width:250px;height:30px;margin-top:10px;margin-bottom:10px;}
    #maintable {margin-right:20px;margin-left:20px;}
    #logout {width:60px;height:20px;border:2px solid #aaaaaa;background-color:#837eb1;cursor:pointer;cursor:hand;}
    #logdiv {width:70px;height:20px;margin-right:auto;margin-left:auto;margin-top:5px;margin-bottom:5px;}
    #subdiv {width:160px;height:20px;margin-right:auto;margin-left:auto;margin-top:5px;margin-bottom:5px;}
    #sub {width:150px;height:20px;border:2px solid #aaaaaa;background-color:#837eb1;cursor:pointer;cursor:hand;}
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
                    <h2 class="text">Upload Images</h2>
                </div>
                <div id="maintable">
                    <div id="subform">
                        <?php
                        if($success == 1) {
                            echo '<div id="subdiv"><button onclick="location.href = \'http://web.engr.oregonstate.edu/~tulipanh/Final/homepage.php\';" id="sub" class="text">Return To Homepage</button></div>';
                        }
                        else {
                            echo '<form action="upload.php" method="post" enctype="multipart/form-data">
                            <span class="badinput"> Something Went Wrong, please try again.</span>
                            <table id="filetab">
                                <tr><td>
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                </td></tr>
                                <tr><td>
                                <input type="submit" value="Upload Image" name="submit">
                                </td></tr>
                            <table>
                        </form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>