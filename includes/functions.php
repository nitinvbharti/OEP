<?php
include("connect.php");

error_reporting(E_ERROR | E_PARSE);

function validateTime($rawtime,$seperator2=':')
{
	$ntime=explode($seperator2,$rawtime);
	//echo $ntime[0].$ntime[1];
	if($ntime[0]>=0 && $ntime[0]<=23)
	{
		return true;
	}
	if($ntime[1]>=0 && $ntime[1]<=59)
	{
		return true;
	}
	return false;
}
function validateDate($rawDate,$seperator='-')
    {
		//echo $rawdate;
		
        $ndate=explode($seperator,$rawDate);
        //var_dump($ndate);
		$cdate=date("Y-m-d");
		//echo $cdate
		$expldate=explode($seperator,$cdate);
        if($ndate[0]!=$expldate[0] || $ndate[1]<$expldate[1] || ($ndate[1]==$expldate[1] && $ndate[2]<$expldate[2]))
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

	//find the relevent personal data based on id////////
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
///////////////lists which faculty will take what course/////////////
function list_all_courses($fac_id)
{
	//global $con;
	$course_set = mysql_query("SELECT * FROM slot_wise_courses WHERE faculty_id='$_SESSION[faculty_id]'") or die(mysql_error());
	return $course_set;
}

function get_test_id($c,$t)
{
	global $con;
	$query = mysql_query("SELECT * FROM tests WHERE course_id='$c' AND exam='$t' ") or die("Died3".mysql_error());
	$count = mysql_num_rows($query);
	if($count==0)
	{
		return -1;
	}
	else
	{
		$tt = mysql_fetch_array($query);
		return $tt['test_id'];
	}
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
/////////////////////////////////fetch questions based on test id///////////
function fetch_questions($c,$t)
{
 if($_POST['goback'])
  {
   unset($_SESSION['init']);
   unset($_SESSION['tid']);
  }
 if(!isset($_SESSION['tid']) && !isset($_SESSION['init']))
  {
   if(!isset($_POST['test_id']))
    {
   $select=mysql_query("select test_id from ques_bank_no where course_id='$_SESSION[course]' and test_id!='' ");
   $count=mysql_num_rows($select);
   if($count)
    {
      echo '<div class="text-left row-fluid" ><a href="faculty.php" class="btn btn-primary text-right" ><i class="icon-white icon-chevron-left" ></i> Go Back</a></div>';
      echo '<div class="row_fluid text-center" ><br /><br /><p class="lead">You have following tests in <big class="text-info" >'.$_SESSION['course'].'</big> for which questions have to be set</p><form action="fac_ques.php" method="post"><select name="test" >';
   while($row=mysql_fetch_array($select))
     {
	   $test=mysql_fetch_array(mysql_query("select type,date from tests where test_id='$row[test_id]' "));
	   if(validateDate($test['date']))
	   {
	   	echo '<option value="'.$row['test_id'].'" >';
   		if($test['type']==1)
    	echo 'Quiz 1';
   		else if($test['type']==2)
    	echo 'Quiz 2';
   		else if($test['type']==3)
    	echo 'Mid sem';
   		else if($test['type']==4)
    	echo 'Viva';
   		else if($test['type']==5)
    	echo 'End sem';
   		echo ' on '.$test['date'].'</option>';
	   }
	 }
   echo '</select><br /><button type="submit" class="btn btn-primary" value="test_id" name="test_id" >Go <i class="icon-white icon-chevron-right"></i></button></form>';
   
   echo '<br /><br />(or)<br /><br /><form action="fac_ques.php" method="post"><button name="test_id" value="-1" class="btn btn-large btn-primary" ><i class="icon-briefcase icon-white" ></i>  Enter Questions Pool</button></form>';
   
   echo '</div>';
    }
    else
	 {
	   $_SESSION['init']=1;
	 }
   }
  else
   {
    if($_POST['test_id']!=-1)
	 {
       $_SESSION['tid']=$_POST['test'];
	   //echo 'hi';
	 }
	$_SESSION['init']=1;
   }
  }
 else
  {
   $_SESSION['init']=1;
  }
if(isset($_SESSION['init']))
 {

	questions_header();
	  
	if(isset($_SESSION['tid']))
	 {
		//$t_id = get_test_id($c,$t);
		if(!isset($_SESSION['ques_bank_id']))
		 {
		   $rows=mysql_fetch_array(mysql_query("select ques_bank_id from ques_bank_no where test_id='$_SESSION[tid]' "));
		   if(!$rows)
		    {
		     mysql_query("insert into ques_bank_no set test_id='$_SESSION[tid]', course_id='$_SESSION[course]' ");
		    }
		   $row=mysql_fetch_array(mysql_query("select ques_bank_id from ques_bank_no where course_id='$_SESSION[course]' and test_id='$_SESSION[tid]' "));
		   $_SESSION['ques_bank_id']=$row['ques_bank_id'];
		 }
	 }
	else
	 {
		if(!isset($_SESSION['ques_bank_id']))
		 {
		   $rows=mysql_fetch_array(mysql_query("select ques_bank_id from ques_bank_no where course_id='$_SESSION[course]' and test_id='' "));
		   if(!$rows)
		    {
		     mysql_query("insert into ques_bank_no set course_id='$_SESSION[course]' ");
			 $row=mysql_fetch_array(mysql_query("select ques_bank_id from ques_bank_no where course_id='$_SESSION[course]' and test_id='' "));
			 $_SESSION['ques_bank_id']=$row['ques_bank_id'];
			}
		   else
		    {
		     $_SESSION['ques_bank_id']=$rows['ques_bank_id'];
			}
		 }
	 }
//echo $_SESSION['ques_bank_id'];
if($_POST['qadd'])
 {
  $qns=$_POST['ques'];
  foreach($qns as $qn =>$id)
  {
   $values=explode("_",$id);
   $question=mysql_fetch_array(mysql_query("select max(ques_id) as max from question_bank where ques_bank_id='$_SESSION[ques_bank_id]' "));
   $qno=$question['max']+1;
   //echo "New qno: ".$qno;
   //echo $values[0].$values[1];
   //echo $_SESSION[ques_bank_id];
   $m=mysql_fetch_array(mysql_query("select max_qns as m from tests where test_id='$_SESSION[tid]' "));
	if($qno>$m['m'])
	{
	echo '<div class="alert fade in alert-failed" ><button type="button" class="close" data-dismiss="alert" >&times;</button>Max Question limit reached</div>';
	}
	else
	{
   $test=mysql_fetch_array(mysql_query("select test_id from ques_bank_no where ques_bank_id='$values[0]' "));
   
   $qn_old=mysql_fetch_array(mysql_query("select if_image from question_bank where ques_bank_id='$values[0]' and ques_id='$values[1]' "));
   
   $ext=$qn_old['if_image'];
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
   $result=mysql_query("update question_bank set ques_bank_id='$_SESSION[ques_bank_id]' , ques_id='$qno' where   ques_bank_id='$values[0]' and ques_id='$values[1]' ") or die(mysql_error());
  }
  }
 }
 

        //echo "q:".$_SESSION['ques_bank_id'];
		$qb_set = mysql_query("SELECT * FROM question_bank WHERE ques_bank_id='$_SESSION[ques_bank_id]' ");
		$test=mysql_fetch_array(mysql_query("select * from tests where test_id='$_SESSION[tid]' "));
		?>
        <div class="text-left" ><form action="fac_ques.php" method="post"><button type="submit" class="btn btn-primary text-right" name="goback" value="goback" ><i class="icon-white icon-chevron-left" ></i> Go Back</button></form></div><br />
		<table align="center" class="table table-striped table-hover table-bordered" >
			<tr class="qns" >
				<th>#</th>
				<th style="width:300px;" >Question</th>
				<th>Options</th>
				<th>Ans</th>
				<?php
                 if(!$test || !$test['equal_weight'])		
                  echo '<th>Marks</th>';
                 if(!$test || $test['neg_marking'])
                  echo '<th>-ve Marks</th>';				 
				?>
				
				<th>Q Type</th>
				<th>Image</th>
				<th>Operation</th>
			</tr>
		<?php
			if(mysql_num_rows($qb_set)>0)
			{
			    $counter=1;
				while($ques = mysql_fetch_array($qb_set))
				{
		?>  
				<tr class="qns" >
				<form action="fac_ques.php" method="post">
					<td><?php echo $counter++; ?></td>
					<td><?php echo $ques['ques'] ?></td>
					<td><?php $option=explode("|",$ques['options']);
                    echo "<b>A:</b> ".$option[0];
					echo "<br /><b>B:</b> ".$option[1];
					echo "<br /><b>C:</b> ".$option[2];
					echo "<br /><b>D:</b> ".$option[3];
					?></td>
					<td><?php echo $ques['ans'] ?></td>
					<?php  
					    if(!$test || !$test['equal_weight'])	
                          {
						   echo '<td>';
						   echo $ques['marks'];  
						   echo '</td>'; 
                          }						  
					   ?>
					<?php 
					if(!$test || $test['neg_marking'])
                     {
					   echo '<td>'; 
					   echo $ques['neg_marks']; 
					   echo '</td>';
                     }
                       ?>
				    <td><?php if($ques['qtype']==0) echo "Single"; else echo "Multiple";?></td>
					<td  style="text-align:center;" ><?php if($ques['if_image']) echo '<a href="#myModal'.$ques['ques_id'].'" role="button" class="btn btn-primary" data-toggle="modal"  ><i class="icon-white icon-eye-open" ></i> View</a>
                   <div id="myModal'.$ques['ques_id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                   <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                   <h3 id="myModalLabel">Image for Question: '.$ques['ques_id'].'</h3>
                   </div>
                   <div class="modal-body">
                   <a href="#" class="thumbnail" ><img src="img/qns/'.$_SESSION['course'].'_'.$_SESSION['ques_bank_id']."_".$_SESSION['tid'].'_'.$ques['ques_id'].'.'.$ques['if_image'].'"  style="width:50%;height:50%;" /></a>
                   </div>
                   <div class="modal-footer">
                   <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                   </div>
                   </div>
					'; else echo "no"; ?></td>
					<td colspan="2" ><button name="edit" class="btn btn-warning" value="Edit" type="submit" ><i class="icon-white icon-pencil" ></i> Edit</button><input type="hidden" value="<?php echo $ques['ques_id']; ?>" name="serial" /> &nbsp;&nbsp;&nbsp;<a href="#myModaldel<?php echo $ques['ques_id']; ?>" role="button" class="btn btn-danger" data-toggle="modal"  ><i class="icon-white icon-remove" ></i> Delete</a>
                   <div id="myModaldel<?php echo $ques['ques_id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                   <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h3 id="myModalLabel">Confirmation</h3>
                   </div>
                   <div class="modal-body">
                   <center><h4>Are you sure?</h4></center>
                   </div>
                   <div class="modal-footer">
                   <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">No</button>
				   <input type="hidden" name="qno" value="<?php echo $ques['ques_id']; ?>" />
				   <input type="submit" name="delete" value="Yes" class="btn btn-danger" />
                   </div>
                   </div></td></form></tr>
		<?php
				}
			}
			$max_array = mysql_fetch_array(mysql_query("SELECT MAX(ques_id) AS mm FROM question_bank WHERE ques_bank_id='$_SESSION[ques_bank_id]' ")) or die("Died".mysql_error());
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
				   if(!$test || !$test['equal_weight'])
				    {
					 echo '<td style="text-align:center;" ><input type="number" class="input" style="width:30px;" name="marks" required/></td>';
					}
				   if(!$test || $test['neg_marking'])
				    {
					 echo '<td  style="text-align:center;" ><input type="number" class="input"  style="width:30px;" name="neg_marks" required/></td>';
					}
				?>
				<td style="text-align:center;" ><select name="qtype" class="input-small" ><option value="Single" >Single</option><option value="Multiple">Multiple</option></select></td>
				<td  style="text-align:center;" ><input type="file" class="input-small" name="file" style="width:80px;" ></td>
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
$select=mysql_query("select ques_bank_id, test_id from ques_bank_no");
echo '<table align="center" class="table table-striped table-hover table-bordered" >';
?>
			<tr class="qns" >
			    <th><i class="icon-ok"></i></th>
				<th>Q.no</th>
				<th>Question</th>
				<th>Options</th>
				<th>Ans</th>	
                <th>Marks</th>
                <th>-ve Marks</th>			 
				<th>Q Type</th>
				<th>Image</th>
			</tr>
<?php

while($ques_bank=mysql_fetch_array($select))
 {
  //echo $_SESSION['ques_bank_id'];
  if($ques_bank['ques_bank_id']!=$_SESSION['ques_bank_id'])
   {
   $select2=mysql_query("select * from question_bank where ques_bank_id='$ques_bank[ques_bank_id]' ");
   $qns_count=mysql_num_rows($select2);
   $select3=mysql_query("select type, date from tests where test_id='$ques_bank[test_id]' ");
   $test=mysql_fetch_array($select3);
   if($ques_bank[test_id] && $qns_count)
    {
   echo '<tr class="info" ><td colspan=5 style="text-align:left" ><span class="lead">Test: ';
   if($test['type']==1)
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($test['type']==2)
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($test['type']==3)
    echo '<span class="text-success" > Mid sem </span>';
   else if($test['type']==4)
    echo '<span class="text-success" > Viva </span>';
   else if($test['type']==5)
    echo '<span class="text-success" > End sem </span>';
   echo '</span>&nbsp;&nbsp;&nbsp;';

?>
<a href="#myModal_test" role="button" data-toggle="modal" >More Info</a>

<!-- Modal -->
<div id="myModal_test" class="modal hide fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Test Info</h3>
</div>
<div class="modal-body">
<?php display_test_info($ques_bank[test_id]); ?>
</div>

<div class="modal-footer">
<a href="#myModal_test" class="btn btn-primary" class="close" data-toggle="modal" >Ok</a>
<a href="update_exam.php?test=<?php echo $_SESSION['tid']; ?>&update_exam=yes" class="btn btn-warning" ><i class="icon-white icon-pencil"></i> Update</a>
</div>
</div>

<?php   
   echo '</td><td colspan=5 style="text-align:right" ><span class="lead">Scheduled on: '.$test['date'].'</span></td></tr>';
   }
  else if(!$ques_bank['test_id'])
   {
   // if(mysql_num_rows($select2))
     echo '<tr class="info" ><td colspan=10 style="text-align:left" ><span class="lead">Questions Pool</span></td></tr>';
   }
   
   $counter=1;
   while($ques=mysql_fetch_array($select2))
     {
	   $test=mysql_fetch_array(mysql_query("select test_id from ques_bank_no where ques_bank_id='$ques_bank[ques_bank_id]' "));
	   echo '<tr><td><input type="checkbox" name="ques[]" value="'.$ques_bank[ques_bank_id].'_'.$ques['ques_id'].'" class="input-large" /></td>';
?>
                  <td><?php echo $counter++; ?></td>
					<td><?php echo $ques['ques'] ?></td>
					<td><?php $option=explode("|",$ques['options']);
                    echo "<b>A:</b> ".$option[0];
					echo "<br /><b>B:</b> ".$option[1];
					echo "<br /><b>C:</b> ".$option[2];
					echo "<br /><b>D:</b> ".$option[3];
					?></td>
					<td><?php echo $ques['ans'] ?></td>
					<?php  echo '<td>'; if(!$test || !$test['equal_weight'])		
                  echo $ques['marks'];  echo '</td>';?>
					<?php echo '<td>'; if(!$test || $test['neg_marking'])		
                  echo $ques['neg_marks']; echo '</td>';?>
				    <td><?php if($ques['qtype']==0) echo "Single"; else echo "Multiple";?></td>
					<td  style="text-align:center;" ><?php if($ques['if_image']) echo '<a href="#myModal'.$ques['ques_id'].'" role="button" class="btn btn-primary" data-toggle="modal"  ><i class="icon-white icon-eye-open" ></i> View</a>
                   <div id="myModal'.$ques['ques_id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                   <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                   <h3 id="myModalLabel">Image for Question: '.$ques['ques_id'].'</h3>
                   </div>
                   <div class="modal-body">
                   <a href="#" class="thumbnail" ><img src="img/qns/'.$_SESSION['course'].'_'.$ques['ques_bank_id']."_".$test['test_id'].'_'.$ques['ques_id'].'.'.$ques['if_image'].'"  style="width:50%;height:50%;" /></a>
                   </div>
                   <div class="modal-footer">
				   <a href="#myModal'.$ques['ques_id'].'" role="button" class="btn btn-primary" data-toggle="modal"  area-hidden="true" >Close</a>
                   </div>
                   </div>
					'; 
					else echo "no"; ?></td>
					
<?php
	   echo '</tr>';
	 }
  }
 }
?>
</table>

</div>
<div class="modal-footer">
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



function display_test_info($course,$exam,$date)
{
$test=mysql_fetch_array(mysql_query("select * from test where course_id='$course' and examtype='$exam' and date='$date' "));
  echo '<table class="table table-bordered table-hover table-striped" >';
   echo '<tr><td><b>Course:</b></td><td>'.$_SESSION['course'].'</td></tr>';
   echo '<tr><td><b>Exam: </b></td><td>';
   if($test['examtype']==1)
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($test['examtype']==2)
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($test['examtype']==3)
    echo '<span class="text-success" > Mid sem </span>';
   else if($test['examtype']==4)
    echo '<span class="text-success" > Viva </span>';
   else if($test['examtype']==5)
    echo '<span class="text-success" > End sem </span>';
   echo '</td></tr>';
   
	  echo '<tr><td><b>Duration: </b></td><td>'.$test['duration'].'</td></tr>';
	  echo '<tr><td><b>Date of Exam: </b></td><td>'.$test['date'].'</td></tr>';
	  echo '<tr><td><b>Maximum Marks: </b></td><td>'.$test['max_marks'].'</td></tr>';
	  /*echo '<tr><td><b>Equal Weightage: </b></td><td>';
	  if($test['equal_weight']==1)
	   echo "Yes";
	  else
	   echo "No";
	  echo '</td></tr>';
	  echo '<tr><td><b>Maximum Questions: </b></td><td>'.$test['max_qns'].'</td></tr>';
	  echo '<tr><td><b>Negative Marking: </b></td><td>';
	  if($test['neg_marking']==1)
	   echo "Yes";
	  else
	   echo "No";
	  echo '</td></tr>';
	  echo '<tr><td><b>Number of Sets: </b></td><td>'.$test['sets'].'</td></tr>';
	  echo '<tr><td><b>Marks are displayed after the test: </b></td><td>';
	  if($test['display']==1)
	   echo "Yes";
	  else
	   echo "No";*/
	  echo '</table>';
}


function update_answers($post)
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
		 if(mysql_num_rows(mysql_query("select ans from answers where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' and ques_id='$_SESSION[cqn]'  ")))
		  {
		    mysql_query("update answers set ans = '$ops' where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' and ques_id='$_SESSION[cqn]'  ");
		  }
		 else
		  {
		    mysql_query("insert into answers set ans = '$ops', rollnumber='$_SESSION[rollnumber]', test_id='$_SESSION[tid]', ques_id='$_SESSION[cqn]' ");
		  }
		}
	   else
	    {
		  mysql_query("update answers set ans = '' where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' and ques_id='$_SESSION[cqn]'  ");
		}
 }
 
 
function questions_header()
 {
	  echo '<br /><div class="row-fluid" >';
	  echo '<div class="span4 text-left" ><b>Course Id:</b> '.$_SESSION['course'].'</div>';
	  echo '<div class="span4 text-center lead" ><big>Questions</big></div>';
	  echo '<div class="span4 text-right" >';
	  if($_SESSION['tid'])
	   {
	    $test=mysql_fetch_array(mysql_query("select type from tests where test_id='$_SESSION[tid]' "));
	   echo '<b>Test:</b>';
   if($test['type']==1)
    echo '<span class="text-success" > Quiz 1 </span>';
   else if($test['type']==2)
    echo '<span class="text-success" > Quiz 2 </span>';
   else if($test['type']==3)
    echo '<span class="text-success" > Mid sem </span>';
   else if($test['type']==4)
    echo '<span class="text-success" > Viva </span>';
   else if($test['type']==5)
    echo '<span class="text-success" > End sem </span>';
?>  
<br /><a href="#myModal" role="button" data-toggle="modal">More Info</a>
 
<!-- Modal -->
<div id="myModal" class="modal hide fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Test Info</h3>
</div>
<div class="modal-body">
<?php display_test_info($_SESSION['tid']); ?>
</div>
<div class="modal-footer">
<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button>
<a href="update_exam.php?test=<?php echo $_SESSION['tid']; ?>&update_exam=yes" class="btn btn-warning" ><i class="icon-white icon-pencil"></i> Update</a>
</div>
</div>
<?php
	 }
	else
	 {
	  echo '<span class="lead" >Questions Pool</span>';
	 }
	  echo '</div>';
	  echo '</div>';
 }
 


function duration()
{
	  if(!isset($_SESSION['start']))
	   {
        $st=mysql_fetch_array(mysql_query("select duration from exam_taken where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' "));
        if($st['duration'])
		 {
          $_SESSION['prev_duration']=$st['duration'];
		  //echo 'taken from prev duration';
		 }
		  
	    $_SESSION['start']=time(NULL);
		$_SESSION['hrs']=0;
		$_SESSION['mins']=0;
		$_SESSION['secs']=0;
       }
	   //$_SESSION['end']=time(NULL);

	  $_SESSION['duration']=time(NULL) - $_SESSION['start'] +$_SESSION['prev_duration'];
	  //echo $_SESSION['duration'];
	  if($_SESSION['duration'])
	   {
		/* 
		if($_SESSION['secs']==0 && $_SESSION['duration'])
		 {
		  $_SESSION['mins']=0;
		 }

		if($_SESSION['mins']%60==0 && $_SESSION['mins'])
		 {
		  $_SESSION['hrs']+=1;
		 }
		 */
		
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

function update_duration()
{
  mysql_query("update exam_taken set duration='$_SESSION[duration]' where rollnumber='$_SESSION[rollnumber]' and test_id='$_SESSION[tid]' ");
}
?>