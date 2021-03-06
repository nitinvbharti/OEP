<?php
session_start();
/*
<!--
*************************************************

This page contains code related to validation of student branch, course opted, eligibility for test 
and redirect students to respective exam based on the choice.

*************************************************
-->
*/

require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 	echo '<div class="row-fluid text-center">';
 	$rollnumber=$_SESSION['rollnumber'];										//Rollnumber of the student
 	$branch=$rollnumber[0].$rollnumber[1].$rollnumber[2];
 	$yr='20'.$rollnumber[3].$rollnumber[4];
 	if($yr<=2013)
 		$cyr='2009';
 	else
 		$cyr='2014';
 	$cur_table_name='curriculum'.'_'.$cyr.'_'.$branch;							//Category of student based on curriculum versions for a branch

if(isset($_POST['go']))
{
$_SESSION['course']=strtoupper($_POST['course']);
	$i=0;
	$j=0;

	$exam=mysql_query("SELECT examtype,semester from test where course_id='$_SESSION[course]' and exam_activation='1'");			$test=mysql_fetch_array($exam);	
	$t=time();
	$dt=Date('Y-m-d',$t);
	$presentyr=$dt[0].$dt[1].$dt[2].$dt[3];										//Current year to create tables accrdingly
	//echo $presentyr;
	$presentm=$dt[5].$dt[6];
	//echo $presentm;
	if($presentm<='6')
		$csem='janmay';
	else
		$csem='julynov';

	$enroll_table='enrollment'.'_'.$csem.'_'.$presentyr.'_'.$cyr.'_'.$branch;
	
 	if(isset($_SESSION['course']))
	{	
	$nm=explode('_', $cur_table_name);
	$roll=mysql_fetch_array(mysql_query("SELECT rollnumber from $enroll_table where course_id='$_SESSION[course]'"));
	$roll_list=explode(',', $roll[rollnumber]);									//List of students enrolled in a course separted by comma
	$i=0;
	while($roll_list[$i])
		$i++;
/////
	$j=0;
	$enrolled=0;
	while($j<=$i)
		{
			if($roll_list[$j]==strtoupper($_SESSION['rollnumber']))
				$enrolled=1;													//Given student is found in the selected course
		$j=$j+1;
		}
	
	if($enrolled=='0')
		echo '<span class="lead" >You are not enrolled in '.$_SESSION[course].' </span>';
	else		
		{
			$exam="SELECT examtype,semester from test where course_id='$_SESSION[course]'";
			$test=mysql_fetch_array(mysql_query($exam));							//Choose type of exam
		
 if(isset($test))
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
		$t=time();
		$dt=Date('Y-m-d',$t);
		$presentyr=$dt[0].$dt[1].$dt[2].$dt[3];

		if($test['semester']=='1')
			$_SESSION['sem']='julynov'.'_'.$presentyr;
		else
			$_SESSION['sem']='janmay'.'_'.$presentyr;
		//echo $_SESSION[sem];

		$chk=mysql_fetch_array(mysql_query("select * from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
		if($chk==NULL)
			{
				$stu_add=mysql_query("INSERT into student_exam_status values('$_SESSION[rollnumber]','$_SESSION[course]','0','0','0','0','0')");
				var_dump($stu_add);
			}
	}
}
}

$examstatus=$_SESSION['examtype'].'_'.'taken';
$val=mysql_fetch_array(mysql_query("select $examstatus from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));


if($val[$examstatus]==='0')
	{
		$_SESSION['examstatus']='0';
		//echo $_SESSION['examstatus'];
		echo '<div class="row">';
		 echo '<div class="span3 offset5"><br/><button class="btn btn-primary btn-large btn-block" type="submit"  onclick="window.location.href=\'exam.php\'"  name="starttest" ><i class="icon-white icon-flag" ></i>Start The Test</button></span></div>';
		 echo '</div>';
		 	}
elseif ($val[$examstatus]=='1')
	{
		$_SESSION['examstatus']='1';
	echo '<span class="lead" >You have already taken '.$_SESSION[course].' test</span>';
	echo '</div>';
	}
else
	{
		if ($val[$examstatus]!=NULL) 
		{
		echo '<div class="row">';
		 echo '<div class="span3 offset5"><br/><button class="btn btn-primary btn-large btn-block" type="submit" name="resumetest" onclick="window.location.href=\'exam.php\'"><i class="icon-white icon-flag" ></i>Resume Test</button></span></div>';
		 echo '</div>';
		}
	else	
		{

			echo '<span class="lead" >NO exam has been set for '.$_SESSION[course].' Course. Consult the related faculty.</span>';
		}
	}
 if($examstatus==NULL)
 		echo '<span class="lead" >You are on wrong page. Please log out</span>';
}




if(!isset($_SESSION['course']))
{
$select=mysql_query("SELECT course_no from $cur_table_name");
 echo '<form action="student.php" method="post" ><select name="course">';
		 while($row=mysql_fetch_array($select))
		  {
		    echo '<option value='.$row['course_no'].'>'.$row['course_no'].'</option> ';
		  }
			 echo '</select>';
	 		echo '<br /><br /><button type="submit" value="go" name="go" class="btn btn-info btn-large" ><i class="icon-ok icon-white" ></i> Select Course</button>';
	 		echo '</form>';
}
	
	}
else
	{

		echo '</br>';
		echo '<span class="lead" >Hello there, What you want to do?</br> logout</span>';
	}

 echo '</div>';
include("footer.php");
?>
