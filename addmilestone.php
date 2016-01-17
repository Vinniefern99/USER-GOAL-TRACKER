<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
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
	
	if ($username && $userid) {
		
		if ($_POST['setmilestonebtn']) {
			// get the form data
			$name = $_POST['name'];
			$description = $_POST['desription'];
			$gkvitype = $_POST['kvitype']; 
			$currentkvi = $_POST['currentkvi'];
			$targetkvi = $_POST['targetkvi'];
			
			//make sure all data was entered
			
			if ($name) {
				if ($description) {
					if ($goaltargetkvi && $goaltargetkvi > 0 && $goalcurrentkvi >= 0) {
						
						//connect to db
						require("./connect.php");
						
						//make sure goal isn't already in db
						$query = mysql_query("SELECT * FROM goal WHERE user_id = '$userid' AND goal_name = '$goalname'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 0) {
				
							//add the goal to the database
							mysql_query("INSERT INTO `goal` (`goal_id`, `user_id`, `goal_name`, `goal_start`, `goal_complete`, `target_kvi`, `current_kvi`) 
											VALUES (NULL, '$userid', '$goalname', '$goalstartdate', NULL, '$goaltargetkvi', '$goalcurrentkvi')" );
							
							
							//make sure the goal was added was changed
							$query = mysql_query("SELECT * FROM goal WHERE user_id = '$userid' AND goal_name = '$goalname' AND goal_start = '$goalstartdate' 
												AND target_kvi = '$goaltargetkvi' AND current_kvi = '$goalcurrentkvi'");
							$numrows = mysql_num_rows($query);
							if ($numrows == 1) {
								echo "Your goal has been added.";
							}
							else 
								echo "An error has occured and your goal.";
						}
						else 
							echo "You already have this goal set.";
				
						mysql_close();
					}
					else 
						echo "You must enter a target KVI and greater than 0 and Current KVI must be 0 or greater.";
				}
				else 
					echo "You must enter a description.";
			
			}
			else
				echo "You must enter a name for your milestone.";
		}
		
		echo "<form action='./addmilestone.php' method='post'>
		<table>
		<tr>
			<td>Milestone Name:</td>
			<td><input type='text' name='name'></td>
		</tr>
		<tr>
			<td>Description:</td>
			<td><input type='text' name='description'></td>
		</tr>
		<tr>
			<td>KVI Type</td>
			<td><input type='number' name='kvitype'></td>
		</tr>
		<tr>
			<td>Target KVI:</td>
			<td><input type='number' name='targetkvi' value='0'></td>
		</tr>
		<tr>
			<td>Current KVI:</td>
			<td><input type='number' name='currentkvi' value='0'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='setmilestonebtn' value='Submit'></td>
		</tr>
		
		</form>";
		
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";



	?>

</body>

</html>