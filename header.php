<?php
include("connect.php");
require("includes/functions.php");
session_start();
/*
<!--
*************************************************

It is the header file of the entire project.Also  Shows navigation links to other pages based on the user.

*************************************************
-->
*/


?>
<!DOCTYPE html>
<html lang="en" >
<head>
 
    <title>Online Examination Portal</title>
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	
    <link href="css/bootstrap.css" rel="stylesheet" media="screen" >
    <link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen" >
    <link href="css/docs.css" rel="stylesheet" media="screen"  media="screen" >
    <link href="css/prettify.css" rel="stylesheet" media="screen" >
    <link rel="stylesheet" href="css/style.css" type="text/css"  media="screen" >
    
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
    <script type="text/javascript" src="js/check_browser_close.js"></script>
   <!--
    <script type="text/javascript">

    window.onbeforeunload = function (event) {
    var message = 'Important: Please use logout button to exit';
    if (typeof event == 'undefined') {
        event = window.event;
    }
    if (event) {
        event.returnValue = message;
    }
    return message;
};
</script>
-->

<style>
      html,
      body {
        height: 96.8%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }
	  
      #wrap > .container {
        padding-top: 60px;
      }
      .container .credit {
        margin: 20px 0;
      }

</style>
</head>
<body>
<div id="wrap" >
<div class="navbar navbar-fixed-top navbar-inverse">
<div class="navbar-inner">
<div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <!--sfetch the current user name from database-->
            <a class="brand" href= "<?php if($_SESSION['faculty_id']){ echo "faculty.php"; } else if($_SESSION['rollnumber']) echo 'profile_stu.php'; else {echo "index.php";} ?>" >OEP</a>
            <div class="nav-collapse collapse">
			<ul class="nav">
			 <?php
			 if($_SESSION['faculty_id'])
			   {
				  ?>
              
                <li class="<?php if($_SESSION['tab']=="1") echo "active"; ?>" ><a href="<?php if($_SESSION['faculty_id']){ echo "faculty.php"; } ?>">Dashboard</a></li>
        				<li class="<?php if($_SESSION['tab']=="3") echo "active"; ?>" ><a href="<?php if($_SESSION['course']){ echo "set_questions.php"; } else echo "faculty.php"; ?>">Set Exam</a></li>
        				<li class="<?php if($_SESSION['tab']=="7") echo "active"; ?>" ><a href="<?php if($_SESSION['course']){ echo "fac_ques.php"; } else echo "faculty.php"; ?>">Set Questions</a></li>
        				<li class="<?php if($_SESSION['tab']=="4") echo "active"; ?>" ><a href="<?php if($_SESSION['course']){ echo "update_exam.php"; } else echo "faculty.php"; ?>">Schedule Exam</a></li>
                <li class="<?php if($_SESSION['tab']=="5") echo "active"; ?>" ><a href="<?php if($_SESSION['course']){ echo "evaluate.php"; } else echo "faculty.php"; ?>">Evaluate</a></li>
                <li class="<?php if($_SESSION['tab']=="8") echo "active"; ?>" ><a href="<?php if($_SESSION['faculty_id']){ echo "profile.php"; } else echo "faculty.php"; ?>">Profile</a></li>
                <li class="dropdown <?php if($_SESSION['tab']=="6") echo "active"; ?>"  >
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="history.php">My History</a></li>
                    <li><a href="about.php">About OEP</a></li>
                    <li><a href="feedback.php">Submit Feedback</a></li>
                  </ul>
                </li>

				<?php
			}
			else if($_SESSION['rollnumber'])
			 {
			  ?>
               <li class="<?php if($_SESSION['tab']=="1") echo "active"; ?>" ><a href="about.php" >About OEP</a></li>
               <li class="<?php if($_SESSION['tab']=="1") echo "active"; ?>" ><a href="markshistory.php" >Marks History</a></li>
              
			  <?php
			 }
				?>
             </ul>
            </div><!--/.nav-collapse -->
			<?php
			if(isset($_SESSION['faculty_id']) || isset($_SESSION['rollnumber']))
			{
			?>
			<div class="text-right">
			<form class="navbar-form" action="index.php" method="post"  ><span style="color:white;padding:5px;" >Hello, <?php echo $_SESSION['name'];?> </span> <input type="submit" class="btn btn-warning" name="logout" value="LogOut" ></form>
			</div>
			<?php
			}
			?>
          </div>

</div>
</div>

<div class="container-fluid" >
<div class="row-fluid text-center">

<h1>Online Examination Portal</h1>
<h3>Welcome</h3>
</div>