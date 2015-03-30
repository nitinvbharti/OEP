<?php
session_start();
require 'header.php';
if(isset($_SESSION['rollnumber']))
{
echo '<div class="row-fluid text-center ">';
/*
echo '<div class="row"';
//echo " Current Time:"; 
echo '<div class="span4 offset4"><span class="lead" id="clock">&nbsp;&nbsp;&nbsp;Current Time: </span>';
echo '<script>
var myVar=setInterval(function(){myTimer()},1000);
myVar.style.color = "Red";
myVar.style.fontSize = "large";

function myTimer() {
    var d = new Date();
    document.getElementById("clock").innerHTML = d.toLocaleTimeString();
    
}
</script>;';
echo '</div></div>';*/
if($_POST['logout'])
  {
  		$dt = new DateTime();
       $dtformatted=$dt->format('Y-m-d H:i:s');
  	 mysql_query("UPDATE login set loggedin='0' AND lastlogin='$dtformatted' WHERE rollnumber='$_SESSION[rollnumber]'");
  	/* echo '<script>window.onbeforeunload = function (event) {
    var message = "Important: Please click on \"Save\"button to leave this page.";
    if (typeof event == "undefined") {
        event = window.event;
    }
    if (event) {
        event.returnValue = message;
    }
    return message;
};

$(function () {
    $("a").not("#lnkLogOut").click(function () {
        window.onbeforeunload = null;
    });
    $(".btn").click(function () {
        window.onbeforeunload = null;
});
});</script>';*/
  	/*if(isset($_SESSION['rollnumber']))
  		{
  		$dt = new DateTime();
       $dtformatted=$dt->format('Y-m-d H:i:s');
       mysql_query("UPDATE login set loggedin='0' AND lastlogin='$dtformatted' WHERE rollnumber='$_SESSION[rollnumber]'");
  		}*/
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

$exam=$_SESSION['examtype'].'_'.'taken';
if(isset($_POST['complete']) || isset($_SESSION['complete']))
 {
  $_SESSION['complete']=1;
  $val=mysql_query("update student_exam_status set $exam=1 where rollnumber='$_SESSION[rollnumber]'and course_id='$_SESSION[course]' ");
 //var_dump($val);
  
  unset($_SESSION['rollnumber']);
  echo "<div class='row'>";
  echo '<div class="span12 text-center">';
  echo '<span class="lead" >Congrats you have successfully'.$_SESSION['course'].'Completed The test</span>';
  unset($_SESSION['course']);
  echo '</div></div>';
  //echo '<script>window.location="index.php";}</script>';
  mysql_query("UPDATE login set loggedin='0' AND lastlogin='$dtformatted' WHERE rollnumber='$_SESSION[rollnumber]'");
  //echo '<script></script>';
 }
else 
 {
 $ExamTableName=$_SESSION['course'].'_'.$_SESSION['examtype'].'_'.$_SESSION['sem'];
 $_SESSION['ExamTableName']=$ExamTableName;
   //	echo $ExamTableName;
 if(isset($_POST['go']) || isset($_SESSION['course']))
  {
	  echo '<br /><div class="row-fluid" >';
	  echo '<div class="span4 text-left">';
	  echo '<span class=lead >Max Marks: ';
	    if(!isset($_SESSION['max_marks']))
		 {
	    $marks=mysql_fetch_array(mysql_query("select sum(marks) as max_marks from $ExamTableName"));
		$_SESSION['max_marks']=$marks['max_marks'];
		 }
		echo $_SESSION['max_marks'];
	  echo '</span>';
	  echo '</div>';
	echo '<div class="span4"><span class="lead text-info"><big>';
    echo $_SESSION['examtype'];
	echo ' - <span class="text-success"><abbr title="'.$_SESSION['course_name'].'" >'.$_SESSION['course'].'</abbr></span></big></span></div>';
   echo '<div class="span4 text-right">';
   duration();
   echo '</div>';
   echo '</div>';
	$qcount=0;
	$selectq=mysql_query("select question_no from $ExamTableName");
	$q_array=array();
	while($question_nums=mysql_fetch_array($selectq))
		{//var_dump( $question_nums);
		$q_array[]=$question_nums['question_no'];
		$qcount=$qcount+1;
		}
	if(!isset($_SESSION['cqno']))
	 { 	
		$_SESSION['qcount']=$qcount;
	    $_SESSION['cqno']=1;
	    $_SESSION['qlist']=$q_array;
	 //   $_SESSION['cqn']=$_SESSION["q1"];
	    $anstablename='ans'.'_'.$_SESSION['course'].'_'.$_SESSION['examtype'];
	 
	   $anstablename=strtolower($anstablename);
	     //var_dump($anstablename);
	    $_SESSION['ansTable']=$anstablename;
	    $select="SELECT count(*) FROM information_schema.columns WHERE table_name='$anstablename'";
	    $ans_col_num=mysql_fetch_array(mysql_query($select));
	    
   	    if($qcount!=($ans_col_num[0]-1))
   	    {
   	    	 $i=$ans_col_num[0];
   	    while($i<=$qcount){
   	    	if($i==1)
   	    		$colname_prev="rollnumber";
   	    	else
   	    		$colname_prev='Q'.($i-1);
   	    	$colname='Q'.$i;
   	    	//var_dump($colname,$colname_prev);
   	    	//echo $anstablename;
   	    	mysql_query("ALTER TABLE $anstablename ADD $colname VARCHAR(7) after $colname_prev");
   	   		$i=$i+1;
   	   		}
   	    }

	}
//var_dump($_SESSION['qlist']);
if(	isset($_SESSION['qcount'])	)
	 echo '<br />
	 <div class="row-fluid"  >
	    <div class="span9" style="text-align:justify;min-height:390px;padding:20px;border-radius:3px;border:1px solid #F5F5F5;box-shadow:0px 0px 5px 0px grey;" >';
  /*
     if(isset($_POST['qn']))
	  {
	   //update_answers($_POST['ops']);
	   
	   $vals=explode("_",$_POST['qn']);
	 //  echo $vals;
	   $_SESSION['cqno']=$vals[1];
	   $_SESSION['cqn']=$vals[0];
	  }
	  */
	 if(isset($_POST['prev']))
	  {
	   if($_SESSION['cqno']>1)
	    {
		 //update_answers($_POST['ops']);
	    $_SESSION['cqno']=$_SESSION['cqno']-1;
	//    var_dump($_SESSION['cqno']);
	    $_SESSION['cqn']=$_SESSION["q".$_SESSION['qcount']];
	    }
	  }
	  
	 if(isset($_POST['next']))
	  {
	   if($_SESSION['cqno']<$_SESSION['qcount'])
	    {	 
	    $_SESSION['cqno']=$_SESSION['cqno']+1;
	    $_SESSION['cqn']=$_SESSION["q".$_SESSION['qcount']];
	    }
	  }
	 if(isset($_POST['answer']))
	  {
	   update_answers($_POST['ops'],$_SESSION['ansTable'],$_SESSION['ansno']);
	  }
	  
	  // update_duration();
	   $QuestionTable=$_SESSION['course'].'_'.$_SESSION['examtype'];
	   $q=$q_array[$_SESSION['cqno']-1];
	   echo $q;
	   $question=mysql_fetch_array(mysql_query("select * from $QuestionTable where que_no='$q' "));
	   $i=3;
	   $options=array();
	   while ($i<=6) {
	   	$opno='option'.$i;
	  	$options[$i-3]=$question[$i];
	  	$i=$i+1;
	   }
//	   var_dump($opno);
	   echo '<br/>';
	   //var_dump($options);
	   echo '<div class="lead" >Q<span id="qno">'.$_SESSION['cqno'].'</span>) '.$question['question'].'? <span class="text-info">';
	 if(!$test['marks'])
	  {
	  	$quemarks="select marks from $_SESSION[ExamTableName] where question_no='$_SESSION[cqno]'";
	  	$test['marks']=mysql_fetch_array(mysql_query($quemarks));
	  }

	 echo '</span>';
	 /*
     if($test['neg_marking'])
      echo ' <span class="text-error">['.$question['neg_marks'].' M]</span>';
	 echo '</div>';
	 */
	 $ansno='Q'.$_SESSION['cqno'];
	 $andTable='ans'.'_'.$_SESSION['course'].'_'.$_SESSION['examtype'];
	 $ans1="select $ansno from $ansTable";
	 $_SESSION['ansno']=$ansno;
	 $answers=mysql_fetch_array(mysql_query("$ans1"));
     $ops=explode("|",$answers['ans']);
	 //echo $answers['ans'];
	 echo '<div class="row-fluid " ><div class="span6 lead"><form action="exam.php" method="post" >
	 <input type="checkbox"  name="ops[]" id="op2"  value="A" ';
	 foreach($ops as $key =>$value)
	  {
	   if($value=="A")
	    {
		 echo 'checked';
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
	 <div class="span4" style="text-align:right;"><button type="submit" id="next" name="next" ';
	 if($_SESSION['cqno']==$_SESSION['qcount'])
	  echo ' class="btn btn-large btn-danger"  disabled';
	 else
	  echo ' class="btn btn-info btn-large" ';
	  echo '>Next <i class="icon-white icon-arrow-right"></i></button></div>
	 </div>';
	 echo '<div class="row">';
	 echo '<div class="span4 offset4"><br /><button class="btn btn-primary btn-large btn-block" name="complete" ><i class="icon-white icon-flag" ></i> Test Completed</button></span></div>';
	 echo '</div>';
	  }
  
 }}
 echo '</div>';
include("footer.php");
?>
