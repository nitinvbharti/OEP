<?php

session_start();   //starting the session for user profile page
require '../includes/connect.inc.php';
require_once '../includes/functions.php';


?>

<!DOCTYPE HTML>



<?php


if(isset($_POST['submit']) || isset($_SESSION['faculty_id']))
{
    SignIn();
	if(isset($_SESSION['faculty_id']))
	{
	header("Location: faculty.php");
	}
}
else
{
?>
	<html>
	<head>
	<title>Sign-In</title>
	<link rel="stylesheet" type="text/css" href="style-sign.css">
	</<head>
	<body id="body-color">
	<div id="Sign-In">
	<fieldset style="width:30%"><legend>LOG-IN HERE</legend>
	<form method="POST" action="faculty_login.php">
	User <br><input type="text" name="username" size="40"><br>
	Password <br><input type="password" name="password" size="40"><br>
	<input type="submit" name="submit" value="Log-In">
	</form>
	</fieldset>
	</div>	
	</body>
	</html> 

<?php
}
?>