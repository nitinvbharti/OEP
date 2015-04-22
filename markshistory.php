<?php
session_start();
/*
*************************************************

This page contains code related to student's marks and evaluation.

*************************************************
*/
require 'header.php';
echo '<div class="row text-center">';
if(isset($_SESSION['rollnumber']))
{
 	
	$rollnumber=$_SESSION['rollnumber'];
 	$branch=$rollnumber[0].$rollnumber[1].$rollnumber[2];	///Construct Branch name from given Rollnumber & find year
 	$yr='20'.$rollnumber[3].$rollnumber[4];
 	if($yr<=2013)
 		$cyr='2009';
 	else
 		$cyr='2014';
 	$cur_table_name='curriculum'.'_'.$cyr.'_'.$branch;





if(isset($_POST['go']))
	{

	$_SESSION['course']=strtolower($_POST['course']);

	$exam="SELECT semester from test where course_id='$_SESSION[course]'";
	$test=mysql_fetch_array(mysql_query($exam));
 	$t=time();										///Use current time to find if its Odd Sem or even Sem.
	$dt=Date('Y-m-d',$t);
	$presentyr=$dt[0].$dt[1].$dt[2].$dt[3];
	if($test['semester']=='1')
			$_SESSION['sem']='julynov'.'_'.$presentyr;
	else
			$_SESSION['sem']='janmay'.'_'.$presentyr;


		////////////COncstruct the name of answer table of the User.
		$markstables[0]='ans'.'_'.$_SESSION['course'].'_'.'q1'.'_'.$_SESSION['sem'];
		$markstables[1]='ans'.'_'.$_SESSION['course'].'_'.'q2'.'_'.$_SESSION['sem'];
		$markstables[2]='ans'.'_'.$_SESSION['course'].'_'.'endsem'.'_'.$_SESSION['sem'];
		$markstables[3]='ans'.'_'.$_SESSION['course'].'_'.'makeup'.'_'.$_SESSION['sem'];
		$markstables[4]='ans'.'_'.$_SESSION['course'].'_'.'supplementary'.'_'.$_SESSION['sem'];
		//var_dump($markstables);
		//echo $_SESSION[rollnumber];
		$max_q1=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='1'"));
		$max_q2=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='2'"));
		$max_endsem=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='3'"));
		//echo $markstables[0];
		$marks_q1=mysql_fetch_array(mysql_query("SELECT marks from $markstables[0] where rollnumber='$_SESSION[rollnumber]'"));
		$marks_q2=mysql_fetch_array(mysql_query("SELECT marks from $markstables[1] where rollnumber='$_SESSION[rollnumber]'"));
		$marks_endsem=mysql_fetch_array(mysql_query("SELECT marks from $markstables[2] where rollnumber='$_SESSION[rollnumber]'"));
 		echo '<div class="table-responsive"><table table-hover table-striped table-bordered" style="width:80%;" align="center"><thead><tr><th>Exam</th><th>Marks</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max Marks</td></tr></thead>';	 
		echo '<tr><td><b>Q1</b></td><td>',$marks_q1[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_q1[0],'</td>';
		echo '<tr><td><b> Q2</b></td><td>',$marks_q2[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_q2[0],'</td>';
		echo '<tr><td> <b>Endsem</b></td><td>',$marks_endsem[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_endsem[0],'</td>';
		echo '</table>';
	}


if(!isset($_SESSION['course']))
{
///////////////Provides option to student to select course for viewing result./////////////
$select=mysql_query("SELECT course_no from $cur_table_name");
 echo '<form action="markshistory.php" method="post" ><select name="course">';
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
		echo '<span class="lead" >Invalid Login. Exit via logout button</span>';

	}

 echo '</div></div>';
//echo '<div class="row">';
include("footer.php");
?>
