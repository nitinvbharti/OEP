<?php
/*
<!--
*************************************************

Establish a connection to MySQL database.

*************************************************
-->
*/
$con=mysql_connect('localhost','root','1234') or die("Could not connect".mysql_error());
mysql_select_db("iiitdm");
?>