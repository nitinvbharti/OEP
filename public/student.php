<?php
session_start();
require '../includes/connect.inc.php';
if(isset($_SESSION['rollnumber']))
{
	echo "Welcome ".$_SESSION['student_name'];
}
else
{
	header("Location: student_login.php");
}	
if(isset($_POST['logout']))
{
	session_destroy();
	header("Location: student_login.php");
}
?>
<form method="POST" action="student.php">
<input  type="submit" name="logout" value="LogOut">
</form>
