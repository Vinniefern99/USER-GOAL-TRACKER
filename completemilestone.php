<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$milestoneid = $_GET['milestoneid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Context-Type" content="text/html; charset=utf-8" />
	<title>Member System - Reset Password</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 	 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
 	 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 	 <link rel="stylesheet" href="/resources/demos/style.css">
 	 <script>
 	 	$(function() {
  		  $( "#datepicker" ).datepicker();
 	 	});
 	 </script>
</head>
<body>
	<button onclick="history.go(-1);">Back</button>
	<br />
	<br />
	<?php 
	
	if ($username && $userid) {
		
		if ($_POST['setcompletedatebtn']) {
			// get the form data
			$completedate = $_POST['completedate'];
			
			//make sure all data was entered
			if ($completedate) {
					
					//connect to db
					require("./connect.php");

					//update the currentt kvi values for this milestone and goal
					mysql_query("UPDATE `milestone` SET `completed_date` = '$completedate' WHERE `milestone_id` = '$milestoneid'");
					
					//make sure the progress was added was changed
					$query = mysql_query("SELECT * FROM milestone WHERE milestone_id = '$milestoneid' AND completed_date = '$completedate'");
					$numrows = mysql_num_rows($query);
					if ($numrows == 1) {
						echo "Your milestone has been marked as completed.";
					}
					else 
						echo "An error has occured and your milestone was not marked completed.";

					mysql_close();
			}
			else
				echo "Please enter a complete date.";
		}
		
		echo "<form action='./completemilestone.php?milestoneid=$milestoneid' method='post'>
		<table>
		<tr>
			<td><a href='./member.php'>Back to Homepage</a></td>
		</tr>
		<tr>
			<td><br />=Milestone completion date:</td>
			<td><br /><input type='text' id='datepicker' name='completedate'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='setcompletedatebtn' value='Submit'></td>
		</tr>
		
		</form>";
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";

	?>

</body>

</html>