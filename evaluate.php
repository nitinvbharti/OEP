<?php
session_start();
if($_SESSION['tab']!=5)
{
 
 $_SESSION['tab']=5;
 //echo '<meta http-equiv="refresh" content="0;faculty.php" >';
}
require("header.php");



if($_SESSION['faculty_id'])
{
if(isset($_SESSION['course']))
 {

 	if(isset($_POST['1']) || isset($_POST['2']) || isset($_POST['3']) || isset($_POST['4']) || isset($_POST['5']))
    {
       if(isset($_POST['1']))
	   {
		 $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='1' "));
<<<<<<< HEAD
     if($_SESSION['course'])
     {
=======
    
>>>>>>> origin/master
          if($check==0)
          {
          	echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."q1";
            $c2=$_SESSION['course']."_"."q1"."_julynov_2015";
            $c3="ans"."_".$_SESSION['course']."_"."q1";


          	 $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

          	//$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no ");

            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            //echo $store[$i];
            $i++;
            }
            $j=0;
            $marks=mysql_query("select marks,neg_marking from $c2" );
            while($total=mysql_fetch_array($marks))
            {
              $final[$j]=$total['marks'];
              $neg[$j]=$total['neg_marking'];
              $j++;
            }



            
            $rollno=mysql_query("select * from $c3");
            while($check=mysql_fetch_array($rollno))
            {
             
              $roll=$check[0];
          
            $k=1;
            while($k!=$cols+1)
            {
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
            }
            $l=$check[$cols+1];
             //echo $l;
              mysql_query("update ans_com302_q1 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
            }

    }
      
 


if(isset($_POST['2']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='2' "));
    
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."q2";
            $c2=$_SESSION['course']."_"."q2"."_julynov_2015";
            $c3="ans"."_".$_SESSION['course']."_"."q2";


             $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

            //$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no ");

            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            //echo $store[$i];
            $i++;
            }
            $j=0;
            $marks=mysql_query("select marks,neg_marking from $c2" );
            while($total=mysql_fetch_array($marks))
            {
              $final[$j]=$total['marks'];
              $neg[$j]=$total['neg_marking'];
              $j++;
            }



            
            $rollno=mysql_query("select * from $c3");
            while($check=mysql_fetch_array($rollno))
            {
             
              $roll=$check[0];
          
            $k=1;
            while($k!=$cols+1)
            {
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
            }
            $l=$check[$cols+1];
             //echo $l;
              mysql_query("update ans_com302_q1 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
            }

    }
      
 


if(isset($_POST['3']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='3' "));
    
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."midsem";
            $c2=$_SESSION['course']."_"."midsem"."_julynov_2015";
            $c3="ans"."_".$_SESSION['course']."_"."midsem";


             $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

            //$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no ");

            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            //echo $store[$i];
            $i++;
            }
            $j=0;
            $marks=mysql_query("select marks,neg_marking from $c2" );
            while($total=mysql_fetch_array($marks))
            {
              $final[$j]=$total['marks'];
              $neg[$j]=$total['neg_marking'];
              $j++;
            }



            
            $rollno=mysql_query("select * from $c3");
            while($check=mysql_fetch_array($rollno))
            {
             
              $roll=$check[0];
          
            $k=1;
            while($k!=$cols+1)
            {
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
            }
            $l=$check[$cols+1];
             //echo $l;
              mysql_query("update ans_com302_q1 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
            }

    }
      
 


if(isset($_POST['4']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='4' "));
    
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."endsem";
            $c2=$_SESSION['course']."_"."endsem"."_julynov_2015";
            $c3="ans"."_".$_SESSION['course']."_"."endsem";


             $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

            //$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no ");

            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            //echo $store[$i];
            $i++;
            }
            $j=0;
            $marks=mysql_query("select marks,neg_marking from $c2" );
            while($total=mysql_fetch_array($marks))
            {
              $final[$j]=$total['marks'];
              $neg[$j]=$total['neg_marking'];
              $j++;
            }



            
            $rollno=mysql_query("select * from $c3");
            while($check=mysql_fetch_array($rollno))
            {
             
              $roll=$check[0];
          
            $k=1;
            while($k!=$cols+1)
            {
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
            }
            $l=$check[$cols+1];
             //echo $l;
              mysql_query("update ans_com302_q1 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
            }

    }
      
 


if(isset($_POST['5']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='5' "));
    
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."viva";
            $c2=$_SESSION['course']."_"."viva"."_julynov_2015";
            $c3="ans"."_".$_SESSION['course']."_"."viva";


             $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

            //$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no ");

            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            //echo $store[$i];
            $i++;
            }
            $j=0;
            $marks=mysql_query("select marks,neg_marking from $c2" );
            while($total=mysql_fetch_array($marks))
            {
              $final[$j]=$total['marks'];
              $neg[$j]=$total['neg_marking'];
              $j++;
            }



            
            $rollno=mysql_query("select * from $c3");
            while($check=mysql_fetch_array($rollno))
            {
             
              $roll=$check[0];
          
            $k=1;
            while($k!=$cols+1)
            {
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
            }
            $l=$check[$cols+1];
             //echo $l;
              mysql_query("update ans_com302_q1 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
            }

    }
      
 }



}
}





  
 
//////////////////////////////////////////////////////////////////////////////////////////////////
?>
<form method="POST" action="evaluate.php">
		<br>
		<br>
		<table align="center" cellpadding="15" >
		<tr>
			<td><input class="btn btn-primary btn-large" name="1"  type="submit"  value="Quiz 1"></td>
			<td><input class="btn btn-primary btn-large" name="2"  type="submit"  value="quiz 2"></td>
			<td><input class="btn btn-primary btn-large" name="3"  type="submit" value="Midsem" /></td>
			<td><input class="btn btn-primary btn-large" name="4"  type="submit" value="Endsem"></td>
			<td><input class="btn btn-primary btn-large" name="5"  type="submit" value="Viva"></td>
		</tr>
		</table>
	</form>


<?php 
require("footer.php");
?>