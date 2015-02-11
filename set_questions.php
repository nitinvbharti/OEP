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
   ///////////////fetching the previous data in incomplete exams ////////////////////////
if($_POST['incomp'])
 {

  $_SESSION['tid']=$_POST['test_id'];
  $test=mysql_fetch_array(mysql_query("select step,course_id,type from tests where test_id='$_SESSION[tid]' "));
  $_SESSION['step']=$test['step'];
  $_SESSION['course']=$test['course_id'];
  $_SESSION['type']=$test['type'];
 }
else if($_SESSION['tid']=="" && $_SESSION['course']=="")
 {
  //////////////////////////////////////if creating the course earlier attempted/////////////////////////////
$query=mysql_query("select test_id, step, course_id,type from tests where step<4 ");
$count=mysql_num_rows($query);
if($count==1)
 {
  $test=mysql_fetch_array($query);
  $_SESSION['tid']=$test['test_id'];
  $_SESSION['step']=$test['step'];
  $_SESSION['course']=$test['course_id'];
  $_SESSION['type']=$test['type'];
 }
if($count>1)
 {///////////////////////////////////if  previously unconfigured exam exists//////////////////////////
  echo '<div class="row text-center" ><p>You have more than one incompletely configured test! Please select one below:</p>';
  echo '<form action=set_questions.php method=post ><select name=test_id >';
  while($row=mysql_fetch_array($query))
   {//////////////////////////////////fetching the previous data////////////////////////////////
     echo '<option value='.$row[test_id].' >'.$row[course_id].': Test Id: '.$row[test_id].'</option>';
   }
  echo '</select><br /><input type=submit class="btn btn-primary" name=incomp value="Go" /></form></div>';
 }
 }
////////////////////////////////////////////////////////////////////////////////////////////////////////


