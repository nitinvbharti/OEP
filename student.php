<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 	echo '<div class="row-fluid text-center">';
// 	$course_count="select count(course_id) from $branch_enroll_table"
 //	$branch_enroll_table=
 	$rollnumber=$_SESSION['rollnumber'];
 	$branch=$rollnumber[0].$rollnumber[1].$rollnumber[2];
 	$yr='20'.$rollnumber[3].$rollnumber[4];
 	if($yr<=2013)
 		$cyr='2009';
 	else
 		$cyr='2014';
 	$cur_table_name='curriculum'.'_'.$cyr.'_'.$branch;
    //	echo $cur_table_name;
 	/*
 	////////////
 	$ccount=0;
 	$select_course=mysql_query("SELECT course_no from $cur_table_name");
 	while($course=mysql_fetch_array($select_course))
		{//var_dump( $course);
		$course_list[$ccount]=$course['course_no'];
		$ccount=$ccount+1;
		}
*/
if(isset($_POST['go']))
{
$_SESSION['course']=strtoupper($_POST['course']);
	$i=0;
	$j=0;
	//$active_xams=array();
//	while($i<5)
//	{
		$exam=mysql_query("SELECT examtype,semester from test where course_id='$_SESSION[course]' and exam_activation='1'");
		//echo $isactive[0];
		//if($isactive[0]=='1')
	//	{
		//	$exam="SELECT examtype,semester from test where course_id='$_SESSION[course]'";
			$test=mysql_fetch_array($exam);
			
		//	var_dump($test);
		//	break;
		//}
		
	//	$i=$i+1;
	//}		
	$t=time();
	$dt=Date('Y-m-d',$t);
	$presentyr=$dt[0].$dt[1].$dt[2].$dt[3];
	//echo $presentyr;
	$presentm=$dt[5].$dt[6];
	//echo $presentm;
	if($presentm<='6')
		$csem='janmay';
	else
		$csem='julynov';

	$enroll_table=$csem.'_'.$presentyr.'_'.$cyr.'_'.$branch;
	
 	if(isset($_SESSION['course']))
	{	
	$nm=explode('_', $cur_table_name);
	$roll=mysql_fetch_array(mysql_query("SELECT rollnumber from $enroll_table where course_id='$_SESSION[course]'"));
	$roll_list=explode(',', $roll[rollnumber]);
	$i=0;
	while($roll_list[$i])
		$i++;
/////
	$j=0;
	$enrolled=0;
	while($j<=$i)
		{
			if($roll_list[$j]==strtoupper($_SESSION['rollnumber']))
				$enrolled=1;
		$j=$j+1;
		}
	
	if($enrolled=='0')
		echo '<span class="lead" >You are not enrolled in '.$_SESSION[course].' </span>';
	else		
		{
			$exam="SELECT examtype,semester from test where course_id='$_SESSION[course]'";
			$test=mysql_fetch_array(mysql_query($exam));
		
 	//$_SESSION['examtype']=$test[0];
   // var_dump($test);
	//echo $_SESSION['examtype'];
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
		if($test['semester']=='1')
			$_SESSION['sem']='julynov_2015';
		else
			$_SESSION['sem']='janmay_2015';

		$chk=mysql_fetch_array(mysql_query("select * from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
		if($chk==NULL)
			{
				$stu_add=mysql_query("INSERT into student_exam_status values('$_SESSION[rollnumber]','$_SESSION[course]','0','0','0','0','0')");
				var_dump($stu_add);
			}
	}


$examstatus=$_SESSION['examtype'].'_'.'taken';
$val=mysql_fetch_array(mysql_query("select $examstatus from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
//var_dump($val);

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
	}
else
	{if ($val[$examstatus]!=NULL) 
		{
		//$_SESSION['prev_duration']=$val[$examstatus];
		//echo $_SESSION[prev_duration];
		//var_dump($_SESSION['prev_duration']);
		echo '<div class="row">';
		 echo '<div class="span3 offset5"><br/><button class="btn btn-primary btn-large btn-block" type="submit" name="resumetest" onclick="window.location.href=\'exam.php\'"><i class="icon-white icon-flag" ></i>Resume Test</button></span></div>';
		 echo '</div>';
		}
	else	
		{

			echo '<span class="lead" >NO exam has been set for '.$_SESSION[course].' Course. Consult the related faculty.</span>';
		}
	}
}
}

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

// if($test[0]==NULL)
 ///	{
 	//	echo '<span class="lead" >No Exam has been set for your Branch</span>';
 	//}
	//echo '<script>window.location="exam.php";</script>';
			//var_dump($_SESSION['examtype']);

	}
else
	{

		echo '</br>';
		//echo '<span class="lead" >No Exam has been set for your Branch</span>';
	}

 echo '</div>';
 	


include("footer.php");
?>
