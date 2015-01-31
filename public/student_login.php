<!DOCTYPE HTML>
<?php

session_start();   //starting the session for user profile page
require '../includes/connect.inc.php';
require_once '../includes/functions.php';

function SignIn()
{
	if(isset($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
		
		$query=mysql_query("SELECT *  FROM students WHERE rollnumber = '$_POST[username]' AND password='$_POST[password]' " ) or die("Died".mysql_error());
		$count = mysql_num_rows($query);
		
		if($count==1)
			{
			$row=mysql_fetch_array($query);
			echo "Welcome ".$row['name']."...";
				$_SESSION['rollnumber']=$_POST['username'];
				$_SESSION['student_name']=$row['name'];
			}
		else
			{
			echo "SORRY... YOU ENTERD WRONG ID AND PASSWORD... PLEASE RETRY...";
			}
	}
	else
	{	
	echo "Please Fill In the Required Fields";
	}
}

if(isset($_POST['submit']) || isset($_SESSION['rollnumber']))
{
    SignIn();
	if(isset($_SESSION['rollnumber']))
	{
	header("Location: student.php");
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
	<form method="POST" action="student_login.php">
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