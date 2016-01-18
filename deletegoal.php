<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$goalid = $_GET['goalid'];
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
	<?php 
	
	$headerform = "
		<table>
		<tr>
			<td><a href='./logout.php'>Logout</a></td>
			<td><a href='./resetpass.php'>Reset Your Password</a></td>
		</tr>
		</table>
		<table>
		<tr>
			<td><br /><a href='./member.php'><- Goals</a></td>
		</tr>
		</table>";
	
	if ($username && $userid) {
		
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		if ($_POST['confirmdelete']) {
			// get the form data
			$yesorno = $_POST['yesorno'];
			
			//make sure all data was entered
			if ($yesorno) {
				if ($yesorno == 'yes') {

					//connect to db
					require("./connect.php");

					//delete the goal and all milestones associated with that goal
					mysql_query("DELETE FROM `goal` WHERE `goal_id` = '$goalid'");
					mysql_query("DELETE FROM `milestone` WHERE `goal_id` = '$goalid'");
					
					//delete all progress
					$milestonequery = mysql_query("SELECT `milestone_id` FROM `milestone` WHERE `goal_id` = '$goalid'");
					while($row = mysql_fetch_assoc($milestonequery)) {
						$milestonetodelete = $row['milestone_id'];
						mysql_query("DELETE FROM `progress` WHERE `milestone_id` = '$milestonetodelete'");
					}
					
					//make sure the progress was added was changed
					$query = mysql_query("SELECT * FROM `goal` WHERE `goal_id` = '$goalid'");
					$numrows = mysql_num_rows($query);
					
					$query2 = mysql_query("SELECT * FROM `milestone` WHERE `goal_id` = '$goalid'");
					$numrows2 = mysql_num_rows($query2);
					
					$query3 = mysql_query("SELECT * FROM `progress` WHERE `progress`.`milestone_id` IN
							(SELECT `milestone_id` FROM `milestone` WHERE `goal_id` = '$goalid') ");
					$numrows3 = mysql_num_rows($query3);
					
					if ($numrows == 0 && $numrows2 == 0 && $numrows3 == 0) {
						echo "Your goal and all milestones/progress associated with that goal has been deleted.";
					}
					else 
						echo "An error has occured and your goal was not completely deleted.";

					mysql_close();
				}
				else 
					echo "Goal will not be deleted.";
			}
			else
				echo "Please select Yes or NO.";
		}
		
		echo "<form action='./deletegoal.php?goalid=$goalid' method='post'>
		<table>
		<tr>
			<td><br />Delete this goal?:</td>
		</tr>
		<tr>
			<td><input type='radio' name='yesorno' value='yes'>Yes</td>
			<td><input type='radio' name='yesorno' value='no'>No</td>
		</tr>
		<tr>
			<td><input type='submit' name='confirmdelete' value='Submit'></td>
		</tr>
		
		</form>";
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";

	?>

</body>

</html>