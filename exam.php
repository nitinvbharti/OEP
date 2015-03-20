<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
 echo '<div class="row-fluid text-center">';
 
if(isset($_POST['complete']) || isset($_SESSION['complete']))
 {
  unset($_SESSION['course']);
  $_SESSION['complete']=1;
 // $testtype=mqsql_query("SELECT examtype FROM test WHERE course_id='$_SESSION[course_id]' and exam_activation='1'");
  $testtype=$_SESSION['examtype'];
  if($testtype==1)
  	$exam="q1_taken";
  elseif ($testtype==2)
  	$exam="q2_taken";
  elseif ($testtype==3)
  	$exam="endsem_taken";
  elseif ($testtype==4)
  	$exam="supplementary_taken";
  elseif ($testtype==5)
  	$exam="makeup_taken";

  mysql_query("update student_exam_status set '$exam'=1,where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[tid]' ");
 }

else 
 {
 if(isset($_POST['go']) || isset($_SESSION['course']))
  {
    //if(!isset($_SESSION['course']))
	 //{
	  //echo 'asdfasf';
     // $_SESSION['course']=$_POST['course'];
	 //}
	
 //unset($_SESSION['tid']);
		
	//if(isset($_SESSION['tid']))
	 {
	  if(!mysql_fetch_array(mysql_query("select done from exam_taken where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]'")))
	   {
	    mysql_query("insert into exam_taken set rollnumber='$_SESSION[rollnumber]', test_id='$_SESSION[tid]' ");
	   }
	  
	  echo '<br /><div class="row-fluid" >';
	  echo '<div class="span4 text-left">';
	  echo '<span class=lead >Max Marks: ';
	  if($test['equal_weight'])
	   {
	    echo $test['max_marks'];
	   }
	  else
	   {
	    if(!isset($_SESSION['max_marks']))
		 {
	    $marks=mysql_fetch_array(mysql_query("select sum(marks) as max_marks from question_bank where ques_bank_id=(select ques_bank_id from ques_bank_no where test_id='$_SESSION[tid]') "));
		$_SESSION['max_marks']=$marks['max_marks'];
		 }
		echo $_SESSION['max_marks'];
	   }
	  echo '</span>';
	  echo '</div>';
	  echo '<div class="span4"><span class="lead text-info"><big>';

    if($_SESSION['tid']==1)
    echo 'Quiz 1';
   else if($_SESSION['tid']==2)
    echo 'Quiz 2';
   else if($_SESSION['tid']==3)
    echo 'Mid sem';
   else if($_SESSION['tid']==4)
    echo 'Viva';
   else if($_SESSION['tid']==5)
    echo 'End sem';
	  echo ' - <span class="text-success"><abbr title="'.$_SESSION['course_name'].'" >'.$_SESSION['course'].'</abbr></span></big></span></div>';
   
   echo '<div class="span4 text-right">';
   duration();
   echo '</div>';
   echo '</div>';
	 
	 if(!isset($_SESSION['ques_bank_id']))
	  {
	    $select=mysql_query("select ques_id, ques_bank_id from question_bank where ques_bank_id=(select ques_bank_id from ques_bank_no where test_id='$_SESSION[tid]') ");
	    $qbank=0;
	 while($qn=mysql_fetch_array($select))
	  {
	    if(!isset($_SESSION['ques_bank_id']))
		 $_SESSION['ques_bank_id']=$qn['ques_bank_id'];
		 
	   $qbank++;
	   $q_array[]=$qn['ques_id'];
	  }
	 $qcount=0;
	 while($qcount!=$qbank)
	  {
	   $index=rand(1,5)-1;
	   $error=0;
	   for($i=0;$i<$qcount;$i++)
	    {
		 if($index==$tmp_array[$i])
		 {
		  $error=1;
		  break;
		 }
		}
	   if(!$error)
	    {
		 $qno=$index+1;
		 $_SESSION["q".$qno]=$q_array[$index];
		 $tmp_array[$qcount]=$index;
		 $qcount++;
		}
	  }
	   $_SESSION['qcount']=$qbank;
	   $_SESSION['cqno']=1;
	   $_SESSION['cqn']=$_SESSION["q1"];
	  }
	  
	 //foreach($_SESSION as $key => $value)
	  //{
	  // echo '<br />'.$key.' = '.$value;
	  //}
	 
	 

	 
	 echo '<br />
	 <div class="row-fluid"  >
	    <div class="span9" style="text-align:justify;min-height:390px;padding:20px;border-radius:3px;border:1px solid #F5F5F5;box-shadow:0px 0px 5px 0px grey;" >';
     if(isset($_POST['qn']))
	  {
	   //update_answers($_POST['ops']);
	   
	   $vals=explode("_",$_POST['qn']);
	   $_SESSION['cqno']=$vals[1];
	   $_SESSION['cqn']=$vals[0];
	  }
	 if(isset($_POST['prev']))
	  {
	   if($_SESSION['cqno']>1)
	    {
		 //update_answers($_POST['ops']);
		 
	    $_SESSION['cqno']=$_SESSION['cqno']-1;
	    $qno=$_SESSION['cqno'];
	    $_SESSION['cqn']=$_SESSION["q".$qno];
	    }
	  }
	  
	 if(isset($_POST['next']))
	  {
	   if($_SESSION['cqno']<$_SESSION['qcount'])
	    {
		 //update_answers($_POST['ops']);
		 
	    $_SESSION['cqno']=$_SESSION['cqno']+1;
	    $qno=$_SESSION['cqno'];
	    $_SESSION['cqn']=$_SESSION["q".$qno];
	    }
	  }
	 if(isset($_POST['answer']))
	  {
	   update_answers($_POST['ops']);
	  }
	  
	   update_duration();
	   $question=mysql_fetch_array(mysql_query("select * from question_bank where ques_id='$_SESSION[cqn]' and ques_bank_id=(select ques_bank_id from ques_bank_no where test_id='$_SESSION[tid]')"));
	   $options=explode("|",$question['options']);
	   
	 echo '<div class="lead" >Q<span id="qno">'.$_SESSION['cqno'].'</span>) '.$question['ques'].'? <span class="text-info">';
	 if(!$test['equal_weight'])
	  echo '['.$question['marks'].' M]';
	 else
	  {
	   $marks=$test['max_marks']/$_SESSION['qcount'];
	   echo '['.$marks.' M]';
	  }
	 echo '</span>';
     if($test['neg_marking'])
      echo ' <span class="text-error">['.$question['neg_marks'].' M]</span>';
	 echo '</div>';
	 $answers=mysql_fetch_array(mysql_query("select ans from answers where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' and ques_id='$_SESSION[cqn]' "));
     $ops=explode("|",$answers['ans']);
	 //echo $answers['ans'];
	 echo '<div class="row-fluid " ><div class="span6 lead"><form action="student.php" method="post" >
	 <input type="checkbox"  name="ops[]" id="op2"  value="A" ';
	 foreach($ops as $key =>$value)
	  {
	   if($value=="A")
	    {
		 echo ' checked';
		 break;
		}
	  }
	 echo '/>&nbsp; A) <span id="op1t">'.$options[0].'</span>';
	 echo '<br /><input type="checkbox" id="op2" name="ops[]" value="B" ';
	 foreach($ops as $key =>$value)
	  {
	   if($value=="B")
	    {
		 echo ' checked';
		 break;
		}
	  }
	 echo '/>&nbsp; B) <span id="op2t">'.$options[1].'</span>';
	 echo '<br /><input type="checkbox"  id="op3" name="ops[]"   value="C" ';
	 foreach($ops as $key =>$value)
	  {
	   if($value=="C")
	    {
		 echo ' checked';
		 break;
		}
	  }
	 echo '/>&nbsp; C) <span id="op3t">'.$options[2].'</span>';
	 echo '<br /><input type="checkbox"  id="op4" name="ops[]"   value="D" ';
	 foreach($ops as $key => $value)
	  {
	   if($value=="D")
	    {
		 echo ' checked';
		 break;
		}
	  }

	 echo '/>&nbsp; D) <span id="op4t">'.$options[3].'</span> </div>';
	 
	 if($question['if_image'])
	  {
	 echo '<div class="span5 offset1" >';
	 echo '<a href="#myModalimg" data-toggle="modal" class="thumbnail"><img src="img/qns/'.$_SESSION['course'].'_'.$_SESSION['ques_bank_id'].'_'.$_SESSION['tid'].'_'.$question['ques_id'].'.'.$question['if_image'].'"  style="height:200px;"  /></a>';
?>

<div id="myModalimg" class="modal hide fade" style="width:80%;margin-left:-550px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Question Image</h3>
</div>
<div class="modal-body text-center">
<?php echo '<img src="img/qns/'.$_SESSION['course'].'_'.$_SESSION['ques_bank_id'].'_'.$_SESSION['tid'].'_'.$question['ques_id'].'.'.$question['if_image'].'"  style="height:100%;" />'; ?>
<br /><br />
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
</div>

<?php
	 echo '</div>';
	 }
	 echo '</div>';
	 
	 if($_SESSION['qcount']>1)
	  {
	 echo '<br /> 
	 <div class="row-fluid" >
	 <div class="span4" style="text-align:left;" ><button type="submit" id="prev"name="prev" ';
	 if($_SESSION['cqno']==1)
	  echo ' class="btn btn-large btn-danger"  disabled';
	 else
	  echo ' class="btn btn-info btn-large" ';
	 echo '/><i class="icon-white icon-arrow-left"></i>Prev</button></div>
	 <div class="span4" style="text-align:center;"><button type="submit" id="answer"name="answer" class="btn btn-large btn-success" ><i class="icon-white icon-ok"></i>Answer</button></div>
	 <div class="span4" style="text-align:right;"><button type="submit" id="next"name="next" ';
	 if($_SESSION['cqno']==$_SESSION['qcount'])
	  echo ' class="btn btn-large btn-danger"  disabled';
	 else
	  echo ' class="btn btn-info btn-large" ';
	 echo '>Next <i class="icon-white icon-arrow-right"></i></button></div>
	 </div>';
	  }
	

	 echo '</div>';
	 echo '<div class="span3" style="padding:20px;min-height:390px;border-radius:3px;border:1px solid #F5F5F5;box-shadow:0px 0px 5px 0px grey;" >';
	 echo '<table align=center >';
	 //$qbank=30;
	 for($i=1;$i<=$_SESSION['qcount'];$i++)
	  {
	   if($i%4==1)
	    echo '<tr>';
	   $qid=$_SESSION["q".$i];
	   echo '<td class="qn" ><button type="submit" name="qn" value="'.$qid.'_'.$i.'"        class="btn btn-primary btn-large ';
	   $ans=mysql_fetch_array(mysql_query("select ans from answers where  rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' and ques_id='$qid' "));
	   if($ans['ans'])
	    echo ' btn-success';
	   else
	    echo ' btn-danger';
	   echo '" />'.$i.'</button></td>';
	   if($i%4==0)
	    echo '</tr>';
	  }
	 echo '</table>';
	 echo '<br /><table>';
	 echo '<tr><td><div class="ans" ></div></td><td class="span6" style="text-align:left;" >&nbsp;&nbsp;Answered</td><td><div class="nans" ></td><td class="span6" style="text-align:left;"></div>&nbsp;&nbsp;Not Answered</td></tr>';
	 echo '</table>';
	 echo '</div>';
	 echo '<div class="row">';
	 echo '<div class="span4 offset4"><br /><button class="btn btn-primary btn-large btn-block" name="complete" ><i class="icon-white icon-flag" ></i> Test Completed</button></span></div>';
	 echo '</div></form>';
	 
   }


  }
 }
/*  
 if(!isset($_SESSION['course']))
  {
   if(!isset($_SESSION['complete']))
    {
 echo '<br /><br /><br /><p class="lead" ><big>Select the Course</big></p>';

 $select=mysql_query("select course_id from enroll where rollnumber='$_SESSION[rollnumber]' ");
 echo '<form action="student.php" method="post" ><select name="course">';
 while($row=mysql_fetch_array($select))
  {
    echo '<option value='.$row['course_id'].'>'.$row['course_id'].'</option> ';
  }
 echo '</select>';


 echo '<br /><br /><button type="submit" value="go" name="go" class="btn btn-info btn-large" ><i class="icon-ok icon-white" ></i> Take Exam</button>';
 echo '</form>';
    }
   else
    {
	 update_duration();
     echo '<div class="row-fluid" ><br /><br /><br /><br /><br /><br /><br /><i class="icon-thumbs-up" ></i> <h1 class="text-success" >Test Completed!!!</h1></div>';
	}
  }
 

 //echo '</div>';
 */
}
else
{
 //echo '<script>window.location="index.php";</script>';
}



include("footer.php");
?>
