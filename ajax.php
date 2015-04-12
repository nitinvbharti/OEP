<?php
include("connect.php");
require("includes/functions.php");
session_start();
//Connect to MySQL Server
$con=mysql_connect('localhost','root','1234') or die("Could not connect".mysql_error());
mysql_select_db("iiitdm");

$query = "UPDATE login set loggedin='0' WHERE rollnumber='$_SESSION[rollnumber]'";

$qry_result = mysql_fetch_array(mysql_query($query));
echo $_SESSION[rollnumber];
?>