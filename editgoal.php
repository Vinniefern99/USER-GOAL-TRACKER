<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$goalid = $_GET['goalid'];
$goalname = $_GET['goalname'];
$goalstart = $_GET['goalstart'];
$targetkvi = $_GET['targetkvi'];
$currentkvi = $_GET['currentkvi'];

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
		
		if ($_POST['setgoalbtn']) {
			// get the form data
			$newname = $_POST['name'];
			$newstartdate = $_POST['startdate'];
			$newtargetkvi = $_POST['targetkvi']; 
			$newcurrentkvi = $_POST['currentkvi'];
			
			//make sure all data was entered
			
			if ($newname) {
				if ($newstartdate) {
					if ($newtargetkvi && $newtargetkvi > 0 && $newcurrentkvi >= 0) {
						
						//connect to db
						require("./connect.php");
						
						//update the goal with the edits
						mysql_query("UPDATE `goal` SET `goal_name`='$newname',`goal_start`='$newstartdate',`target_kvi`='$newtargetkvi', `current_kvi`='$newcurrentkvi' 
								WHERE `goal_id` = '$goalid'");
						
						//make sure the goal was updated
						$query = mysql_query("SELECT * FROM goal WHERE user_id = '$userid' AND goal_name = '$newname' AND goal_start = '$newstartdate' 
											AND target_kvi = '$newtargetkvi' AND current_kvi = '$newcurrentkvi'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 1) {
							echo "Your goal has been updated. <a href='./member.php'>Back to Goals</a>";
						}
						else 
							echo "An error has occured and your goal was not updated. <a href='./member.php'>Back to Goals</a>";
				
						mysql_close();
					}
					else 
						echo "You must enter a target KVI and greater than 0 and Current KVI must be 0 or greater.";
				}
				else 
					echo "You must enter a start date.";
			
			}
			else
				echo "You must enter a name for your goal.";
		}
		
		echo "<form action='./editgoal.php?goalid=$goalid' method='post'>
		<table>
		<tr>
			<td><br />Goal Name:</td>
			<td><br /><input type='text' name='name' value='$goalname'></td>
		</tr>
		<tr>
			<td>Start Date:</td>
			<td><input type='text' id='datepicker' name='startdate' value='$goalstart'></td>
		</tr>
		<tr>
			<td>Target KVI:</td>
			<td><input type='number' name='targetkvi' value='$targetkvi'></td>
		</tr>
		<tr>
			<td>Current KVI:</td>
			<td><input type='number' name='currentkvi' value='$currentkvi'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='setgoalbtn' value='Submit Edits'></td>
		</tr>
		
		</form>";
		
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";

	?>

</body>

</html>