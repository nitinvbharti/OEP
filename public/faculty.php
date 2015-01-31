<?php
session_start();
require_once '../includes/connect.inc.php';
require_once '../includes/functions.php';
if(isset($_SESSION['faculty_id']))
{
//	$fac_id=$_SESSION['faculty_id'];
	if(isset($_POST['home']))
	{
	}
	echo "Welcome ".$_SESSION['faculty_name'];
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
	header("Location: faculty_login.php");
}

if(isset($_POST['logout']))
{
	session_destroy();
	header("Location: faculty_login.php");
}

if(isset($_POST['choice']))
{
	echo 'Came into '.$_POST['choice'].' Section for Course '.$_POST['course'];
}
else  //Later do else if for each of choicess....
{
	$fac_id=$_SESSION['faculty_id'];
	echo $fac_id;
	$course_set=list_all_courses($fac_id);
	?>
	Select Course to Go On with
	<form method="POST" action="faculty.php">
		<select name="course">
		<?php
		while($course=mysql_fetch_array($course_set))
		{
			?>
			<option value="<?php echo $course['course_id']?>"><?php echo $course['course_id']?></option>

		<?php
		}
		mysql_free_result($course_set);
		?>
		</select><br>
		<input name="choice" type="submit" value="Set_exam">
		<input name="choice" type="submit" value="Questions">
		<input name="choice" type="submit" value="Evaluate">
	</form>
<?php
}
?>