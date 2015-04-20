<?php

session_start();
if($_SESSION['tab']!=8)
{
	$_SESSION['tab']=8;
}

require("header.php");

if(!isset($_SESSION['faculty_id']))
{
	echo '<script>window.location="index.php";</script>';
}
else if(!isset($_POST['change_pass']) && !isset($_POST['btn_set']))
{
	if(isset($_SESSION['success']))
	{
		echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Password successfully changed ..!!!</div>';
		unset($_SESSION['success']);
	}
	$details=mysql_fetch_array(mysql_query("select * from faculty where faculty_id='$_SESSION[faculty_id]' "));
	$courses=mysql_query("select course_id from slot_wise_courses where faculty_id='$_SESSION[faculty_id]' ");
?>
	</br></br></br>
	<table class="table table-hover table-striped table-bordered" style="width:40%;" align="center">
	<tr>
		<td style="text-align: center"><strong>ID</strong></td>
		<td style="text-align: center"><?php echo $details['faculty_id'] ?></td>
	</tr>
	<tr>
		<td style="text-align: center"><strong>NAME</strong></td>
		<td style="text-align: center"><?php echo $details['name'] ?></td>
	</tr>
	<tr>
		<td style="text-align: center"><strong>FACULTY STATUS</strong></td>
		<td style="text-align: center"><?php
		if($details['activate']==1)
		{
		   echo "Active"; 
		}
		else 
		{
	 		echo "Not Registered.";
		} 
		 ?></td>
	</tr>
	<tr>
		<td style="text-align: center"><strong>PASSWORD</strong></td>
		<td style="text-align: center"><?php echo $details['password']; ?></td>
	</tr>
	<tr>
		<td style="text-align: center"><strong>COURSES</strong></td>
		<td style="text-align: center"><?php while($each_course=mysql_fetch_array($courses)) echo $each_course['course_id']."</br>" ?></td>
	</tr>
	</table>
	<div class="password_change" align="center">
		<form action="profile.php" method="post">
			<button class="btn btn-primary btn-large" type="submit" name="change_pass" value="change" ><i class="icon-white icon-flag" > </i> Change Password</button>
		</form>
	</div>
<?php
}
else if(isset($_POST['change_pass']))
{
?>
	</br></br></br>
	<?php
	if(isset($_POST['old_pass']))
	{
		$get_pass=mysql_fetch_array(mysql_query("select password from faculty where faculty_id='$_SESSION[faculty_id]' "));	
		if($get_pass['password']!=$_POST['old_pass'])
		{
			echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Old Password Not same ..!!!</div>';
			unset($_POST['old_pass']);
			unset($_POST['new_pass']);
			unset($_POST['re_pass']);
		}
		else if($_POST['new_pass']!=$_POST['re_pass'])
		{
			echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Passwords doesnot match ..!!!</div>';
			unset($_POST['old_pass']);
			unset($_POST['new_pass']);
			unset($_POST['re_pass']);	
		}
		else
		{
			mysql_query("update faculty set password='$_POST[new_pass]' where faculty_id='$_SESSION[faculty_id]' ");
			$_SESSION['success']=1;
			echo '<script>window.location="profile.php";</script>';
		}
	}
	if(!isset($_POST['old_pass']))
	{
	?>
	<div class="controls password_handle">
	<form action="profile.php" method="post">
	<table class="table table-hover table-striped table-bordered" style="width:40%;" align="center">
		<tr>
			<td style="text-align: center; padding-top: 15px;"><strong>CURRENT PASSWORD</strong></td>
			<td><input class="input-medium" id="o_pass" type="text" name="old_pass" placeholder="Enter current password" required /> </td>
		</tr>
		<tr>
			<td style="text-align: center; padding-top: 15px;"><strong>NEW PASSWORD</strong></td>
			<td><input class="input-medium" id="n_pass" type="text" name="new_pass" placeholder="Enter new password" required/> </td>
		</tr>
		<tr>
			<td style="text-align: center; padding-top: 15px;"><strong>RE-ENTER PASSWORD</strong></td>
			<td><input class="input-medium" id="r_pass" type="text" name="re_pass" placeholder="Re-enter new password" required/> </td>
		</tr>
	</table>
	<div class="btn_submit" align="center">
		<button class="btn btn-primary btn-large" type="submit" name="change_pass" value="change" ><i class="icon-white icon-flag" ></i> Submit</button>
	</div>
	</form>
	</div>
	<?php
	}
}

require("footer.php");

?>