if($_SESSION['tid']=="" && $_SESSION['course']=="" && $count==0)
{
 echo '<script>window.location="faculty.php";</script>';
}
else if(isset($_SESSION['course']))
{
if($_SESSION[step]=="")
 $_SESSION[step]=1;

if($_GET['step']==1)
 {
  $_SESSION['step']=1;
 }
 
 ////second step in series of setting exam to get details of question paper like max marks,questions count etc.
if($_GET['step']==2)
 {
  if($_GET['exam'])
   {
		//echo $_GET[duration];
		//echo $_GET[exp_date];
		
		if(!validateDate($_GET[exp_date]))/// check if vali date has entered
		{
		unset($_GET[exp_date]);
		echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid date!</div>';
		$_SESSION['step']=1;
		}
		else if(!validateTime($_GET[duration]))//check if valid time has been entered
		{
		unset($_GET[duration]);
		echo '<div class="alert fade in" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Sorry!!! </strong>Invalid duration!</div>';
		$_SESSION['step']=1;
		}
		else
		{
		if(!(isset($_SESSION['tid'])))
		{
		$_SESSION['type']=$_GET['exam'];
		$_SESSION['tid']=mysql_num_rows(mysql_query("select test_id from tests"))+1;
		mysql_query("insert into tests set course_id='$_SESSION[course]', type='$_GET[exam]', duration='$_GET[duration]', date='$_GET[exp_date]', step='$_SESSION[step]', test_id='$_SESSION[tid]' ");
		}
		else
		{
		mysql_query("update tests set course_id='$_SESSION[course]', type='$_GET[exam]', duration='$_GET[duration]', date='$_GET[exp_date]', step='$_SESSION[step]' where test_id='$_SESSION[tid]' ");
		}
		$_SESSION['step']=2;
		}
		}
		else
		{
		$_SESSION['step']=2;
		}
		
 }
 
 ////////////third and final stage for creation of new exam////////////////////////
if($_GET['step']==3)
 {
  if(isset($_SESSION['tid']) && $_GET['max_marks']!="" )
   {
    $_SESSION['step']=3;
     mysql_query("update tests set max_marks='$_GET[max_marks]', equal_weight='$_GET[uniform]', max_qns='$_GET[max_qns]', neg_marking='$_GET[neg_marking]', sets='$_GET[sets]', display='$_GET[display]', step='$_SESSION[step]' where test_id='$_SESSION[tid]'");
   }
  else
   {
    $_SESSION['step']=2;
    echo '<script>window.location="set_questions.php?step=2";</script>';
   }
 }
 
if($_POST['step']==4)
 {
  $_SESSION['step']=4;
  mysql_query("update tests set step=4 where test_id='$_SESSION[tid]' ");
 }

 
if($_SESSION['step']==4)
    echo '<script>window.location="fac_ques.php";</script>';
 
?>
<div class="row text-center">
<p class="lead" >Set Question Paper</p>
</div>
<div class="row text-center lead" >
<div class="span4 <?php if($_SESSION['step']!=1) echo "muted"; ?>" >Step 1</div>
<div class="span4 <?php if($_SESSION['step']!=2) echo "muted"; ?>" >Step 2</div>
<div class="span4 <?php if($_SESSION['step']!=3) echo "muted"; ?>" >Step 3</div>
</div>



<div class="progress progress-striped active" >



<!--progress bar showing question setting progress-->
<?php
if($_SESSION['step']>0)
 {
  //echo "ss1";
  echo '<div class="bar"  style="width:33.333%;" ></div>';
 }
 
if($_SESSION['step']>1)
 {
  //echo "ss2";
  echo '<div class="bar bar-warning"  style="width:33.333%;" ></div>';
 }
if($_SESSION['step']>2)
 {
  //echo "ss3";
  echo '<div class="bar bar-success"  style="width:33.333%;" ></div>';
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
   if($_SESSION['type']==1)
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($_SESSION['type']==2)
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($_SESSION['type']==3)
    echo '<span class="text-success" > Mid sem </span>';
   else if($_SESSION['type']==4)
    echo '<span class="text-success" > Viva </span>';
   else if($_SESSION['type']==5)
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
   if(isset($_SESSION['tid']))
    {
	
	 $test=mysql_fetch_array(mysql_query("select type, duration, date from tests where test_id='$_SESSION[tid]' "));
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
  <option value="4" <?php if($test['type']==4) echo "selected"; ?> >Viva</option>
  <option value="5" <?php if($test['type']==5) echo "selected"; ?> >EndSem</option>
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
  <input type="date" class="input" name="exp_date" id="exp_date" maxlength="10" <?php  if($test['duration']) echo "value=".$test['duration']; ?> placeholder="YYYY-MM-DD" />
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
 
 //getting the question paper detail
if($_SESSION['step']==2)
 {	
   if(isset($_SESSION['tid']))
    {
	 $test=mysql_fetch_array(mysql_query("select max_marks, equal_weight, max_qns, neg_marking, sets, display from tests where test_id='$_SESSION[tid]' "));
	}
 ?>
  <div class="span4 offset1" >
  <p class="lead" >Exam details</p>
  <form class="form-horizontal" action="set_questions.php" method="get" >
  <div class="control-group" >
  <label class="control-label" for="max_marks"  ><strong>Max Marks: </strong></label>
  <div class="controls">
  <input type="number" class="input" name="max_marks" maxlength="3" <?php if($test['max_marks']) echo 'value="'.$test['max_marks'].'"'; ?> id="max_marks" placeholder="maximum marks..." />
  </div>
  </div>
  
  <div class="control-group" >
  <label class="control-label" for="uniform"  ><strong>All questions carry equal marks: </strong></label>
  <div class="controls">
  <select name="uniform" id="uniform" ><option value="1"  <?php if($test['equal_weight']==1) echo "selected"; ?> >Yes</option><option value="0"  <?php if($test['equal_weight']==0) echo "selected"; ?> >No</option></select>
  </div>
  </div>
  <div class="control-group" >
  <label class="control-label" for="max_qns"  ><strong>Max Questions: </strong></label>
  <div class="controls">
  <input type="number" class="input" name="max_qns"  maxlength="3" <?php if($test['max_qns']) echo "value=".$test['max_qns']; ?> id="max_qns" placeholder="maximum questions..." />
  </div>
  </div>
  <div class="control-group" >
  <label class="control-label" for="neg_marking"  ><strong>Negative Marking: </strong></label>
  <div class="controls">
  <select name="neg_marking" id="neg_marking" ><option value="0"   <?php if($test['neg_marking']==0) echo "selected"; ?> >No</option><option value="1"   <?php if($test['neg_marking']==1) echo "selected"; ?> >Yes</option></select>
  </div>
  </div>
  
  
  <div class="control-group" >
  <label class="control-label" for="sets"  ><strong># Sets: </strong></label>
  <div class="controls">
  <select name="sets" id="sets" >
  <option value="1"   <?php if($test['sets']==1) echo "selected"; ?> >1</option>
  <option value="2"   <?php if($test['sets']==2) echo "selected"; ?> >2</option>
  <option value="3"   <?php if($test['sets']==3) echo "selected"; ?> >3</option>
  <option value="4"   <?php if($test['sets']==4) echo "selected"; ?> >4</option>
  <option value="5"   <?php if($test['sets']==5) echo "selected"; ?> >5</option>
  </select>
  </div>
  </div>
  
  
  <div class="control-group" >
  <label class="control-label" for="display"  ><strong>Should marks be displayed after test: </strong></label>
  <div class="controls">
  <select name="display" id="display" ><option value="0"    <?php if($test['display']==0) echo "selected"; ?> >No</option><option value="1"    <?php if($test['display']==1) echo "selected"; ?> >Yes</option></select>
  </div>
  </div>
  
  
  
  <div class="control-group" >
  <div class="controls">
  <a href="set_questions.php?step=1" class="btn btn-primary"  value="1" ><i class="icon-chevron-left icon-white"></i>Back </a>
  <button type="submit" class="btn btn-primary" name="step"  value="3" >Next <i class="icon-chevron-right icon-white"></i></button>
  
  </div>
  </div>
  
  </form>
  </div>
  <!-- end of getting the data for question-->
  
<?php
}
if($_SESSION['step']==3)
 {
  ?>
    <div class="span6 offset1" >
    <p class="lead" >You are almost done with setting the question paper.</p>
	<h3>Summary</h3>
  
  <?php
    display_test_info($_SESSION['tid']);
  ?>
  <p><br />Click <span class="text-success" >Finish</span> to add questions or <span class="text-error" >Back</span> making changes to it.<br /><br /></p>
  <form action="set_questions.php" method="post">
  <a href="set_questions.php?step=2" class="btn btn-primary" ><i class="icon-chevron-left icon-white"></i>Back </a>
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