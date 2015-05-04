
<?php
/*<!--
********************************************

It contains all thee related functions required for the project.

********************************************
-->*/


include("connect.php");

error_reporting(E_ERROR | E_PARSE);


//////////////////////////		This funtion contains code for validation of date in proper format and the time in proper values////////////////////
function validateTime($rawtime,$seperator2=':')
{
	//echo $rawtime;
	$ntime=explode($seperator2,$rawtime);
	//echo $ntime[0].$ntime[1];
	if($ntime[0]>=0 && $ntime[0]<=3 && $ntime[1]>=0 && ($ntime[0]<=2 &&$ntime[1]<=59))
	{
		return true;
	}
	return false;
}

//////////////// 	This function contains code to check if the date is correct and greater than todays date and it is in proper format as asked 		////////////////////

function validateDate($rawDate,$seperator='-')
    {
		//echo $rawdate;
		
        $ndate=explode($seperator,$rawDate);
        //var_dump($ndate);
		$cdate=date("Y-m-d");
		//echo $cdate
		$expldate=explode($seperator,$cdate);
        if($ndate[0]<$expldate[0] || $ndate[1]>12 || $ndate[1]<0 || ($ndate[0]==$expldate[0] && $ndate[1]==$expldate[1] && $ndate[2]<$expldate[2]))
        {
            return false;
        }
        if($ndate[0]%4==0)
        {
            $daysArray=array(31,29,31,30,31,30,31,31,30,31,30,31);
            if($ndate[1]>=1 && $ndate[1]<=12)
            {
                if($ndate[2]>=0&&$ndate[2]<=$daysArray[$ndate[1]-1])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
           
            $daysArray=array(31,28,31,30,31, 30,31,31,30,31, 30,31);
            if($ndate[1]>=1&&$ndate[1]<=12)
            {
                if($ndate[2]>=0&&$ndate[2]<=$daysArray[$ndate[1]-1])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
    }

//////////////// 	This function contains code to check if the date is correct or not and it is in proper format as asked 		////////////////////

function validateDate2($rawDate,$seperator='-')
    {
		//echo $rawdate;
		
        $ndate=explode($seperator,$rawDate);
        //var_dump($ndate);
		$cdate=date("Y-m-d");
		//echo $cdate;
		$expldate=explode($seperator,$cdate);
        if($ndate[0]<=2007)
        {
            return false;
        }
        if($ndate[0]%4==0)
        {
            $daysArray=array(31,29,31,30,31,30,31,31,30,31,30,31);
            if($ndate[1]>=1 && $ndate[1]<=12)
            {
                if($ndate[2]>=0&&$ndate[2]<=$daysArray[$ndate[1]-1])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            $daysArray=array(31,28,31,30,31, 30,31,31,30,31, 30,31);
            if($ndate[1]>=1&&$ndate[1]<=12)
            {
                if($ndate[2]>=0&&$ndate[2]<=$daysArray[$ndate[1]-1])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
    }

	///////		find the relevent personal data based on id 		////////
function search_who()
{
	if(isset($_SESSION['rollnumber']) || isset($_SESSION['faculty_id']) )
	{
		if(isset($_SESSION['faculty_id']))
		{
			echo ", $_SESSION[faculty_name]";
		}
		else if(isset($_SESSION['rollnumber']))
		{
			echo ", $_SESSION[student_name]";
		}
	}
}

/////////////////// 	This shows who is logged in by displaying there name /////////////////////

function signin_faculty()
{
	if(isset($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
		
		//echo $q;
		$query=mysql_query( "SELECT *  FROM faculty WHERE faculty_id = '$_POST[username]' AND password='$_POST[password]' ") or die("Died".mysql_error());
		$count = mysql_num_rows($query);
		
		if($count==1)
			{
			$row=mysql_fetch_array($query);
			echo "Welcome ".$row['name']."...";
			$_SESSION['faculty_id']=$_POST['username'];
			$_SESSION['faculty_name']=$row['name'];
			return 1;
			}
		else
			{
			return 0;
			}
	}
	else
	{	
		echo "Please Fill In the Required Fields";
		return 0;
	}
}

////////////////////// 		This shows the name of the student who is logged in //////////////

function signin_student()
{
	if(isset($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
		
		$query=mysql_query("SELECT *  FROM students WHERE rollnumber = '$_POST[username]' AND password='$_POST[password]' " ) or die("Died".mysql_error());
		$count = mysql_num_rows($query);
		
		if($count==1)
			{
			$row=mysql_fetch_array($query);
			echo "Welcome ".$row['name']."...";
			$_SESSION['rollnumber']=$_POST['username'];
			$_SESSION['student_name']=$row['name'];
			return 1;
			}
		else
			{
			return 0;
			}
	}
	else
	{	
	echo "Please Fill In the Required Fields";
	return 0;
	}
}
///////////////Select the examtype according to the value for creating databases as needed in short form////////////
function find_examtype($examtype)
{	
	if($examtype=="1")
	{
		$exmtyp="q1";
	}
	else if($examtype=="2")
	{
		$exmtyp="q2";
	}
	else if($examtype=="3")
	{
		$exmtyp="midsem";
	}
	else if($examtype=="4")
	{
		$exmtyp="endsem";
	}
	return $exmtyp;
}
///////////////Select the examtype according to the value for displaying in list as it must be properly explained   ////////////
function find_examtype_proper($examtype)
{	
	if($examtype=="1")
	{
		$exmtyp="Quiz 1";
	}
	else if($examtype=="2")
	{
		$exmtyp="Quiz 2";
	}
	else if($examtype=="3")
	{
		$exmtyp="Mid Sem";
	}
	else if($examtype=="4")
	{
		$exmtyp="End Sem";
	}
	return $exmtyp;
}	
///////////////lists which faculty will take what course/////////////
function list_all_courses($fac_id)
{
	//global $con;
	$course_set = mysql_query("SELECT * FROM slot_wise_courses WHERE faculty_id='$_SESSION[faculty_id]'") or die(mysql_error());
	return $course_set;
}

/////////////////////////////////checks if a given faculty teahes a given course/////////////

function if_okay_fac($c)
{
	//global $con;
	$query = mysql_query("SELECT * FROM courses WHERE course_id='$c' AND faculty_id='$_SESSION[faculty_id]'") or die("Died3".mysql_error());
	$count = mysql_num_rows($query);
	if($count==0)
	{
		return -1;
	}
	else
	{
		return 1;
	}
}

////////////////	This determines the semester, on the basis of number stored in database, which is later used in creating databases	//////////////

function find_semester($d)
{
	if($d=="1")
 	{
 		$sem="julynov";
 	}
 	else if($d=="2")
 	{
 		$sem="janmay";
 	}
 	return $sem;
}

////////////////	This function evaluates semester on the basis of month of the date entered. This saves the user from entering the semester  //////////////

function find_sem($m)
{
	if($m>=1 && $m<=6)
		return 2;
	else if($m>=7 && $m<=12)
		return 1;

}

/////////////////////////////////	fetch database and questions based on date,examtype and course chosen by the user	///////////

function fetch_questions($c,$d)
{
 if($_POST['goback'])
  {
   unset($_SESSION['init']);
   unset($_SESSION['selected_exam']);

  }
 if(!isset($_SESSION['selected_exam']) && !isset($_SESSION['init']))
  {
   if(!isset($_SESSION['selected_exam']))
    {
    	$selected_exam_details=explode('_',$d);
    	$dt=explode('-',$selected_exam_details[0]);
    	$table_name=$c."_".$selected_exam_details[1]."_".find_semester(find_sem($dt[1]))."_".$dt[0];
    	//echo "Table name : ".$table_name;
		$_SESSION['ques_bank_id']=$table_name;
    }
  else
   {
		$_SESSION['init']=1;
    	$selected_exam_details=explode('_',$d);
    	$dt=explode('-',$selected_exam_details[0]);
    	$table_name=$c."_".$selected_exam_details[1]."_".find_semester(find_sem($dt[1]))."_".$dt[0];
    	$_SESSION['ques_bank_id']=$table_name;
   }
  }
 else
  {
 	 	$_SESSION['init']=1;
    	$selected_exam_details=explode('_',$d);
    	$dt=explode('-',$selected_exam_details[0]);
    	$table_name=$c."_".$selected_exam_details[1]."_".find_semester(find_sem($dt[1]))."_".$dt[0];
    	$_SESSION['ques_bank_id']=$table_name;
  		//echo "see u".$_SESSION['ques_bank_id'].$d;
   		
  }
if(isset($_SESSION['init']))
 {
	 //echo "hereme";
	questions_header();
	if(isset($_SESSION['selected_exam']))
	 {
		//$t_id = get_test_id($c,$t);
		if(!isset($_SESSION['ques_bank_id']))
		 {
	    	$selected_exam_details=explode('_',$d);
    		$dt=explode('-',$selected_exam_details[0]);
    		$table_name=$c."_".$selected_exam_details[1]."_".find_semester(find_sem($dt[1]))."_".$dt[0];
    		$_SESSION['ques_bank_id']=$table_name;
		 }
	 }
	else
	 {
		if(!isset($_SESSION['ques_bank_id']))
		 {
		 	$selected_exam_details=explode('_',$d);
    		$dt=explode('-',$selected_exam_details[0]);
    		$table_name=$c."_".$selected_exam_details[1]."_".find_semester(find_sem($dt[1]))."_".$dt[0];
    		$_SESSION['ques_bank_id']=$table_name;
		 }
	 }
//echo $_SESSION['ques_bank_id'];///------------> correct till here
if($_POST['qadd'])
 {
 	
 	
  $qns=$_POST['quesno'];
  //echo $qns;
  $exam_dateandtype=explode("_", $_SESSION['selected_exam']);
  $masterDB=$_SESSION['course']."_".$exam_dateandtype[1];
  	//echo $masterDB;
   //$values=explode("_",$id);
   $mDB_question=mysql_fetch_array(mysql_query("select * from $masterDB where que_no=$qns "));
   //echo "New qno: ".$qno;
   //echo $values[0].$values[1];
   //echo $_SESSION[ques_bank_id];
   $m=mysql_fetch_array(mysql_query("select sum(marks) as m from $_SESSION[ques_bank_id] "));
   $examno=find_number($exam_dateandtype[1]);
   $test_details=mysql_fetch_array(mysql_query("select max_marks from test where course_id='$_SESSION[course]' and examtype=$examno and date='$exam_dateandtype[0]' "));;
   //echo $test_details['max_marks'];
   //echo "sum of marks"=$m['m'];
	if($test_details['max_marks']<($m['m']+$_POST['marks']))
	{
		echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Max marks limit reached. Marks Remaining = ';
		echo $test_details['max_marks']-$m['m'].'</div>';
	}
	else
	{
   //echo $_SESSION['course']."_".$values[0]."_".$test['test_id']."_".$values[1].".".$ext;
   //echo "<br />".$_SESSION['course']."_".$_SESSION['ques_bank_id']."_".$_SESSION['test_id']."_".$qno.".".$ext;
   if($ext)
    {
	 //echo "hi";
	 if(file_exists("img/qns/".$_SESSION['course']."_".$values[0]."_".$test['test_id']."_".$values[1].".".$ext))
	  {
     rename("img/qns/".$_SESSION['course']."_".$values[0]."_".$test['test_id']."_".$values[1].".".$ext, "img/qns/".$_SESSION['course']."_".$_SESSION['ques_bank_id']."_".$_SESSION['test_id']."_".$qno.".".$ext);
	  }
	}
	//echo "insert into $_SESSION[ques_bank_id] values($mDB_question[que_no], $_POST[neg_marks] ,$_POST[marks] ) ";
   $result=mysql_query("insert into $_SESSION[ques_bank_id] values($mDB_question[que_no], $_POST[neg_marks] ,$_POST[marks] ) ") or die(mysql_error());
  }
 }
 

        //echo "q:".$_SESSION['ques_bank_id'];
		//$qb_set = mysql_query("SELECT * FROM question_bank WHERE ques_bank_id='$_SESSION[ques_bank_id]' ");
 		$selected_exam_details=explode('_',$d);
 		$masterDB=$_SESSION['course']."_".$selected_exam_details[1];
		$test=mysql_fetch_array(mysql_query("select * from test where course_id='$_SESSION[course]' and examtype=$selected_exam_details[1] and  date='$selected_exam_details[0]' "));
		?>
        <div class="text-left" >
        <form action="faculty.php" method="post"><button type="submit" class="btn btn-primary text-right" name="goback" value="goback" ><i class="icon-white icon-chevron-left" ></i> Go Back</button></form></div><br />
		<table align="center" class="table table-striped table-hover table-bordered" >
			<tr class="qns" >
				<th>#</th>
				<th style="width:300px;" >Question</th>
				<th>Options</th>
				<th>Ans</th>
				<?php
                  echo '<th>Marks</th>';				 
				?>
				<th>Negative Marks</th>
				<th>Operation</th>
			</tr>
		<?php
			if(mysql_num_rows(mysql_query("select * from $_SESSION[ques_bank_id]"))>0)
			{
			    $counter=1;
			    $select=mysql_query("select * from $_SESSION[ques_bank_id]");
				while($ques_no = mysql_fetch_array($select))
				{
					$ques_details= mysql_fetch_array(mysql_query("select * from $masterDB where que_no='$ques_no[question_no]' "))
		?>  
				<tr class="qns" >
				<form action="fac_ques.php" method="post">
					<td><?php echo $counter++; ?></td>
					<td><?php echo $ques_details['question'] ?></td>
					<td><?php
                    echo "<b>A:</b> ".$ques_details['option1'];
					echo "<br /><b>B:</b> ".$ques_details['option2'];
					echo "<br /><b>C:</b> ".$ques_details['option3'];
					echo "<br /><b>D:</b> ".$ques_details['option4'];
					?></td>
					<td><?php echo $ques_details['answer'] ?></td>
					<?php  
						   echo '<td>';
						   echo $ques_no['marks'];  
						   echo '</td>'; 
                          
					   ?>
					<?php 
					   echo '<td>'; 
					   echo $ques_no['neg_marking']; 
					   echo '</td>';
                      ?>
					<td colspan="2" ><button name="edit" class="btn btn-warning" value="Edit" type="submit" ><i class="icon-white icon-pencil" ></i> Edit</button><input type="hidden" value="<?php echo $ques_details['que_no']; ?>" name="quesno" /> &nbsp;&nbsp;&nbsp;<a href="#myModaldel<?php echo "_".$ques_details['que_no']; ?>" role="button" class="btn btn-danger" data-toggle="modal"  ><i class="icon-white icon-remove" ></i> Delete</a>
                   <div id="myModaldel<?php echo "_".$ques_details['que_no']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                   <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h3 id="myModalLabel">Confirmation</h3>
                   </div>
                   <div class="modal-body">
                   <center><h4>Are you sure?</h4></center>
                   </div>
                   <div class="modal-footer">
                   <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">No</button>
				   <input type="hidden" name="ques" value="<?php echo $ques_details['question'] ; ?>" />
				   <input type="submit" name="delete" value="Yes" class="btn btn-danger" />
                   </div>
                   </div></td></form></tr>
		<?php
				}
			}
			else
			{
				$counter=1;	//echo "try adding some questions";
			}
			
			$max_array = mysql_fetch_array(mysql_query("select MAX(question_no) AS mm FROM $_SESSION[ques_bank_id] ")) or die(mysql_error());
			if(!isset($max_array['mm']))
			{
				//echo "here".$counter;
				$max=0;
			}
			else
				$max =  $max_array['mm']+1;
			?>
			
			<tr>
			<form action="fac_ques.php" method="post" name="fac_ques" enctype="multipart/form-data">
				<td><?php echo $counter;?></td>
				<td  style="text-align:center;" ><textarea  rows="1" name="ques" style="min-width:300px;min-height:50px;" required></textarea></td>
				<td style="text-align:center;" >
					<input type="text" class="input-small" name="op1" placeholder="Option A" required/><br /><input type="text" class="input-small"  name="op2" placeholder="Option B" required/><br /><input type="text" class="input-small"  name="op3" placeholder="Option C" required/><br /><input type="text" class="input-small"  name="op4" placeholder="Option D" required/>
				</td>
				
				<td style="text-align:center;" ><input type="text" class="input-small"  maxlength="7" style="width:60px;" name="ans" placeholder="(Ex:A|B)" required/></td>
				<?php
					 echo '<td style="text-align:center;" ><input type="number" class="input" style="width:30px;" name="marks" required/></td>';
					 echo '<td  style="text-align:center;" ><input type="number" class="input"  style="width:30px;" name="neg_marks" required/></td>';
					
				?>
				<!-- <td  style="text-align:center;" ><input type="file" class="input-small" name="file" style="width:80px;" ></td> -->
				<td colspan="2" style="text-align:center;vertical-align:middle;" ><button type="submit" class="btn btn-primary btn-block btn-large" name="add" value="Add" ><i class="icon-white icon-plus" ></i> Add</button></td>
				</form>
			</tr>
		</table>
		<br /><br />
<center><a href="#myModalques" role="button" class="btn btn-primary btn-large btn-success" data-toggle="modal"><i class="icon-white icon-plus" ></i> Choose from library</a></center><br /><br />

<div class="text-left" ><form action="fac_ques.php" method="post"><button type="submit" class="btn btn-primary text-right" name="goback" value="goback" ><i class="icon-white icon-chevron-left" ></i> Go Back</button></form></div>
 
<!-- Modal -->
<div id="myModalques" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:95%;margin-left:-650px;" >
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Questions Library</h3>
</div>
<div class="modal-body">
<form action="fac_ques.php" method="post">
<table>
<?php

$select=mysql_query("select * from $masterDB");
echo '<table align="center" class="table table-striped table-hover table-bordered" >';
?>
			<tr class="qns" >
			    <th><i class="icon-ok"></i></th>
				<th>Q.no</th>
				<th>Question</th>
				<th>Options</th>
				<th>Ans</th>	
			</tr>
<?php

   $counter=1;
while($ques_bank=mysql_fetch_array($select))
 {
  //echo $_SESSION['ques_bank_id'];
 	
 	$presence = mysql_fetch_array(mysql_query("select * from $_SESSION[ques_bank_id] where question_no=$ques_bank[que_no] "));
 	//echo $presence['question_no'];
  if(!isset($presence['question_no']))
   {
   		//echo "here";
	   

?>
<!-- <a href="#myModal_test" role="button" data-toggle="modal" >More Info</a> -->

<!-- Modal -->
<div id="myModal_test" class="modal hide fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Test Info</h3>
</div>
<div class="modal-body">
<?php //display_test_info($ques_bank[test_id]); ?>
</div>

<div class="modal-footer">
<a href="#myModal_test" class="btn btn-primary" class="close" data-toggle="modal" >Ok</a>
<a href="update_exam.php?test=<?php echo $_SESSION['tid']; ?>&update_exam=yes" class="btn btn-warning" ><i class="icon-white icon-pencil"></i> Update</a>
</div>
</div>

<?php   
   //echo '</td></tr>';
   
   echo '<tr><td><input type="radio" name="quesno" value="'.$ques_bank['que_no'].'" class="input-large" /></td>';
?>
                  <td><?php echo $counter++; ?></td>
					<td><?php echo $ques_bank['question'] ?></td>
					<td><?php
                    echo "<b>A:</b> ".$ques_bank['option1'];
					echo "<br /><b>B:</b> ".$ques_bank['option2'];
					echo "<br /><b>C:</b> ".$ques_bank['option2'];
					echo "<br /><b>D:</b> ".$ques_bank['option3'];
					?></td>
					<td><?php echo $ques_bank['answer'] ?></td>
				    
					
<?php
	   echo '</tr>';
	 }
  }

?>
</table>

</div>
<div class="modal-footer">

<h4><font color="red">Marks : </font><input type="number" class="input" style="width:30px;" name="marks" min="1" value="3" required/>
<font color="red">Negative Marks : </font><input type="number" class="input" style="width:30px;" name="neg_marks" max="0" value="0" required/></h4>
<button class="btn" data-dismiss="modal" aria-hidden="true" >Close</button>
<button class="btn btn-primary" type="submit" name="qadd" value="add" ><i class="icon-white icon-plus" ></i> Add</button>
</form>
</div>
</div>
		
		<br /><br />
			<?php
			//$query_t= mysql_query("INSERT INTO question_bank VALUES (0,$q_b_id,' ',0,' ',' ',' ',' ')") or die("Died2".mysql_error());
			//echo "Created new QB for $c $t";
 }
}

/////////////////	This selects the respictive number of respective exam which is used to compare in used block 	////////////////////////////

function find_number($no)
{
	//echo "here".$no;
	if($no="q1")
		return 1;
	else if($no="q2")
		return 2;
	else if($no="midsem")
		return 3;
	else if($no="endsem")
		return 4;
}

//////////////////	 This displays the information about the selected exam such as date, examtype, duration and max marks. 	///////////////

function display_test_info($selected_exam)
{
	//echo $selected_exam;

	$test=explode("_", $selected_exam);
	//echo $test[1];
  echo '<table class="table table-bordered table-hover table-striped" >';
   echo '<tr><td><b>Course:</b></td><td>'.$_SESSION['course'].'</td></tr>';
   echo '<tr><td><b>Exam: </b></td><td>';
   if($test['1']=="1")
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($test['1']=="2")
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($test['1']=="3")
    echo '<span class="text-success" > Mid sem </span>';
   else if($test['1']=="4")
    echo '<span class="text-success" > End sem </span>';
   echo '</td></tr>';
   	  //$examno=find_number($test[1]);
   	  $data=mysql_fetch_array(mysql_query("select * from test where course_id='$_SESSION[course]' and examtype=$test[1] and date='$test[2]' "));
   	  //echo $_SESSION['date'].$data['duration']."here"."select * from test where course_id='$_SESSION[course]' and examtype='$examno' and date='$test[2]' ";
	  echo '<tr><td><b>Duration: </b></td><td>'.$data['duration'].'</td></tr>';
	  echo '<tr><td><b>Date of Exam: </b></td><td>'.$data['date'].'</td></tr>';
	  echo '<tr><td><b>Maximum Marks: </b></td><td>'.$data['max_marks'].'</td></tr>';
	  echo '</table>';
}

//////////////	This function stores the exam into the answer table for that question 	//////////////////

function update_answers($post,$ansTable,$ansno)
 {
    $options=$post;
	   if($options)
	   {
	   foreach($options as $key =>$value)
	    {
		 //echo '<br /> '.$value;
		 if($ops=="")
          $ops=$value;
		 else
		  $ops.="|".$value;
		}
		 if(mysql_num_rows(mysql_query("select * from $ansTable where rollnumber='$_SESSION[rollnumber]' ")))
		  {
		    mysql_query("update $ansTable set $ansno= '$ops' where rollnumber='$_SESSION[rollnumber]'");
		  }
		 else
		  {
		    mysql_query("insert into $ansTable set $ansno= '$ops', rollnumber='$_SESSION[rollnumber]' ");
		  }
		}
	   else
	    {
		  mysql_query("update $ansTable set $ansno = '' where rollnumber='$_SESSION[rollnumber]'");
		}
 }
 

function questions_header()
 {
	  echo '<br /><div class="row-fluid" >';
	  echo '<div class="span4 text-left" ><b>Course Id:</b> '.$_SESSION['course'].'</div>';
	  //echo $_SESSION['course']; ------> correct
	  echo '<div class="span4 text-center lead" ><big>Questions</big></div>';
	  echo '<div class="span4 text-right" >';	   	$test=explode("_",$_SESSION['selected_exam']);
	   echo '<b>Test:</b>';
	   if($test[1]=="q1")
	    echo '<span class="text-success" > Quiz 1 </span>';
	   else if($test[1]=="q2")
	    echo '<span class="text-success" > Quiz 2 </span>';
	   else if($test[1]=="midsem")
	    echo '<span class="text-success" > Mid sem </span>';
	   else if($test[1]=="endsem")
	    echo '<span class="text-success" > End sem </span>';

?> 
<!-- Shows the menu related to updating the exam-->
<div id="myModal" class="modal hide fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Test Info</h3>
</div>
<div class="modal-body">
<?php	
	//display_test_info($_SESSION['selected_exam']); 
?>
</div>
<div class="modal-footer">
<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button>
<a href="update_exam.php?test=<?php echo $_SESSION['tid']; ?>update_exam=yes" class="btn btn-warning" ><i class="icon-white icon-pencil"></i> Update</a>
</div>
</div>
<?php
	 
	  echo '</div>';
	  echo '</div>';
 }
 
///****************************************************
///Shows how much time is remaining for the User
///*************************************************///
function duration()
{	$time=0;
	  if(!isset($_SESSION['start']))
	   {
        $st=mysql_fetch_array(mysql_query("select duration from test where course_id='$_SESSION[course]'"));
        $st_conv=explode(":", $st[0]);
        $totalsec=$st_conv[0]*3600+$st_conv[1]*60;
        $_SESSION['totalsec']=$totalsec;
		 $examstatus=$_SESSION['examtype'].'_'.'taken';
      		$val=mysql_fetch_array(mysql_query("select $examstatus from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
		 if($val[0]!=0 && $val[0]!='1')
		 {
          $_SESSION['prev_duration']=$val[0];
		 }	 


	    $_SESSION['start']=time(NULL);
		$_SESSION['hrs']=0;
		$_SESSION['mins']=0;
		$_SESSION['secs']=0;
       }
	  $_SESSION['duration']=time(NULL) - $_SESSION['start'] +$_SESSION['prev_duration'];

	  if($_SESSION['duration'])
	   {
		
        $_SESSION['secs']=$_SESSION['duration']%60;
		if($_SESSION['duration']>3600)
		 {
		  $temp=$_SESSION['duration']-(3600*(floor($_SESSION['duration']/3600)));
		  $_SESSION['mins']=floor($temp/60);
		 }
		else
		 {
		  $_SESSION['mins']=floor($_SESSION['duration']/60);
		 }
		
		 $_SESSION['hrs']=floor($_SESSION['duration']/3600);

		
		
		echo '<span class="lead"> Time Spent: ';
		if($_SESSION['hrs']<10)
		 echo '0';
		echo $_SESSION['hrs'];
		echo ':';
		if($_SESSION['mins']<10)
		 echo '0';
		echo $_SESSION['mins'];
		echo ':';
	    if($_SESSION['secs']<10)
		 echo '0';
		echo $_SESSION['secs'];
		echo '</span>';
	   }
	  else
	   {
	    echo '<span class="lead" >Time Spent: 00:00:00</span>';
	   }
}

//Function to verify the remaining time for the User for finishing the exam.It also updates how much time is remaining. Peforms database action
function update_duration()
{
	$examtype=$_SESSION['examtype'].'_'.'taken';
if(!isset($_SESSION[totalsec]))
		{
			$st=mysql_fetch_array(mysql_query("select duration from test where course_id='$_SESSION[course]'"));
        //var_dump($st);
        	$st_conv=explode(":", $st[0]);
       	    $totalsec=$st_conv[0]*3600+$st_conv[1]*60;
        	$_SESSION['totalsec']=$totalsec;        
      		$val=mysql_fetch_array(mysql_query("select $examtype from student_exam_status where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' "));
	
		}
	else{
		if($_SESSION[duration]<=$_SESSION[totalsec])
	  		mysql_query("update student_exam_status set $examtype='$_SESSION[duration]' where rollnumber='$_SESSION[rollnumber]' and course_id='$_SESSION[course]' ");
		else
			{
				echo '<script>window.alert("Your Exam  is Over.")</script>';
				 mysql_query("UPDATE login set loggedin='0' AND lastlogin='$dtformatted' WHERE rollnumber='$_SESSION[rollnumber]'");
				session_destroy();
				echo '<script>window.location="index.php";</script>';
			}	
		}	
}
//function limits the access of portal for a particular range of ip address.
function verifyip()
{	

$location = 'index.php'; 
$range_low = ip2long("172.16.0.0");
$range_high = ip2long("192.100.100.200");

$ip = ip2long($_SERVER['REMOTE_ADDR']);
if ($ip >= $range_low && $ip <= $range_high) 
	{
	return '0';
	}
else {
	return '1';
// do something else or nothing at all
//echo '<script>window.location="index.com";</script>';
}
}

?>