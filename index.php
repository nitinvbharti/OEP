<?php
session_start();
include("connect.php");



/////////////////////////////when logout is clicked///////////////////////////////////////////////////
if($_POST['logout'])
  {
    session_destroy();
	if(isset($_COOKIE["rollnumber"]))
	  {
       setcookie("rollnumber","",time()-3600*4*365*10);
	  }
	if(isset($_COOKIE["faculty_id"]))
	  {
       setcookie("faculty_id","",time()-3600*4*365*10);
	  }
	setcookie("name","",time()-3600*4*365*10);
    echo '<script>window.location="index.php";</script>';
  }
/////////////////////////////when logout is clicked///////////////////////////////////////////////////


/////////////////////////////when login is clicked////////////////////////////////////////////////////
if(isset($_POST['login']))
 {//////////Check for login credentials of students////////////////////////////////////////
  $select=mysql_query("select name from students where rollnumber='$_POST[username]' and password='$_POST[password]' ");
  //echo "select name from students where rollnumber='$_POST[username]' and password='$_POST[password]' ";
  if(mysql_num_rows($select))
   {
    $student=mysql_fetch_array($select);
	$_SESSION['rollnumber']=$_POST['username'];
	$_SESSION['name']=$student['name'];
	//echo '<script>window.location="student.php";</script>';
   }
  else
   {///////////////////////checking for login credentials of faculty//////////////////////////////////
    $select=mysql_query("select name from faculty where faculty_id='$_POST[username]' and password='$_POST[password]' ");
    if(mysql_num_rows($select))
     {
	   $faculty=mysql_fetch_array($select);
       $_SESSION['faculty_id']=$_POST['username'];
	   $_SESSION['name']=$faculty['name'];
	 //echo '<script>window.location="faculty.php";</script>';
     }
   }



   ///////////////////////////////creating a cookie to remeber the logged in credentials--start//////////////////////////////////
    if(isset($_POST['remember']))
	 {
	  if($_SESSION['rollnumber'])
	   {
        setcookie("rollnumber", "$_SESSION[rollnumber]", time()+3600*24*365*10);
	    setcookie("name", "$_SESSION[name]", time() + 3600 * 24 * 365 * 10);
		//echo 'student cookies set';
	   }
	  else if($_SESSION['faculty_id'])
	   {
        setcookie("faculty_id", "$_SESSION[faculty_id]", time()+3600*24*365*10);
	    setcookie("name", "$_SESSION[name]", time() + 3600 * 24 * 365 * 10);
		//echo 'faculty cookies set';
	   }
	 }

   ///////////////////////////////creating a cookie to remeber the logged in credentials--------end//////////////////////////////////
	//echo "admin ok";
 }
 ///////////////checking and logging in if already a valid cookie exists//////////////////
if(isset($_COOKIE['rollnumber']) && !isset($_SESSION['rollnumber']))
  {
	$_SESSION["rollnumber"]=$_COOKIE["rollnumber"];
	$_SESSION["name"]=$_COOKIE["name"];
	//echo "restored from student cookies";
  }
if(isset($_COOKIE["faculty_id"]) && !isset($_SESSION['faculty_id']))
  {
	$_SESSION["faculty_id"]=$_COOKIE["faculty_id"];
	$_SESSION["name"]=$_COOKIE["name"];
	//echo "restored from faculty cookies";
  }



require("header.php");
////////////////redirecting faculty/student to correct page-------start//////////////
if(isset($_SESSION['rollnumber']) || isset($_SESSION['faculty_id']))
{
	if(isset($_SESSION['faculty_id']))
	{
		echo '<script>window.location="faculty.php";</script>';
	}
	else if(isset($_SESSION['rollnumber']))
	{
		echo '<script>window.location="student.php";</script>';
	}
  
////////////////redirecting faculty/student to correct page-------end//////////////
}
else
{

/////////////////form to fill the login data------start///////////////////////
?>
<div class="row-fluid text-center" >
<br />
<h4><i class="icon-user"></i> User Login</h4>
<form action="index.php" method="post" name="UserLogin">
<table align=center >
<tr>
<td><input type="text" class="input-medium" name="username"  autocomplete="off" maxlength="9" placeholder="Your Username..."  required/></td>
</tr>
<tr>
<td><input type="password"  class="input-medium" autocomplete="on"  name="password" maxlength="15" placeholder="Your Password..."  required required/></td>
</tr>

<tr><td><label class="control-label" ><input type="checkbox"  class="checkbox" name="remember" value=1/> Remember me</label></td></tr>
<tr><td><input type="submit"  class="btn btn-primary" name="login" value="Login" /></td></tr>
</table>
</form>

<?php

/////////////////form to fill the login data --end///////////////////////
if(!isset($_SESSION['rollnumber']) && !isset($_SESSION['faculty_id']) && isset($_POST['login']))
 {
echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid username or password!</div>';
 }
echo '</div>';
}
  require("footer.php");
?>