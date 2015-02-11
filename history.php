<?php
session_start();
require 'header.php';
if(isset($_SESSION['faculty_id']))
{
 echo '<div class="row-fluid text-center">';
 $i=0;
 $course_list=array();
 //fetching list of courses taught by faculty
 $sql=mysql_query(" select course_id from courses where faculty_id='$_SESSION[faculty_id]'");
 while($course_taken=mysql_fetch_array($sql))
  	 {  $course_list[$i]=$course_taken['course_id'];
  			$i++;
  			//echo $course_taken['course_id'];		
  	 }

  $i--;
  $examtype="";
 
  echo "Your exam history consists of following exams\n";
  while($i>=0)
  {
	 $sqlVar = $course_list[$i];
	 $sql1=mysql_query("select type,date,duration from tests where course_id='$sqlVar'");//fetching exams set for a given course id 
  			while($test_id_list=mysql_fetch_array($sql1))
			  	{
			  		//verifying the type of exam
			  		if($test_id_list['type']==1)
			  				$examtype="Quiz 1";
			  			elseif($test_id_list['type']==2)
			  				$examtype="Quiz 2";
			  			elseif($test_id_list['type']==3)
			  				$examtype="Midsem";
			  			elseif($test_id_list['type']==4)
			  				$examtype="Viva";
			  			elseif($test_id_list['type']==5)
			  				$examtype="Endsem";
			  		echo "</br>",$examtype," ","Duration ",$test_id_list['duration'],"Hours ","Date ",$test_id_list['date']."</br>";
			  	}
  	$i=$i-1;
  }
   }
   ?>
