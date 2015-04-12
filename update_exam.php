<?php
session_start();
//////////////////////////////////////////////////////////////////////////////////////////////////
if($_SESSION['tab']!=4)
{
 $_SESSION['tab']=4;
 //echo '<meta http-equiv="refresh" content="0;fac_ques.php" >';
}
//////////////////////////////////////////////////////////////////////////////////////////////////

include("header.php");
if($_POST['goback'])
 {
  unset($_SESSION['selected_exam']);
 }
echo '<div class="row_fluid text-center" >';
if($_GET['update_exam'] || isset($_SESSION['selected_exam']))
 {
   if(isset($_POST['update']) && isset($_POST['exam']) && isset($_POST['duration']) && isset($_POST['exp_date']) && isset($_POST['max_marks']))
    {
      $details=explode('_',$_SESSION['selected_exam']);
     mysql_query("update test set examtype='$_POST[exam]', duration='$_POST[duration]', date='$_POST[exp_date]', max_marks=$_POST[max_marks] where course_id='$details[0]' and examtype='$details[1]' and date='$details[2]' ");
     $_SESSION['selected_exam']=$_SESSION['course'].'_'.$_POST['exam'].'_'.$_POST['exp_date'];
    // echo $_SESSION['selected_exam'];
     echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Test details updated!</div>';
	 }

  if(!isset($_SESSION['selected_exam']))
  {
     $_SESSION['selected_exam']=$_GET['test'];
  }

  $details=explode('_',$_SESSION['selected_exam']);
  if(isset($_POST['schedule']))
   {
      //echo "reached here";
      $details=explode('_',$_SESSION['selected_exam']);
      //$date=explode('-', $details[1]);
      mysql_query("update test set exam_activation=$_POST[schedule] where course_id='$_SESSION[course]' and date='$details[2]' and examtype=$details[1] ");
  	 if($_POST['schedule']==1)
  	 {
  	  echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Test Activated!</div>';
  	 }
  	 else
  	 {
  	  echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Test Deactivated!</div>';
  	 }
   }

    echo '<div class="text-left" ><form action="update_exam.php" method="post"><button type="submit" class="btn btn-primary text-right" name="goback" value="goback" ><i class="icon-white icon-chevron-left" ></i> Go Back</button></form></div>';
   echo '<h3>Test Summary</h3>';
   echo '<div class="row" ><div class="span5 offset4" >';
   //echo $_SESSION['selected_exam'];
   $details=explode('_',$_SESSION['selected_exam']);
    display_test_info($_SESSION['selected_exam']);
   $test=mysql_fetch_array(mysql_query("select date from test where course_id='$details[0]' and examtype='$details[1]' and date='$details[2]' "));
   if(validateDate($test['date']))
   {
      echo '<br /><a href="#myModaledit" role="button" class="btn btn-large btn-primary" data-toggle="modal"><i class="icon-white icon-pencil" ></i> Edit</a>&nbsp;&nbsp;&nbsp;<a href="#myModalsch" role="button" class="btn btn-large btn-success" data-toggle="modal"><i class="icon-white icon-flag"></i> Schedule</a><br /><br />';
   }
   ?>
<!-- Modal -->
<div id="myModaledit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Edit Test Info</h3>
</div>
<div class="modal-body">
<form action="update_exam.php" method="post">
<table class="table-bordered table table-hover table-striped ">
<?php
 $test=mysql_fetch_array(mysql_query("select * from test where course_id='$details[0]' and examtype='$details[1]' and date='$details[2]' "));
 echo '<tr><td><b>Exam: </b></td>';
 echo '<td>';
 ?>
  <select name="exam" id="exam">
  <option value="1" <?php if($test['type']==1) echo "selected"; ?> >Q1</option>
  <option value="2" <?php if($test['type']==2) echo "selected"; ?> >Q2</option>
  <option value="3" <?php if($test['type']==3) echo "selected"; ?> >MidSem</option>
  <option value="5" <?php if($test['type']==4) echo "selected"; ?> >EndSem</option>
  </select>
 <?php
 echo '</td></tr>';
 
 echo '<tr><td><b>Duration: </b></td>';
 echo '<td>';
  ?>
  <input type="text" class="input" name="duration" id="duration"  <?php if($test['duration']) echo "value=".$test['duration']; ?> placeholder="(Ex: 2:30 i.e, 2 hrs 30 mins)" />
  <?php
 echo '</td></tr>';
 
 echo '<tr><td><b>Date of Exam: </b></td>';
 echo '<td>';
 ?>
  <input type="date" class="input" name="exp_date" id="exp_date"  <?php if($test['date']) echo "value=".$test['date']; ?> placeholder="YYYY-MM-DD" />
 <?php
 echo '</td></tr>';
 
 echo '<tr><td><b>Maximum Marks: </b></td>';
 echo '<td>';
 ?>
  <input type="number" class="input" name="max_marks"  <?php if($test['max_marks']) echo 'value="'.$test['max_marks'].'"'; ?> id="max_marks" placeholder="maximum marks..." />
 <?php
 echo '</td></tr>';
 
 ?>
</table>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button class="btn btn-primary" type="submit" name="update" value="update" ><i class="icon-white icon-ok" ></i>  Update</button>
</form>
</div>
</div>




 
<!-- Modal -->
<div id="myModalsch" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" action='<?php $select=mysql_query("update tests set active='$_POST[schedule]' where test_id='$_SESSION[tid]'");?> ' class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Test Scheduling</h3>
</div>
<div class="modal-body text-left">

<span class="lead">Do you really want to do it now?</span>

</div>
<div class="modal-footer">
<form action="update_exam.php" method="post">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

<?php 
if($test['exam_activation']=='0' || $test['exam_activation']=='-1') 
   {
   	echo '<button type="submit" class="btn btn-primary" name="schedule" value="1" ><i class="icon-white icon-ok" ></i> Activate</button>';  
	}
else 
{
	echo '<button type="submit" class="btn btn-danger" name="schedule" value="-1" ><i class="icon-white icon-remove" ></i> Deactivate</button>'; 
}
?>
</form>
</div>
</div>
   <?php
   echo '</div></div>';
 
 }
else
 {
   
   echo '<br /><div class="text-left" ><a href="faculty.php" class="btn btn-primary text-right" ><i class="icon-white icon-chevron-left" ></i> Go Back</a></div><br /><p class="lead">You have following tests set in the course <big>'.$_SESSION['course'].'</big></p><form action="update_exam.php" method="get" ><select name="test" >';
   $select = mysql_query("select examtype, date from test where course_id='$_SESSION[course]' order by date");
   while($test=mysql_fetch_array($select))
     {
      if(validateDate2($test['date']))
      {
	     echo '<option value="'.$_SESSION['course'].'_'.$test['examtype'].'_'.$test['date'].'" >';
       if($test['examtype']==1)
         echo 'Quiz 1';
       else if($test['examtype']==2)
         echo 'Quiz 2';
       else if($test['examtype']==3)
         echo 'Mid sem';
       else if($test['examtype']==4)
         echo 'End sem';
       echo ' on '.$test['date'].'</option>';
     }
	 }
   echo '</select><br /><button type="submit" class="btn btn-primary" value="yes" name="update_exam" >Go <i class="icon-white icon-chevron-right"></i></button></form><br /><br /><br /><br /><br /><br /><br /><br /><br />';
 }

echo '</div>';

include("footer.php");
?>
