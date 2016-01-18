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
			<td><br /><a href='./managegoal.php?goalid=$goalid'><- Milestones</a></td>
		</tr>
		</table>
		<table>
		<tr>
			<td><br /><b>Enter your new Milestone:</b></td>
		</tr>
		</table>";
	
	if ($username && $userid) {
		
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		if ($_POST['setmilestonebtn']) {
			// get the form data
			$name = $_POST['name'];
			$description = $_POST['description'];
			$currentkvi = $_POST['currentkvi'];
			$targetkvi = $_POST['targetkvi'];
			$targetdate = $_POST['targetdate'];
			
			
			//make sure all data was entered
			
			if ($name) {
				if ($description) {
					if ($targetkvi && $targetkvi > 0 && $currentkvi >= 0) {
						
						//connect to db
						require("./connect.php");
						
						//make sure milestone isn't already in db
						$query = mysql_query("SELECT * FROM milestone WHERE goal_id = '$goalid' AND milestone_name = '$name'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 0) {
				
							//add the goal to the database
							mysql_query("INSERT INTO `milestone` (`milestone_id`, `goal_id`, `milestone_name`, `Description`, `target_kvi`, `current_kvi`, `target_date`, `completed_date`) 
											VALUES (NULL, '$goalid', '$name', '$description', '$targetkvi', '$currentkvi', '$targetdate', NULL)" );
							
							//if current kvi > 0, add the ammount to the current kvi of the goal
							if ($currentkvi > 0) {
								mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi` + $currentkvi WHERE `goal_id` = '$goalid'");
							}
							
							//make sure the milestone was added was changed
							$query = mysql_query("SELECT * FROM milestone WHERE goal_id = '$goalid' AND milestone_name = '$name' AND Description = '$description' 
												AND target_kvi = '$targetkvi' AND current_kvi = '$currentkvi' AND target_date = '$targetdate'");
							$numrows = mysql_num_rows($query);
							if ($numrows == 1) {
								echo "Your milestone has been added.";
							}
							else 
								echo "An error has occured and your milestone was not added.";
						}
						else 
							echo "You already have this milestone set.";
				
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
		
		
		echo "<form action='./addmilestone.php?goalid=$goalid' method='post'>
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
			<td>Target KVI:</td>
			<td><input type='number' name='targetkvi'></td>
		</tr>
		<tr>
			<td>Current KVI:</td>
			<td><input type='number' name='currentkvi'></td>
		</tr>
		<tr>
			<td>Target Date:</td>
			<td><input type='text' id='datepicker' name='targetdate'></td>
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