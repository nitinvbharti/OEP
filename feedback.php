<?php
session_start();
  /*
<!--
*************************************************

Feed back page for the users.

*************************************************
-->
  */

  require("header.php");
  //echo '<title>Feedback - IIITD&M Management Portal</title>';

?>
 <div class="row-fluid text-center">
 <h3>User Feedback</h3>
 <form action="feedback.php" method="post">
 <table align="center" >
 <tr><td><textarea id="feedback" name="feedback" style="min-width:500px;min-height:150px;"  onkeydown="manage_count()"   onkeyup="manage_count()" class="form-control" placeholder="Please provide your valuable feedback to improve our product..."  maxlength="1000" ></textarea></td></tr>
 <tr><td><span class="text-error" ><big>Characters left: </big><span id="count" >1000</span></td></tr>
 <tr><td><br /><input type="submit" class="btn btn-primary btn-large" name="f_submit" value="Submit" /></td></tr>
 </table>
 </form>
 </div>
<?php
  if(isset($_POST['f_submit']))
    {
	 if($_POST['feedback']!="")
	  {
	    if($_SESSION['faculty_id'])
		 $uname = $_SESSION['faculty_id'];
		else if($_SESSION['rollnumber'])
		 $uname = $_SESSION['rollnumber'];
	    $date_time=date('h:m||Y-m-d',time());
		mysql_query("insert into feedback_mp set feedback='$_POST[feedback]',date_time='$date_time',id='$uname' ");
        echo '<br /><div class="alert alert-success fade in" >
		<button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Your feedback submitted.</div>';
		$form_print=0;
	  }
	 else
	  {
	    echo '<br /><div class="alert fade in" >
		<button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Please enter feedback message and submit</div>';
	  }
	}

  
  require("footer.php");
?>