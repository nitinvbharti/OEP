<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 echo '<div class="row-fluid text-center">';
 /*
if(isset($_POST['complete']) || isset($_SESSION['complete']))
 {
  unset($_SESSION['course']);
  $_SESSION['complete']=1;
  mysql_query("update student_exam_status set done=1, duration='$_SESSION[duration]' where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' ");
 }
else

 {
 if(isset($_POST['go']) || isset($_SESSION['course']))
  {
    if(!isset($_SESSION['course']))
	 {
	  //echo 'asdfasf';
      $_SESSION['course']=$_POST['course'];
	 }
*/  
 
	
	$eligible=mysql_fetch_array(mysql_query("select course_id from enroll where rollnumber='$_SESSION[rollnumber]' "));
	//var_dump($eligible);
	//$_SESSION['course']="COM305";
	$_SESSION['course']=$eligible[0];
	echo $_SESSION['course'];
	$exam="SELECT examtype,semester from test where course_id='$_SESSION[course]'";
	$test=mysql_fetch_array(mysql_query($exam));
 	//$_SESSION['examtype']=$test[0];
 	//var_dump($test);
 if($test)
	{
		if($test[0]=='1')
		{	$_SESSION['examtype']='q1';
			}
		elseif ($test[0]=='2') {
			$_SESSION['examtype']='q2';
	
		}
		elseif ($test[0]=='3') {
			$_SESSION['examtype']='midsem';
	}
		elseif ($test[0]=='4') {
			$_SESSION['examtype']='endsem';
		
		}
		elseif ($test[0]=='5') {
			$_SESSION['examtype']='Supplementary';
		
		}
		if($test['semester']=='1')
			$_SESSION['sem']='julynov_2015';
		else
			$_SESSION['sem']='janmay_2015';
		echo "You test will start in Five minutes";
		//header('Refresh: 3;url=exam.php');
 		// header("Refresh: 5;url=exam.php");
	//date ( string $format [, int $timestamp = time() ] )
	echo '<script>window.location="exam.php";</script>';

	}

else
	{

		echo "</br>NO exam has been activated for you";
		//echo '<script>window.location="exam.php";</script>';
	}




 
	
echo '</div>';
}
include("footer.php");
?>
