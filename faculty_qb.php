<!--
*************************************************

Faculty is reirected to this page to set question paper.

*************************************************
-->
<?php
session_start();

require("header.php");

if(isset($_SESSION['faculty_id']))
 {
	if(isset($_POST['home']))
	{
	}
	?>
	<form method="POST" action="faculty.php">
	<input  type="submit" name="home" value="Home">
	<input  type="submit" name="logout" value="LogOut">
	</form>
	<br>
	<br>
<?php
}
else
{
    echo '<script>window.location="index.php";</script>';
}

if(isset($_POST['logout']))
{
	session_destroy();
	echo '<script>window.location="index.php";</script>';
}

if(isset($_POST['set_exam']) || isset($_POST['evaluate']) || isset($_POST['question']))
{
	if(isset($_POST['question']))
	{
		 fetch_questions($_POST['course'],$_POST['test']);
		 
	}
	else
	{
		echo "Both Course and Test are reqiure to be set";
	}
}
else  //Later do else if for each of choicess....
{
	$fac_id=$_SESSION['faculty_id'];
	echo $fac_id;
	$course_set=list_all_courses($fac_id);
	?>
	Select Course to Go On with
	<form method="POST" action="faculty.php">
		<table align=left>
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
			<td>
				<select name="test">
					<option value="Q1">Quiz 1</option>
					<option value="Q2">Quiz 2</option>
					<option value="MS">Mid Sem</option>
					<option value="ES">End Sem</option>
					<option value="EE">Extra</option>
				</select>
			</td>
		</tr>
		</table>
		<br>
		<br>
		<table>
		<tr>
			<td><input class="btn btn-primary" name="set_exam" type="submit" value="Set_Exam"></td>
			<td><input class="btn btn-primary" name="question" type="submit" value="Questions"></td>
			<td><input class="btn btn-primary" name="evaluate" type="submit" value="Evaluate"></td>
		</tr>
		</table>
	</form>
<?php
}
?>