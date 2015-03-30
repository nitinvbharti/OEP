<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{


 	echo '<div class="row-fluid text-center">';
	$eligible=mysql_fetch_array(mysql_query("select course_id from janmay_2015_2009_coe where rollnumber='$_SESSION[rollnumber]' "));
	$_SESSION['course']=$eligible[0];
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
			$_SESSION['examtype']='supplementary';
		
		}
		if($test['semester']=='1')
			$_SESSION['sem']='julynov_2015';
		else
			$_SESSION['sem']='janmay_2015';
		
	//echo '<script>window.location="exam.php";</script>';

	}
else
	{

		echo '</br>';
		echo '<span class="lead" >No Exam has been set for your Branch</span>';
	}
	//var_dump($_SESSION['examtype']);
$examstatus=$_SESSION['examtype'].'_'.'taken';
$val=mysql_fetch_array(mysql_query("select $examstatus from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
//var_dump($val);
if($val[$examstatus]==='0')
	{
		 echo '<div class="row">';
		 echo '<div class="span3 offset5"><br/><button class="btn btn-primary btn-large btn-block" type="submit"  onclick="window.location.href=\'exam.php\'"  name="starttest" ><i class="icon-white icon-flag" ></i>Start The Test</button></span></div>';
		 echo '</div>';
	}
elseif ($val[$examstatus]=='1')
	{
	echo '<span class="lead" >You have already taken '.$_SESSION[course].' test</span>';
	}
else
	{
	// when some part of test is already done;
		//var_dump($val);
		$_SESSION['prev_duration']=$val[$examstatus];
		echo $_SESSION[prev_duration];
		echo '<div class="row">';
		 echo '<div class="span3 offset5"><br/><button class="btn btn-primary btn-large btn-block" type="submit" name="resumetest" onclick="window.location.href=\'exam.php\'"><i class="icon-white icon-flag" ></i>Resume Test</button></span></div>';
		 echo '</div>';
		//var_dump($_SESSION['prev_duration']);
	}
 echo '</div>';
 	
}

include("footer.php");
?>
