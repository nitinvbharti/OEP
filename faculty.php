<?php
session_start();



//////////////////////////////////////////////////////////////////////////////////////////////////
if($_SESSION['tab']!=1)
{
 $_SESSION['tab']=1;
 //echo '<meta http-equiv="refresh" content="0;faculty.php" >';
}
//////////////////////////////////////////////////////////////////////////////////////////////////
require("header.php");

if(isset($_SESSION['faculty_id']))
{
 
if(isset($_POST['set_exam']) || isset($_POST['evaluate']) || isset($_POST['question']) || isset($_POST['schedule']) || isset($_POST['exams']))
{
	unset($_SESSION['init']);
	unset($_SESSION['tid']);
	unset($_SESSION['ques_bank_id']);
	$_SESSION['course'] = $_POST['course'];
	if(isset($_POST['question']))
	 {
		 echo '<script>window.location="fac_ques.php";</script>';
	 }
	else if(isset($_POST['set_exam']))
	 {
		echo '<script>window.location="set_questions.php";</script>';
	 }
	else if(isset($_POST['exams']) || isset($_POST['schedule']))
	 {
       echo '<script>window.location="update_exam.php";</script>';
	 }
	 
	 
}
else  //Later do else if for each of choicess....
{
	$fac_id=$_SESSION['faculty_id'];
	$course_set=list_all_courses($fac_id);
	?>
	<div class="row-fluid text-center">
	<h3>Select Course</h3>
	<form method="POST" action="faculty.php">
		<table align="center" cellpadding="10" >
		<tr>
			<td>
				<select name="course">
				<?php while($course=mysql_fetch_array($course_set))
				{
				?>
				<option value="<?php echo $course['course_id']?>"><?php echo $course['course_id']?></option>
				<?php
				}
				mysql_free_result($course_set);
				?>
				</select>
			</td>
		</tr>
		</table>
		<br>
		<br>
		<table align="center" cellpadding="15" >
		<tr>
			<td><input class="btn btn-primary btn-large" name="set_exam" type="submit" value="Set Exam"></td>
			<td><input class="btn btn-primary btn-large" name="question" type="submit" value="Set Questions"></td>
			<td><input class="btn btn-primary btn-large" name="exams" type="submit" value="View Exams" /></td>
			<td><input class="btn btn-primary btn-large" name="schedule" type="submit" value="Schedule Exam"></td>
			<td><input class="btn btn-primary btn-large" name="evaluate" type="submit" value="Evaluate"></td>
			
		</tr>
		</table>
	</form>
	</div>
<?php
}
}//end of if
else
 {
 echo '<script>window.location="index.php";</script>';
 }
require("footer.php");
?>