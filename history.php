<?php
session_start();
require 'header.php';
if(isset($_SESSION['faculty_id']))
{
 echo '<div class="row-fluid text-center">';
 $i=0;
 $course_list=array();
 //fetching list of courses taught by faculty
 $sql=mysql_query(" select course_id from slot_wise_courses where faculty_id='$_SESSION[faculty_id]'");
 while($course_taken=mysql_fetch_array($sql))
  	 {  $course_list[$i]=$course_taken['course_id'];
  			$i++;
  			//echo $course_taken['course_id'];		
  	 }

  $i--;
  $examtype="";
 
  echo "<h3>Your exam history consists of following exams : </h3>\n";
  
	?>
	<table class="table table-hover table-striped table-bordered" style="width:66%;" align="center">
		<tr>
			<th style="text-align: center;">Examtype</th>
			<th style="text-align: center;">Duration</th>
			<th style="text-align: center;">Date</th>
			<th style="text-align: center;">Marks</th>
		</tr>
	<?php
  while($i>=0)
  {
	 $sqlVar = $course_list[$i];
	 $sql1=mysql_query("select examtype,date,duration,max_marks from test where course_id='$sqlVar'");//fetching exams set for a given course id 
  			while($test_id_list=mysql_fetch_array($sql1))
			  	{
			  		//verifying the type of exam
			  		if($test_id_list['examtype']==1)
			  				$examtype="Quiz 1";
			  			elseif($test_id_list['examtype']==2)
			  				$examtype="Quiz 2";
			  			elseif($test_id_list['examtype']==3)
			  				$examtype="Midsem";
			  			elseif($test_id_list['examtype']==4)
			  				$examtype="Endsem";
			  	
			  		echo '<tr><td style="text-align: center;">',$examtype,'</td><td  style="text-align: center;">',$test_id_list['duration']," Hours",'</td><td style="text-align: center;">',$test_id_list['date'].'</td><td style="text-align: center;">'.$test_id_list['max_marks']."</td></tr>";
			  	}
  	$i=$i-1;
  }
   }
	echo "</table>";
	echo "</br></br></br>";
include("footer.php");

?>