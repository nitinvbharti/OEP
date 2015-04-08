<?php
session_start();
require("header.php");

if($_SESSION['tab']!=1)
{
 
 $_SESSION['tab']=1;
 unset($_SESSION['ques_bank_id']);
unset($_SESSION['selected_exam']);
 //echo '<meta http-equiv="refresh" content="0;faculty.php" >';
}

if($_SESSION['faculty_id'])
{
if(isset($_SESSION['course']))
 {

 	if(isset($_POST['1']) || isset($_POST['2']) || isset($_POST['3']) || isset($_POST['4']) || isset($_POST['5']))
    {
       if(isset($_POST['1']))
	   {
		 $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='1' "));
          if($check==0)
          {
          	echo 'No finished exams to evaluate';
          }
          else
          {
          	 $i=0;
          	$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            echo $store[$i];
            $i++;
            }
           




          }
	   }
    }

 }






}





  
 
//////////////////////////////////////////////////////////////////////////////////////////////////
?>
<form method="POST" action="evaluate.php">
		<br>
		<br>
		<table align="center" cellpadding="15" >
		<tr>
			<td><input class="btn btn-primary btn-large" name="1"  type="submit"  value="Quiz 1"></td>
			<td><input class="btn btn-primary btn-large" name="2"  type="submit"  value="quiz 2"></td>
			<td><input class="btn btn-primary btn-large" name="3"  type="submit" value="Midsem" /></td>
			<td><input class="btn btn-primary btn-large" name="4"  type="submit" value="Endsem"></td>
			<td><input class="btn btn-primary btn-large" name="5"  type="submit" value="Viva"></td>
		</tr>
		</table>
	</form>


<?php 
require("footer.php");
?>