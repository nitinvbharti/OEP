<?php
session_start();
require("header.php");
if($_SESSION['faculty_id'])
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_SESSION['course']))
 {
  $type=$_POST['name'];
  $test_nos=mysql_fetch_array(mysql_query("select test_id from tests where course_id='$_SESSION[course]'  and type='$_POST['name']' "));
  echo $test_nos;
  //$_SESSION['type']=$test['type'];
  $qid=mysql_fetch_array(mysql_query("select ques_bank_id from ques_bank_no where test_id='$test_nos' "));
  echo $test;



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