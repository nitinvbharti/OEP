<?php


session_start();
$test=array("type"=>"","duration"=>"","date"=>"");
//////////////////////////////////////////////////////////////////////////////////////////////////
if($_SESSION['tab']!=3)
{
 $_SESSION['tab']=3;
 //echo '<meta http-equiv="refresh" content="0;set_questions.php" >';
}
//////////////////////////////////////////////////////////////////////////////////////////////////

require("header.php");

if($_SESSION['faculty_id'])
 {
 
////////////////////////////////////////////////////////////////////////////////////////////////////////


if($_SESSION['course']=="")
{
 echo '<script>window.location="faculty.php";</script>';
}
else if(isset($_SESSION['course']))
{
  //echo $_SESSION['course'];           --> Correct
if($_SESSION[step]==0)
 $_SESSION[step]=1;

if($_GET['step']==1)
 {
  $_SESSION['step']=1;
 }
 
 ////second step in series of setting exam to get details of question paper like max marks,questions count etc.
if($_GET['step']==2)
 {
  if($_GET['exam'] && $_GET['duration'] && $_GET['exp_date'])
   {
    //echo $_GET['exam'];  -->correct
		//echo $_GET[duration];
		//echo $_GET[exp_date];
		
		if(!validateDate($_GET['exp_date']))/// check if vali date has entered
		{
      //echo "ss1";
		unset($_GET['exp_date']);
		echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid date!</div>';
		$_SESSION['step']=1;
		}
		if(!validateTime($_GET['duration']))//check if valid time has been entered
		{
      //echo "ss2";
		unset($_GET['duration']);
		echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid duration!</div>';
		$_SESSION['step']=1;
		}
		else
		{
		if(isset($_GET['duration']) && isset($_GET['exam']) && isset($_GET['exp_date']))
		{
      //echo $_SESSION['course']." ".$_GET['exam']." ".$_GET['exp_date'];
		  $_SESSION['exam']=$_GET['exam'];
      $_SESSION['date']=$_GET['exp_date'];
      $check=mysql_query("select course_id from test where course_id='$_SESSION[course]' and examtype=$_GET[exam] and date='$_GET[exp_date]' ");
      $row=mysql_fetch_array($check);
      $ndate=explode('-',$_GET['exp_date']);
      if($ndate[1]>=1 && $ndate[1]<=6)
          $sem=2;
      else if($ndate[1]>=7 && $ndate[1]<=12)
          $sem=1;
      if($row=="" && isset($_GET['duration']) && isset($_GET['exam']) && isset($_GET['exp_date']))
      {
        //echo "  ss4  ";
        //echo $sem."i";
        mysql_query("insert into test set course_id='$_SESSION[course]', examtype='$_GET[exam]', duration='$_GET[duration]', date='$_GET[exp_date]',max_marks='$_GET[max_marks]',step='$_SESSION[step]',semester='$sem' ");
	  	}
      else
      {
       //echo " ss5 ";
        //echo $sem."u";
        mysql_query("update test set course_id='$_SESSION[course]', examtype='$_GET[exam]', duration='$_GET[duration]', date='$_GET[exp_date]', step='$_SESSION[step]', semester='$sem' where course_id='$_SESSION[course]' and examtype='$_GET[exam]' and date='$_GET[exp_date]' ");
      }
    }
		else
		{
		echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid duration!</div>';
    }
		$_SESSION['step']=2;
		}
		}
		else
		{
		$_SESSION['step']=1;
		}
		
 }
 
 ////////////third and final stage for creation of new exam////////////////////////
if($_GET['step']==2)
 {
  //echo $_SESSION['course'];
  if(isset($_SESSION['course']) && $_GET['max_marks']!="" )
   {
    $_SESSION['step']=2;
     mysql_query("update test set max_marks='$_GET[max_marks]',step='$_SESSION[step]' where date='$_SESSION[exp_date]'");
   }
  else
   {
    $_SESSION['step']=1;
    //echo '<script>window.location="set_questions.php?step=1";</script>';
   }
 }
 
if($_POST['step']==4)
 {
  $_SESSION['step']=4;
  mysql_query("update test set step=4 where course_id='$_SESSION[course]' and examtype='$_SESSION[exam]' and date='$_SESSION[date]' ");
 }

 
if($_SESSION['step']==4)
    echo '<script>window.location="fac_ques.php";</script>';
 
?>
<div class="row text-center">
<p class="lead" >Set Question Paper</p>
</div>
<div class="row text-center lead" >
<div class="span6 <?php if($_SESSION['step']!=1) echo "muted"; ?>" >Step 1</div>
<!--<div class="span4 <?php if($_SESSION['step']!=2) echo "muted"; ?>" >Step 2</div>-->
<div class="span6 <?php if($_SESSION['step']!=3) echo "muted"; ?>" >Step 2</div>
</div>



<div class="progress progress-striped active" >



<!--progress bar showing question setting progress-->
<?php
if($_SESSION['step']>0)
 {
  //echo "ss1";
  echo '<div class="bar"  style="width:50%;" ></div>';
 }
 
if($_SESSION['step']>1)
 {
  //echo "ss3";
  echo '<div class="bar bar-success"  style="width:50%;" ></div>';
 }
echo '</div>';
 
?>
<!--end-->

<!--showing the type of exam being set-->
  <div class="row text-center">
  <div class="span3 text-left">
  <?php
   echo '<p class="lead"><b>Course:</b><span class="text-success" > '.$_SESSION['course'].'</span></p>';
   echo '<p class="lead"><b>Test:</b>';
   if($_SESSION['exam']==1)
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($_SESSION['exam']==2)
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($_SESSION['exam']==3)
    echo '<span class="text-success" > Mid sem </span>';
   else if($_SESSION['exam']==4)
    echo '<span class="text-success" > End sem </span>';
   else
    echo '<span class="text-error">not set</span>';
   echo '</p>';
  echo '<p class="lead"><b>Prof:</b> <abbr title="'.$_SESSION['name'].'" ><span class="text-success" >  '.$_SESSION['faculty_id'].'</span></p>';
  ?>
  </div>
  <!--end-->
<?php
if($_SESSION['step']==1)
 {
  //echo "ss4";
  ?>
  <?php
   if(isset($_SESSION['course']))
    {
	   //echo $_SESSION['course']." h ".$_SESSION['exam']." h ".$_SESSION['date'];
	 $test=mysql_fetch_array(mysql_query("select examtype, duration, date, max_marks from test where course_id='$_SESSION[course]' and  examtype='$_SESSION[exam]' and date='$_SESSION[date]' "));
	}
  ?>
  <!-- selecting the type of the exam to be attempted-->
  <div class="span4 offset1" >
  <p class="lead" >Exam scheduling</p>
  <form class="form-horizontal" action="set_questions.php" method="get" >
  <div class="control-group" >
  <label class="control-label" for="exam"  ><big><strong>Exam: </strong></big></label>
  <div class="controls">
  <select name="exam" id="exam">
  <option value="1" <?php if($test['type']==1) echo "selected"; ?> >Q1</option>
  <option value="2" <?php if($test['type']==2) echo "selected"; ?> >Q2</option>
  <option value="3" <?php if($test['type']==3) echo "selected"; ?> >MidSem</option>
  <option value="4" <?php if($test['type']==4) echo "selected"; ?> >EndSem</option>
  </select>
  </div>
  </div>


<!--duration of exam-->
  <div class="control-group" >
  <label class="control-label" for="duration"  ><strong>Duration: </strong></label>
  <div class="controls">
  <input type="text" class="input" name="duration" id="duration"
  <?php 
	if($test['duration'])
	    echo "value=".$test['duration']; 
  ?> maxlength="5" placeholder="(Ex: 2:30 i.e, 2 hrs 30 mins)" />
  </div>
  </div>
  
  <!--date of the exam-->
  <div class="control-group" >
  <label class="control-label" for="exp_date"  ><strong>Expected Date: </strong></label>
  <div class="controls">
  <input type="date" class="input" name="exp_date" id="exp_date" maxlength="10" <?php  if($test['duration']) echo "value=".$test['date']; ?> placeholder="YYYY-MM-DD" />
  </div>
  </div>
  <!--Maximum marks of the exam -->
  <div class="control-group" >
  <label class="control-label" for="max_marks"  ><strong>Max Marks: </strong></label>
  <div class="controls">
  <input type="number" class="input" name="max_marks" maxlength="3" <?php if($test['max_marks']) echo 'value="'.$test['max_marks'].'"'; ?> id="max_marks" placeholder="maximum marks..." />
  </div>
  </div>
  <!--end-->
  <div class="control-group" >
  <div class="controls">
  <button type="submit" class="btn btn-primary" name="step" value="2" >Next <i class="icon-chevron-right icon-white"></i></button>
  </div>
  </div>
  
  </form>
  </div>
<?php

}
 
if($_SESSION['step']==2)
 {
  ?>
    <div class="span6 offset1" >
    <p class="lead" >You are almost done with setting the question paper.</p>
	<h3>Summary</h3>
  
  <?php
    //echo $_SESSION['course'].;
    display_test_info($_SESSION['course']."_".$_GET['exam']."_".$_GET['exp_date']);
  ?>
  <p><br />Click <span class="text-success" >Finish</span> to add questions or <span class="text-error" >Back</span> making changes to it.<br /><br /></p>
  <form action="set_questions.php" method="post">
  <a href="set_questions.php?step=1" class="btn btn-primary" name="step" value="1"><i class="icon-chevron-left icon-white"></i>Back </a>
  <button type="submit" class="btn btn-primary" name="step"  value="4" ><i class="icon-flag icon-white"></i> Finish</button>
  </form>
	</div>
  <?php
 }

}
?>
</div>
<div class="text-left row-fluid" ><a href="faculty.php" class="btn btn-primary text-right" ><i class="icon-white icon-chevron-left" ></i> Go Back</a></div>
<?php
 }
 else
  {
    echo '<script>window.location="index.php";</script>';
  }
require("footer.php");
?>