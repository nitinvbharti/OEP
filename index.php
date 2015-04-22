<?php
session_start();
/*
*************************************************

This page has code related to login. It authentictes the user as facuty or student and redirects to corresponding page.
It also starts the session.

*************************************************
*/
include("connect.php");
//include("header.php");
require("header.php");



/////////////////////////////when logout is clicked///////////////////////////////////////////////////
if($_POST['logout'])
  {
  	if(isset($_SESSION['rollnumber']))
  		{
  		$dt = new DateTime();
        $dtformatted=$dt->format('Y-m-d H:i:s');
        mysql_query("UPDATE login set loggedin='0' AND lastlogin='$dtformatted' WHERE rollnumber='$_SESSION[rollnumber]'");
  		}
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
    echo '<script>window.location="index.php";</script>';   //////Reddirect the use to homepage

  }
/////////////////////////////when logout is clicked///////////////////////////////////////////////////


/////////////////////////////when login is clicked////////////////////////////////////////////////////
if(isset($_POST['login']))
 {//////////Check for login credentials of students////////////////////////////////////////
  $select=mysql_query("select name from students where rollnumber='$_POST[username]' and password = '$_POST[password]' ");
  if(mysql_num_rows($select))
   {


    $_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
    $ip=$_SESSION['ip'];
    /*
    $Allowed=verifyip($ip)
    Call this funtion to verify ip in a given range
    By default it is not activated.
    */
    $Allowed=1;
    //var_dump(expression)
    if($Allowed=='1')
    {
      
    $student=mysql_fetch_array($select);
	  $_SESSION['rollnumber']=strtoupper($_POST['username']);
	  $_SESSION['name']=$student['name'];
	//echo '<script>window.location="student.php";</script>';
	   $dt = new DateTime();
       $dtformatted=$dt->format('Y-m-d H:i:s');
       //echo '<br>'.$dtformatted;
       $check=mysql_fetch_array(mysql_query("select * from login where rollnumber='$_SESSION[rollnumber]'"));
       //var_dump($check);
       //echo '<br>'.$check[1];
       if($check[1]=='1')
       	{
       		if($check[3]!=$ip)
            {unset($_SESSION['rollnumber']);
       		   $multilogin='1';
       		   $_SESSION['multilogin']='1';
             }
       	}
       elseif($check[1]=='0')
       		{mysql_query("UPDATE login set loggedin='1',lastlogin='$dtformatted',ipaddress='$ip' where rollnumber='$_SESSION[rollnumber]'");
   	   		//echo $dtformatted;
       		$multilogin='0';
   	   		}
   	   else
       		{mysql_query("INSERT INTO login set rollnumber='$_SESSION[rollnumber]',loggedin='1',lastlogin='$dtformatted',ipaddress='$ip'");
       		$multilogin='0';
       		}
   }
   else
   {
      ///when belongs to bad ip range
   }
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
<tr><td><a><input type="submit"  class="btn btn-primary" name="login" value="Login" /></a></td></tr>
</table>
</form>

<?php

/////////////////form to fill the login data --end///////////////////////
if(!isset($_SESSION['rollnumber']) && !isset($_SESSION['faculty_id']) && isset($_POST['login']))
 {
 	if($multilogin=='1')
 		{
 			echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Multiple Login Not Allowed</div>';
		}
		else
		{
			echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid username or password!</div>';
 		}
 }
echo '</div>';
}
  require("footer.php");
?>