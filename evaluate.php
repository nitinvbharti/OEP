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
		 $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='1' and evaluated='0' and step='4' "));

     $sem=mysql_fetch_array(mysql_query("select semester from test where course_id='$_SESSION[course]' and examtype='1' "));
       //echo $sem['semester'];
          if($check==0)
          {
          	echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."q1";
            if($sem['semester']==1)
            {
            $c2=$_SESSION['course']."_"."q1"."_julynov_2015";
            }
            else
            {
              $c2=$_SESSION['course']."_"."q1"."_janmay_2016";
            }
            $c3="ans"."_".$_SESSION['course']."_"."q1";

         //   echo $c2;
          	 $i=0;
            // $cols=mysql_num_rows(mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no "));
             //echo $cols;
            $cols=mysql_num_rows(mysql_query("select answer from $c1,$c2 where que_no=question_no "));

          	//$ans=mysql_query("select answer from com302_q1,com302_q1_julynov_2015 where que_no=question_no ");
                        $ans=mysql_query("select answer from $c1,$c2 where que_no=question_no order by $c1.que_no");
                        // echo "select answer from $c1,$c2 where que_no=question_no order by que_no";
            while($verify=mysql_fetch_array($ans))
            {
            $store[$i]=$verify['answer'];
            echo $store[$i]."</br>";
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
              echo $check[$k].$store[$k-1]."</br>";
              if($check[$k]==$store[$k-1])
              {
                $check[$cols+1]+=$final[$k-1];
              }
              else
              {
               $check[$cols+1]+=$neg[$k-1]; 
              }
              $k++;
              echo $check[$cols+1];
            }
            $l=$check[$cols+1];

            //echo $l;
            mysql_query("alter table $c3 add marks int(2) null ");

            mysql_query("update $c3 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
                          mysql_query("update test set evaluated='1' where course_id='$_SESSION[course]' and examtype='1'");
                             $s=$_SESSION['course'];
        //echo "<br><br><b><center>COURSE ID  = $s </center></b><br><br> ";
                echo "<br><br><b><center> QUIZ-1 MARKS </center></b><br><br> ";

        echo "<table align='center'>
       <tr>
       <th>   ROLL NO   </th>
       <th>   MARKS     </th>
       </tr>";
       
       $sql=mysql_query("select rollnumber,marks from $c3 order by rollnumber");
        while($row = mysql_fetch_array($sql))
       {
        //echo $row['rollnumber'];
         echo "<tr>";
         echo "<td>" . $row['rollnumber'] . "</td>";
         echo "<td>" .$row['marks']. "</td>";
         echo "</tr>";
       }
       echo "</table><br>";

            }
        

    }
      
 


