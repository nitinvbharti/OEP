<?php
session_start();
/*
<!--
*************************************************

This page contains code related to student's marks and evaluation.

*************************************************
-->
*/
require 'header.php';
echo '<div class="row text-center">';
if(isset($_SESSION['rollnumber']))
{
 	
	$rollnumber=$_SESSION['rollnumber'];
 	$branch=$rollnumber[0].$rollnumber[1].$rollnumber[2];
 	$yr='20'.$rollnumber[3].$rollnumber[4];
 	if($yr<=2013)
 		$cyr='2009';
 	else
 		$cyr='2014';
 	$cur_table_name='curriculum'.'_'.$cyr.'_'.$branch;

if(isset($_POST['go']))
	{

		//echo "Shubam";
		$_SESSION['course']=strtolower($_POST['course']);
		$markstables[0]='ans'.'_'.$_SESSION['course'].'_'.'q1';
		$markstables[1]='ans'.'_'.$_SESSION['course'].'_'.'q2';
		$markstables[2]='ans'.'_'.$_SESSION['course'].'_'.'endsem';
		$markstables[3]='ans'.'_'.$_SESSION['course'].'_'.'makeup';
		$markstables[4]='ans'.'_'.$_SESSION['course'].'_'.'supplementary';
		//var_dump($markstables);
		//echo $_SESSION[rollnumber];
		$max_q1=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='1'"));
		$max_q2=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='2'"));
		$max_endsem=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='3'"));
		//echo $markstables[0];
		$marks_q1=mysql_fetch_array(mysql_query("SELECT marks from $markstables[0] where rollnumber='$_SESSION[rollnumber]'"));
		$marks_q2=mysql_fetch_array(mysql_query("SELECT marks from $markstables[1] where rollnumber='$_SESSION[rollnumber]'"));
		$marks_endsem=mysql_fetch_array(mysql_query("SELECT marks from $markstables[2] where rollnumber='$_SESSION[rollnumber]'"));
 		//var_dump($marks_q1);
 		//$marks_q1=10;
 		//$marks_q2=20;
 		//$marks_endsem=40;
		//echo '<tr><td style="text-align: center;">',$markstables[0],'</td><td  style="text-align: center;">',$markstables[1]," Hours",'</td><td style="text-align: center;">',$test_id_list['date'].'</td><td style="text-align: center;">'.$test_id_list['max_marks']."</td></tr>";
		echo '<div class="row" align="center"><div class="span5 offset4"><div class="table-responsive"><table class="table"><thead><tr><th>Exam</th><th>Marks</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max Marks</td></tr></thead>';	 
		echo '<tr><td><b>Q1</b></td><td>',$marks_q1[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_q1[0],'</td>';
		echo '<tr><td><b> Q2</b></td><td>',$marks_q2[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_q2[0],'</td>';
		echo '<tr><td> <b>Endsem</b></td><td>',$marks_endsem[0],'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$max_endsem[0],'</td>';
		echo '</table></div></div>';
	}


if(!isset($_SESSION['course']))
{

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
