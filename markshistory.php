<?php
session_start();
require 'header.php';
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

/*
$t=time();
		$dt=Date('Y-m-d',$t);
		$presentyr=$dt[0].$dt[1].$dt[2].$dt[3];
		//echo $presentyr;
		$presentm=$dt[5].$dt[6];
		//echo $presentm;
		if($presentm<='7')
			$csem='janmay';
		else
			$csem='julynov';
		
		$enroll_table=$csem.'_'.$presentyr.'_'.$cyr.'_'.$branch;

*/
//while()

if(isset($_POST['go']))
	{

		$_SESSION['course']=strtoupper($_POST['course']);
		$markstables[0]='ans'.'_'.$_SESSION['course'].'_'.'q1';
		$markstables[1]='ans'.'_'.$_SESSION['course'].'_'.'q2';
		$markstables[2]='ans'.'_'.$_SESSION['course'].'_'.'endsem';
		//$markstables[0]='ans'.'_'.$_SESSION['course'].'_'.'makeup';
		//$markstables[0]='ans'.'_'.$_SESSION['course'].'_'.'supplementary';
		$max_q1=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='1'"));
		$max_q2=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='2'"));
		$max_endsem=mysql_fetch_array(mysql_query("SELECT max_marks from test where course_id='$_SESSION[course]' AND examtype='3'"));

		$marks_q1=mysql_fetch_array(mysql_query("SELECT marks from $markstables[0] where rollnumber='$_SESSION[rollnumber]"));
		$marks_q2=mysql_fetch_array(mysql_query("SELECT marks from $markstables[0] where rollnumber='$_SESSION[rollnumber]"));
		$marks_endsem=mysql_fetch_array(mysql_query("SELECT marks from $markstables[0] where rollnumber='$_SESSION[rollnumber]"));

		echo '<table>';
		echo '<tr><td>Q1</td></tr></table>';


	}










if(!isset($_SESSION['course']))
{

echo '<span class="lead" >Select course to check marks</span>';
echo '<div class="row"><div class="span6 offset5"';	

$select=mysql_query("SELECT course_no from $cur_table_name");
 echo '<form action="student.php" method="post" ><select name="course">';
 	while($row=mysql_fetch_array($select))
  		{
    		echo '<option value='.$row['course_no'].'>'.$row['course_no'].'</option> ';
  		}
	 echo '</select>';


	 echo '<br /><br /><button type="submit" value="go" name="go" class="btn btn-info btn-large" ><i class="icon-ok icon-white" ></i> Select Course</button>';
	 echo '</form>';
	 echo '</div></div>';
	}
}
else


{

		echo '</br>';
		echo '<span class="lead" >Invalid Login. Exit via logout button</span>';

}

 echo '</div>';
 	


include("footer.php");
?>
