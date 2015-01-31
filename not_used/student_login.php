<!DOCTYPE HTML>
<?php
session_start();


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
	<link rel="stylesheet" type="text/css" href="style-sign.css" >
	<head>
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