if(isset($_POST['2']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='2' and evaluated='0' and step='4' "));

     $sem=mysql_fetch_array(mysql_query("select semester from test where course_id='$_SESSION[course]' and examtype='2' "));
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."q2";
            if($sem['semester']==1)
            {
            $c2=$_SESSION['course']."_"."q2"."_julynov_2015";
            }
            else
            {
              $c2=$_SESSION['course']."_"."q2"."_janmay_2016";
            }
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
              mysql_query("update $c3 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
                                mysql_query("update test set evaluated='1' where course_id='$_SESSION[course]' and examtype='2'");
                             $s=$_SESSION['course'];
        //echo "<br><br><b><center>COURSE ID  = $s </center></b><br><br> ";
                echo "<br><br><b><center> QUIZ-2 MARKS </center></b><br><br> ";

        echo "<table align='center'>
       <tr>
       <th>   ROLL NO   </th>
       <th>   MARKS     </th>
       </tr>";
       
       $sql=mysql_query("select rollnumber,marks from $c3 order by rollnumber");
        while($row = mysql_fetch_array($sql))
       {
        //echo $row['rollnumber'];
         echo "<tr>";
         echo "<td>" . $row['rollnumber'] . "</td>";
         echo "<td>" .$row['marks']. "</td>";
         echo "</tr>";
       }
       echo "</table><br>";
            }

    }
      
 


if(isset($_POST['3']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='3' and evaluated='0' and step='4' "));

     $sem=mysql_fetch_array(mysql_query("select semester from test where course_id='$_SESSION[course]' and examtype='3' "));
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."midsem";
            if($sem['semester']==1)
            {
            $c2=$_SESSION['course']."_"."midsem"."_julynov_2015";
            }
            else
            {
              $c2=$_SESSION['course']."_"."midsem"."_janmay_2016";
            }
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
              mysql_query("update $c3 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
                                mysql_query("update test set evaluated='1' where course_id='$_SESSION[course]' and examtype='3'");
                             $s=$_SESSION['course'];
        //echo "<br><br><b><center>COURSE ID  = $s </center></b><br><br> ";
                echo "<br><br><b><center> MIDSEM MARKS </center></b><br><br> ";

        echo "<table align='center'>
       <tr>
       <th>   ROLL NO   </th>
       <th>   MARKS     </th>
       </tr>";
       
       $sql=mysql_query("select rollnumber,marks from $c3 order by rollnumber");
        while($row = mysql_fetch_array($sql))
       {
        //echo $row['rollnumber'];
         echo "<tr>";
         echo "<td>" . $row['rollnumber'] . "</td>";
         echo "<td>" .$row['marks']. "</td>";
         echo "</tr>";
       }
       echo "</table><br>";
            }

    }
      
 


if(isset($_POST['4']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='4' and evaluated='0' and step='4' "));

     $sem=mysql_fetch_array(mysql_query("select semester from test where course_id='$_SESSION[course]' and examtype='4' "));
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."endsem";
            if($sem['semester']==1)
            {
            $c2=$_SESSION['course']."_"."endsem"."_julynov_2015";
            }
            else
            {
              $c2=$_SESSION['course']."_"."endsem"."_janmay_2016";
            }
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
              mysql_query("update $c3 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
                                mysql_query("update test set evaluated='1' where course_id='$_SESSION[course]' and examtype='4'");
                             $s=$_SESSION['course'];
        //echo "<br><br><b><center>COURSE ID  = $s </center></b><br><br> ";
                echo "<br><br><b><center> Endsem MARKS </center></b><br><br> ";

        echo "<table align='center'>
       <tr>
       <th>   ROLL NO   </th>
       <th>   MARKS     </th>
       </tr>";
       
       $sql=mysql_query("select rollnumber,marks from $c3 order by rollnumber");
        while($row = mysql_fetch_array($sql))
       {
        //echo $row['rollnumber'];
         echo "<tr>";
         echo "<td>" . $row['rollnumber'] . "</td>";
         echo "<td>" .$row['marks']. "</td>";
         echo "</tr>";
       }
       echo "</table><br>";
            }

    }
      
 


if(isset($_POST['5']))
     {
     $check=mysql_num_rows(mysql_query("select exam_activation from test where course_id='$_SESSION[course]' and examtype='5' and evaluated='0' and step='4' "));

     $sem=mysql_fetch_array(mysql_query("select semester from test where course_id='$_SESSION[course]' and examtype='5' "));
          if($check==0)
          {
            echo 'No finished exams to evaluate';
          }
          else
          {
            $c1=$_SESSION['course']."_"."viva";
            if($sem['semester']==1)
            {
            $c2=$_SESSION['course']."_"."viva"."_julynov_2015";
            }
            else
            {
              $c2=$_SESSION['course']."_"."viva"."_janmay_2016";
            }
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
              mysql_query("update $c3 set marks={$l} where rollnumber='{$roll}'");
            
            

            }
                                mysql_query("update test set evaluated='1' where course_id='$_SESSION[course]' and examtype='5'");
                             $s=$_SESSION['course'];
        //echo "<br><br><b><center>COURSE ID  = $s </center></b><br><br> ";
                echo "<br><br><b><center> VIVA MARKS </center></b><br><br> ";

        echo "<table align='center'>
       <tr>
       <th>   ROLL NO   </th>
       <th>   MARKS     </th>
       </tr>";
       
       $sql=mysql_query("select rollnumber,marks from $c3 order by rollnumber");
        while($row = mysql_fetch_array($sql))
       {
        //echo $row['rollnumber'];
         echo "<tr>";
         echo "<td>" . $row['rollnumber'] . "</td>";
         echo "<td>" .$row['marks']. "</td>";
         echo "</tr>";
       }
       echo "</table><br>";
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