<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 echo '<div class="row-fluid text-center">';
 
if(isset($_POST['complete']) || isset($_SESSION['complete']))
 {
  //$cid=$_SESSION[tid];
  unset($_SESSION['course']);
  $_SESSION['complete']=1;
  mysql_query("update exam_taken set done=1, duration='$_SESSION[duration]' where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' ");
 }
//else 
 {
 if(isset($_POST['go']) || isset($_SESSION['course']))
  {
    if(!isset($_SESSION['course']))
	 {
	  //echo 'asdfasf';
      $_SESSION['course']=$_POST['course'];
	 }
   ?>