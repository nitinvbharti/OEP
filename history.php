<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 echo '<div class="row-fluid text-center">';
 $i=0;
 $test_given=array();
$sql=mysql_query(" select test_id from exam_taken where rollnumber='$_SESSION[rollnumber]'");
 while($test_given[i]=mysql_fetch_array($sql))
  	 {
  	 	$sql_courses=mysql_fetch_array(mysql_query("select course_id from test where test_id='$test_given[$i]'");
  	 		echo "Course Tested".$sql_courses[0];
  	 }
   $i--;
  // $sql_courses=mysql_query("select course_id from test where test_id=$");
  // while () {
   //	echo ;
   }
   ?>
