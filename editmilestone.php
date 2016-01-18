<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$goalid = $_GET['goalid'];
$milestoneid = $_GET['milestoneid'];
$milestonename = $_GET['milestonename'];
$milestonedesc = $_GET['milestonedesc'];
$targetkvi = $_GET['targetkvi'];
$oldcurrentkvi = $_GET['currentkvi'];
$targetdate = $_GET['targetdate'];
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
		</table>";
	
	if ($username && $userid) {
		
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		if ($_POST['setmilestonebtn']) {
			// get the form data
			$newname = $_POST['name'];
			$newdescription = $_POST['description'];
			$newcurrentkvi = $_POST['currentkvi'];
			$newtargetkvi = $_POST['targetkvi'];
			$newtargetdate = $_POST['targetdate'];
			$oldcurrentkvi = $_GET['oldcurrentkvi'];
			
			//make sure all data was entered
			
			if ($newname) {
				if ($newdescription) {
					if ($newtargetkvi && $newtargetkvi > 0 && $newcurrentkvi >= 0) {
						
						//connect to db
						require("./connect.php");
						
						//update the goal with the edits
						mysql_query("UPDATE `milestone` SET `milestone_name`='$newname',`Description`='$newdescription',`target_kvi`='$newtargetkvi', `current_kvi`='$newcurrentkvi' 
								WHERE `milestone_id` = '$milestoneid'");
						
						//if current kvi changed, update the current kvi of the goal
						if ($newcurrentkvi > $oldcurrentkvi) {
							$updatedcurrentkvidiff = $newcurrentkvi - $oldcurrentkvi;
							mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi`+ '$updatedcurrentkvidiff' WHERE `goal_id` = '$goalid'");
						}
						if ($newcurrentkvi < $oldcurrentkvi) {
							$updatedcurrentkvidiff = $oldcurrentkvi - $newcurrentkvi;
							mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi`- '$updatedcurrentkvidiff' WHERE `goal_id` = '$goalid'");
						}
						
						//make sure the milestone was updated
						$query = mysql_query("SELECT * FROM `milestone` WHERE `milestone_name`='$newname' AND `Description`='$newdescription' AND `target_kvi`='$newtargetkvi'
											AND `current_kvi`='$newcurrentkvi'  AND `milestone_id` = '$milestoneid'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 1) {
							echo "Your milestone has been updated.";
						}
						else 
							echo "An error has occured and your milestone was not updated.";
				
						mysql_close();
					}
					else 
						echo "You must enter a target KVI and greater than 0 and Current KVI must be 0 or greater.";
				}
				else 
					echo "You must enter a start date.";
			
			}
			else
				echo "You must enter a name for your milestone.";
		}
		
		echo "<form action='./editmilestone.php?milestoneid=$milestoneid&goalid=$goalid&oldcurrentkvi=$oldcurrentkvi' method='post'>
		<table>
		<tr>
			<td><br />Milestone Name:</td>
			<td><br /><input type='text' name='name' value = '$milestonename'></td>
		</tr>
		<tr>
			<td>Description:</td>
			<td><input type='text' name='description' value = '$milestonedesc'></td>
		</tr>
		<tr>
			<td>Target KVI:</td>
			<td><input type='number' name='targetkvi' value = '$targetkvi'></td>
		</tr>
		<tr>
			<td>Current KVI:</td>
			<td><input type='number' name='currentkvi' value = '$oldcurrentkvi'></td>
		</tr>
		<tr>
			<td>Target Date:</td>
			<td><input type='text' id='datepicker' name='targetdate' value = '$targetdate'></td>
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