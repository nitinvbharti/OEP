<?php
session_start();


//////////////////////////////////////////////////////////////////////////////////////////////////
if($_SESSION['tab']!=7)
{
 $_SESSION['tab']=7;
 //echo '<meta http-equiv="refresh" content="0;fac_ques.php" >';
}
//////////////////////////////////////////////////////////////////////////////////////////////////
require("header.php");


if(isset($_POST['selected_exam']) && isset($_POST['go']))
{
	//echo $_POST['selected_exam'];
	$_SESSION['selected_exam']=$_POST['selected_exam'];
}

if(!isset($_SESSION['selected_exam']) && !(isset($_SESSION['exam']) && isset($_SESSION['date'])))
{
	//echo "I see u..";
	$all_dates=mysql_query("select date,examtype from test where course_id='$_SESSION[course]' ");
	//while($new=mysql_fetch_array($all_dates))
	//	echo $new['date']."  and  ";
	?>	
	<div class="row-fluid text-center">
	<br><br>
	<h3>Select Date </h3>
	<form method="POST" action="fac_ques.php">
		<select name="selected_exam">
		<?php while($date=mysql_fetch_array($all_dates))// && var_dump(validatedate($date['date']) ))
		{
		?>
		<option value="<?php echo $date['date']."_".find_examtype($date['examtype']) ?>"><?php echo $date['date']." ".find_examtype($date['examtype']) ?></option>
		<?php
		}
		mysql_free_result($date_set);
		?>
		</select>
		<p></p>
		<button type="submit" value="go" name="go" class="btn btn-info btn-large" ><i class="icon-ok icon-white" ></i> Submit </button>
		</form>
	</div>
<?php
}
else if(isset($_SESSION['faculty_id']) && isset($_SESSION['course']))
 {
 	
 	//echo $_SESSION['selected_exam'];
 	////////////To make all data default if reached from finish in set_questions.php
 	$check=mysql_fetch_array(mysql_query("select step from test where examtype='$_SESSION[exam]' and course_id='$_SESSION[course]' and date='$_SESSION[date]' "));
 	//echo "if i m here".$check[step]."need help";
 	if($check['step']==4 && isset($_SESSION['exam']) && isset($_SESSION['date']))
 	{
 		$_SESSION['selected_exam']=$_SESSION['date']."_".find_examtype($_SESSION['exam']);
 		//echo "i reached here";
 		$exmtyp=find_examtype($_SESSION['exam']);
 		//echo $exmtyp;
 		$checksem=mysql_fetch_array(mysql_query("select semester from test where examtype='$_SESSION[exam]' and course_id='$_SESSION[course]' and date='$_SESSION[date]' "));
 		$sem=find_semester($checksem['semester']);
 		$ndate=explode('-',$_SESSION['date']);
 		$currentcoursetable=$_SESSION['course']."_".$exmtyp."_".$sem."_".$ndate[0];
 		$_SESSION['ctTable']=$currentcoursetable;
 		//echo "Here the table name is = ".$currentcoursetable;   //-->> Correct till here
 		$checkcourseDB=mysql_query("SHOW TABLES LIKE '".$_SESSION['course']."_".$exmtyp."' ");
 		$ctDB=mysql_num_rows($checkcourseDB);
 		$masterDB=$_SESSION['course']."_".$exmtyp;
 		if($ctDB=="0")
 		{	
 			////////////Creating Dynamic table for master question bank of the course if not exists/////////////
 			mysql_query("create table $masterDB(que_no INT(5),question VARCHAR(200),answer char(1),option1 varchar(20),option2 varchar(20),option3 varchar(20),option4 varchar(20),primary key (que_no)); ") or die (mysql_error());
 		}
 		$ctTable=mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$currentcoursetable."' "));
 		if($ctTable==0)
 		{
 			/////////////////Creating table for exam if doesn't exists/////////
	 		mysql_query("create table $currentcoursetable(question_no INT(5),neg_marking INT(1),marks INT(2),primary key(question_no),foreign key (question_no) references $masterDB(que_no)); ") or die (mysql_error());
 		}
 		unset($_SESSION['exam']);
		unset($_SESSION['date']);
		unset($_SESSION['step']);
 	}

  	if(isset($_POST['delete']) || isset($_POST['edit']) || isset($_POST['add']) || isset($_POST['change']))
   	{
   		//echo "Here";
   		$exam_details=explode("_", $_SESSION['selected_exam']);
   		$masterDB=$_SESSION['course']."_".$exam_details[1];
   		$max=mysql_fetch_array(mysql_query("select que_no from $masterDB order by que_no desc"));
   		//echo "HERE".$max['que_no']."select * from $masterDB "."ends";
   		if(!isset($max['que_no']))
   			$qno=1;
   		else
	    	$qno=$max['que_no']+1;
	    //echo "Here";
		if(isset($_POST['add']))
		{
			$exmno=find_number($exam_details[1]);
			$max_marks=mysql_fetch_array(mysql_query("select max_marks from test where course_id='$_SESSION[course]' and examtype=$exmno and date='$exam_details[0]' "));
			$m=mysql_fetch_array(mysql_query("select sum(marks) as m from $_SESSION[ques_bank_id]"));
			if(!isset($m['m']))
				$m['m']=0;
	        //echo "HERE"."select max_marks from test where course_id='$_SESSION[course]' and examtype=$exmno and date='$exam_details[0]' "."ends";//---------->correct
	        //echo $m['m'];
	        if($max_marks['max_marks']<($m['m']+$_POST['marks']))
	        {
	        	echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Max Marks Limit reached</div>';
	        	$qmarks=$max_marks['max_marks']-$m['m'];
	        }
	        else
	        {
	        	$qmarks=$_POST['marks'];
	        }
			    //echo "hello";
				//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
				//$op = $_POST['op1']."|".$_POST['op2']."|".$_POST['op3']."|".$_POST['op4'];
				//echo "File: ".$_FILES["file"]["name"][0];
				if($_FILES["file"]["name"])
				{
				  	//echo "hai";
				  	$allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG");
		          	$extension = end(explode(".", $_FILES["file"]["name"]));
				   	if ((($_FILES["file"]["type"] == "image/gif")|| ($_FILES["file"]["type"] == "image/jpeg")|| ($_FILES["file"]["type"] == "image/jpg")|| ($_FILES["file"]["type"] == "image/png"))&& ($_FILES["file"]["size"] < 3000000 )&& in_array($extension, $allowedExts))
				    {
		                if ($_FILES["file"]["error"] > 0 )
		                {
		                  	echo "Some Unexpected Error. Try Again.. ";
		                }
		              	else
		                { 
						    $file_success=1;
							//echo "fine";
				        }
			        }
				   else
				    {
				       echo "file is not within range/ unsupported format";
				    }
				}
				else
				{
				 	 $extension=0;
				}
				 
				//list of questions 
				$no_repeat=mysql_num_rows(mysql_query("select ques_no from $masterDB where ques='$_POST[ques]' "));
				
			    if(!(isset($no_repeat)) and $qmarks!=0)
				{
				 	//echo "hi";
					$query_t= mysql_query("INSERT INTO $masterDB VALUES ('$qno','$_POST[ques]','$_POST[ans]', '$_POST[op1]', '$_POST[op2]', '$_POST[op3]', '$_POST[op4]')") or die("Died2".mysql_error());
					$query_t1=mysql_query("insert into $_SESSION[ques_bank_id] VALUES ('$qno','$_POST[neg_marks]','$qmarks') ");
					/////echo "HERE"."insert into $_SESSION[ques_bank_id] VALUES ('$qno','$_POST[neg_marks]','$qmarks') "."ends";---------->correct
					if($file_success)
				 	{
						if(file_exists($_SESSION["course"]."_".$qno.".".$extension))
					 		unlink($_SESSION["course"]."_".$qno.".".$extension);
		            	move_uploaded_file($_FILES["file"]["tmp_name"],"img/qns/".$_SESSION["course"]."_".$qno.".".$extension);
				 	}
		        	echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Question  added!</div>';
					$_SESSION['ques_id']=$qno;
				}
				else
				{
					echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>No more questions can be added!!! </strong></div>';
				}
			
		//echo '<script>window.location="fac_ques.php";</script>';
		}
		else if(isset($_POST['delete']))// deleting a questions from database 
		{
		    //echo 'hi';
			//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
			$qns=mysql_fetch_array(mysql_query("select if_image from question_bank where ques_id='$qno' "));
		    if(file_exists($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$qns['if_image']))
			  unlink($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$qns['if_image']);
			$ques_no=mysql_fetch_array(mysql_query("select que_no from $masterDB where question='$_POST[ques]' "));
			//echo $_POST['ques']."HERE"."select que_no from $masterDB where question='$_POST[ques]' "."ends";
			$query_t= mysql_query("DELETE FROM $_SESSION[ques_bank_id] WHERE question_no=$ques_no[que_no] " ) or die("Died2".mysql_error());
	        echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Question deleted!</div>';
			//echo '<script>window.location="fac_ques.php";</script>';
		}
		else if(isset($_POST['edit']) || isset($_POST['change'])) //  editing a present question
		{
			if(isset($_POST['edit']))
			{
		   	questions_header();
			//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
			$ques_ed= mysql_query("SELECT * FROM $masterDB WHERE que_no=$_POST[quesno] ") or die("Died2".mysql_error());
			$ques = mysql_fetch_array($ques_ed);
			$mark_details=mysql_fetch_array(mysql_query("select * from $_SESSION[ques_bank_id] where question_no=$ques[que_no] "));
			//////echo "HERE".$ques['que_no']."SELECT * FROM $masterDB WHERE que_no=$_POST[quesno] "."ends";----------------->> correct
			//$options = $ques['options'];
			//$opts = explode("|",$options);
			//$test=mysql_fetch_array(mysql_query("select * from tests where test_id='$_SESSION[tid]' "));
			
			?>
			<form method="POST" action="fac_ques.php" enctype="multipart/form-data" >
			<table class="table table-hover table-striped table-bordered" >
			<tr class="qns" >
				<th>Q.No</th>
				<th>Question</th>
				<th>Options</th>
				<th>Answer</th>
				<?php
                 echo '<th>Marks</th>';
                 echo '<th>-ve Marks</th>';				 
				?>
				<!-- Table showing the questions to entered and questions to be selected-->
			</tr>
				<tr>
					<td name="que_no" value=<?php echo $ques['que_no'];?> >1</td>
					<td ><textarea   rows="1" name="ques" style="min-width:300px;min-height:140px;" required><?php echo $ques['question'];?></textarea></td>			
						<td>
                         <input type="text" class="input-small" name="op1" value="<?php echo $ques['option1'];?>" required/><br /><input  class="input-small" type="text" name="op2" value="<?php echo $ques['option2'];?>" required/><br /><input  class="input-small" type="text" name="op3" value="<?php echo $ques['option3'];?>" required/><br /><input  class="input-small" type="text" name="op4" value="<?php echo $ques['option4'];?>" required/>
						 </td>
					<td><input type="text" class="input-small"  maxlength="7" style="width:60px;" name="ans" placeholder="(Ex:A|B)" value="<?php echo $ques['answer'];?>" required/></td>
					
					  <td><input type="number" name="marks"  class="input" style="width:30px;" value="<?php echo $mark_details['marks'];?>" required/></td>
					
					  <td><input type="number" name="neg_marks"  class="input" style="width:30px;" value="<?php echo $mark_details['neg_marking'];?>" required/></td>
					
					
				  <br /><br />
					<td><button type="submit" class="btn btn-warning btn-large" name="change" value="Update" ><i class="icon-white icon-ok" ></i> Update</button><input type="hidden" value="<?php echo $ques['que_no']; ?>" name="quesno" />
					</tr>
			</table>
			</form>
			<div class="row-fluid text-center">
			<a href="faculty.php" class="btn btn-primary btn-large" ><i class="icon-white icon-chevron-left" ></i> Go Back</a>
			</div>
		<?php	
	}
	else
	{
		//echo "here";
	    $qno=$_POST['quesno'];
//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
		//echo "File: ".$_FILES["file"]["name"][0];
		if($_FILES["file"]["name"])
		 {
		  $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG");
          $extension = end(explode(".", $_FILES["file"]["name"]));
		   if ((($_FILES["file"]["type"] == "image/gif")|| ($_FILES["file"]["type"] == "image/jpeg")|| ($_FILES["file"]["type"] == "image/jpg")|| ($_FILES["file"]["type"] == "image/png"))&& ($_FILES["file"]["size"] < 3000000 )&& in_array($extension, $allowedExts))
		       {
                 if ($_FILES["file"]["error"] > 0 )
                   {
                  echo "Some Unexpected Error. Try Again.. ";
				   $error=1;
                   }
                else
                   {
				    $file_success=1;
		           }
	           }
		   else
		      {
		       echo "file is not within range/ unsupported format";
			   $error=1;
		      }
		 }
		else
		 {
		  $extension=0;
		 } 
		 //echo "HERE".$qno;
		$query_t= mysql_query("update $masterDB set question='$_POST[ques]', answer='$_POST[ans]', option1='$_POST[op1]', option2='$_POST[op2]', option3='$_POST[op3]', option4='$_POST[op4]' where que_no=$qno ") or die("Died2".mysql_error());
		$query_currentDB=mysql_query("update $_SESSION[ques_bank_id] set marks=$_POST[marks],neg_marking=$_POST[neg_marks] where question_no=$qno ");
		//Showing/verifying the added external files in the question
		
		if($file_success)
		 {
			if(file_exists($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension))
			 unlink($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension);
            move_uploaded_file($_FILES["file"]["tmp_name"],"img/qns/".$_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension);
		 }
        echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Question no:'.$qno.' updated!</div>';
	}
   }
  }//end of if
     if(!$_POST['edit'])
	 {
	 	//echo "It's Here".$_SESSION['selected_exam'];
	  fetch_questions($_SESSION['course'],$_SESSION['selected_exam']);
	 }
}//end of if
else
 {
    echo '<script>window.location="index.php";</script>';
 }


include("footer.php");
?>