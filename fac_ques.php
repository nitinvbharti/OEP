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

if(isset($_SESSION['faculty_id']) && isset($_SESSION['course']))
 {
  if(isset($_POST['delete']) || isset($_POST['edit']) || isset($_POST['add']) || isset($_POST['change']))
   {
   	$max=mysql_fetch_array(mysql_query("select max(ques_id) as max from question_bank where ques_bank_id='$_SESSION[ques_bank_id]' "));
    $qno=$max['max']+1;
	if(isset($_POST['add']))
	{
		$m=mysql_fetch_array(mysql_query("select max_qns as m from tests where test_id='$_SESSION[tid]' "));
        if($qno>$m['m'])
        {
        	echo "max questions limit reached";
        }
        else
        {
	    //echo "hello";
		//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
		$op = $_POST['op1']."|".$_POST['op2']."|".$_POST['op3']."|".$_POST['op4'];
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
	    if($_POST['qtype']=="Single")
		 $type=0;
		else
		 $type=1;
		 
		 
		$no_repeat=mysql_num_rows(mysql_query("select ques_id from question_bank where ques_bank_id='$_SESSION[ques_bank_id]' and ques='$_POST[ques]' and ans='$_POST[ans]' and marks='$_POST[marks]' and options='$op' and qtype='$type' and if_image='$extension' and neg_marks='$_POST[neg_marks]' "));
		
	    if(!$no_repeat)
		 {
		  //echo "hi";
		$query_t= mysql_query("INSERT INTO question_bank VALUES ('$qno','$_SESSION[ques_bank_id]','$_POST[ques]','$_POST[ans]','$_POST[marks]', '$op', '$type','$extension', '$_POST[neg_marks]' )") or die("Died2".mysql_error());
		if($file_success)
		 {
			if(file_exists($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension))
			 unlink($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension);
            move_uploaded_file($_FILES["file"]["tmp_name"],"img/qns/".$_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$extension);
		 }
        echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Question no:'.$qno.' added!</div>';
		$_SESSION['ques_id']=$qno;
		 }
		}
		//echo '<script>window.location="fac_ques.php";</script>';
	}
	else if(isset($_POST['delete']))
	{
	    //echo 'hi';
		//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
		$qns=mysql_fetch_array(mysql_query("select if_image from question_bank where ques_id='$qno' "));
	    if(file_exists($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$qns['if_image']))
		  unlink($_SESSION["course"]."_".$_SESSION['ques_bank_id']."_".$_SESSION['tid']."_".$qno.".".$qns['if_image']);		
		$query_t= mysql_query("DELETE FROM question_bank WHERE ques_id = '$_POST[qno]' and ques_bank_id='$_SESSION[ques_bank_id]' ") or die("Died2".mysql_error());
        echo '<div class="alert fade in alert-success" ><button type="button" class="close" data-dismiss="alert" >&times;</button><strong>Success!!! </strong>Question no:'.$_POST[qno].' deleted!</div>';
		//echo '<script>window.location="fac_ques.php";</script>';
	}
	else if(isset($_POST['edit']) || isset($_POST['change']))
	{
		if(isset($_POST['edit']))
		{
		   questions_header();
			//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
			$query_ed= mysql_query("SELECT * FROM question_bank WHERE ques_id = '$_POST[serial]' and ques_bank_id='$_SESSION[ques_bank_id]' ") or die("Died2".mysql_error());
			$ques = mysql_fetch_array($query_ed);
			$options = $ques['options'];
			$opts = explode("|",$options);
			$test=mysql_fetch_array(mysql_query("select * from tests where test_id='$_SESSION[tid]' "));
			
			?>
			<form method="POST" action="fac_ques.php" enctype="multipart/form-data" >
			<table class="table table-hover table-striped table-bordered" >
			<tr class="qns" >
				<th>Q.No</th>
				<th>Question</th>
				<th>Options</th>
				<th>Answer</th>
				<?php
                 if(!$test || !$test['equal_weight'])		
                  echo '<th>Marks</th>';
                 if(!$test || $test['neg_marking'])
                  echo '<th>-ve Marks</th>';				 
				?>
				
				<th>Q-Type</th>
				<th>Image</th>
				<th>Operation</th>
			</tr>
				<tr>
					<td><?php echo $ques['ques_id'];?></td>
					<td ><textarea   rows="1" name="ques" style="min-width:300px;min-height:140px;" required><?php echo $ques['ques'];?></textarea></td>			
						<td>
                         <input type="text" class="input-small" name="op1" value="<?php echo $opts[0];?>" required/><br /><input  class="input-small" type="text" name="op2" value="<?php echo $opts[1];?>" required/><br /><input  class="input-small" type="text" name="op3" value="<?php echo $opts[2];?>" required/><br /><input  class="input-small" type="text" name="op4" value="<?php echo $opts[3];?>" required/>
						 </td>
					<td><input type="text" class="input-small"  maxlength="7" style="width:60px;" name="ans" placeholder="(Ex:A|B)" value="<?php echo $ques['ans'];?>" required/></td>
					<?php
					 if(!$test || !$test['equal_weight'])
					  {
					 ?>
					  <td><input type="number" name="marks"  class="input" style="width:30px;" value="<?php echo $ques['marks'];?>" required/></td>
					<?php 
					  } 
					?>
					<?php
					 if(!$test || $test['neg_marking'])
					  {
					 ?>
					  <td><input type="number" name="neg_marks"  class="input" style="width:30px;" value="<?php echo $ques['neg_marks'];?>" required/></td>
					<?php 
					  }
					?>
					<td><select name="qtype" class="input-small" ><option value="Single" <?php if($ques['qtype']==0) echo "selected"; ?> >Single</option><option value="Multiple"  <?php if($ques['qtype']==1) echo "selected"; ?> >Multiple</option></select></td>
					<td>
					
					<?php if($ques['if_image']) echo '<a href="#myModal'.$ques['ques_id'].'" role="button" class="btn btn-primary" data-toggle="modal"  ><i class="icon-white icon-eye-open" ></i> View</a>
                   <div id="myModal'.$ques['ques_id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                   <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                   <h3 id="myModalLabel">Image for Question: '.$ques['ques_id'].'</h3>
                   </div>
                   <div class="modal-body">
                   <a href="#" class="thumbnail" ><img src="img/qns/'.$_SESSION['course'].'_'.$_SESSION['ques_bank_id']."_".$_SESSION['tid'].'_'.$ques['ques_id'].'.'.$ques['if_image'].'" style="width:50%;height:50%;" /></a>
                   </div>
                   <div class="modal-footer">
                   <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                   </div>
                   </div>
					';
				   else 
				     echo "no"; ?>
				  <br /><br />
				  <input type="file" class="input-small" name="file" style="width:200px;" ><br /><span class="help-inline">(leave un-uploaded to remove the image)</span></td>
					<td><button type="submit" class="btn btn-warning btn-large" name="change" value="Update" ><i class="icon-white icon-ok" ></i> Update</button><input type="hidden" value="<?php echo $ques['ques_id']; ?>" name="serial" />
					</tr>
			</table>
			</form>
			<div class="row-fluid text-center">
			<a href="fac_ques.php" class="btn btn-primary btn-large" ><i class="icon-white icon-chevron-left" ></i> Go Back</a>
			</div>
		<?php	
		}
	else
	{
	    $qno=$_POST['serial'];
//$t_id = get_test_id($_SESSION['course_id'],$_SESSION['test']);
		$op = $_POST['op1']."|".$_POST['op2']."|".$_POST['op3']."|".$_POST['op4'];
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
	    if($_POST['qtype']=="Single")
		 $type=0;
		else
		 $type=1;
		 
		$query_t= mysql_query("update question_bank set ques='$_POST[ques]', ans='$_POST[ans]', marks='$_POST[marks]', options='$op', qtype='$type', if_image='$extension', neg_marks='$_POST[neg_marks]'  where ques_id='$qno'  and ques_bank_id='$_SESSION[ques_bank_id]' ") or die("Died2".mysql_error());
		
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
	  fetch_questions($_SESSION['course'],$_SESSION['tid']);
	 }
 }//end of if
else
 {
    echo '<script>window.location="index.php";</script>';
 }


include("footer.php");
?>