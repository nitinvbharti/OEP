<?php
  session_start();
  require("header.php");
?>
<?php

if(isset($_POST['submit']) || isset($_SESSION['faculty_id']))
{
    signin_faculty();
	if(isset($_SESSION['faculty_id']))
	 {
	   echo '<script>window.location="faculty.php";</script>';
	 }
}
else
{
?>

<div class="row-fluid text-center" >
<br />
<h4><i class="icon-user"></i> Faculty Login</h4>
<form action="faculty_login.php" method="post" name="faculty_login">
<table align=center >
<tr>
<td><input type="text" class="input-medium" name="username"  maxlength="9" placeholder="Your Username..."  required/></td>
</tr>
<tr>
<td><input type="password"  class="input-medium" name="password" maxlength="15" placeholder="Your password..."  required required/></td>
</tr>


<tr><td><label class="control-label" ><input type="checkbox"  class="checkbox" name="rememberme" value=1/> Remember me</label></td></tr>
<tr><td><input type="submit"  class="btn btn-primary" name="submit" value="Login" /></td></tr>
</table>

</form>



</div>
<?php
}
  require("footer.php");
?>
