<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$goalid = $_GET['goalid'];
$goalname = $_GET['goalname'];
$milestoneid = $_GET['milestoneid'];
$progressid = $_GET['progressid'];
$progressdesc = $_GET['description'];
$oldkvivalue = $_GET['kvivalue'];
$occurdate = $_GET['occurdate'];
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
			<td><br /><a href='./managemilestone.php?milestoneid=$milestoneid&goalname=$goalname&goalid=$goalid'><- Progress</a></td>
		</tr>
		</table>";
	
	if ($username && $userid) {
		
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		
		if ($_POST['setprogressbtn']) {
			// get the form data
			$newdescription = $_POST['description'];
			$newkvivalue = $_POST['kvivalue'];
			$newoccurancedate = $_POST['occurancedate'];
			$oldkvivalue = $_GET['oldkvivalue'];
			
			//make sure all data was entered
			
			if ($newdescription) {
				if ($newkvivalue > 0) {
					if ($newoccurancedate) {
						
						//connect to db
						require("./connect.php");
						
						//update the goal with the edits
						mysql_query("UPDATE `progress` SET `description`='$newdescription',`kvi_value`='$newkvivalue',`occurance_date`='$newoccurancedate' 
								WHERE `progress_id` = '$progressid'");
						
						//if current kvi changed, update the current kvi of the goal and milestone
						if ($newkvivalue > $oldkvivalue) {
							$updatedkvivaluediff = $newkvivalue - $oldkvivalue;
							mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi`+ '$updatedkvivaluediff' WHERE `goal_id` = '$goalid'");
							mysql_query("UPDATE `milestone` SET `current_kvi` = `current_kvi`+ '$updatedkvivaluediff' WHERE `milestone_id` = '$milestoneid'");
								
						}
						if ($newkvivalue < $oldkvivalue) {
							$updatedkvivaluediff = $oldkvivalue - $newkvivalue;
							mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi`- '$updatedkvivaluediff' WHERE `goal_id` = '$goalid'");
							mysql_query("UPDATE `milestone` SET `current_kvi` = `current_kvi`- '$updatedkvivaluediff' WHERE `milestone_id` = '$milestoneid'");
								
						}
						
						//make sure the progress was updated
						$query = mysql_query("SELECT * FROM `progress` WHERE `description`='$newdescription' AND `kvi_value`='$newkvivalue' AND `occurance_date`='$newoccurancedate'
											AND `progress_id` = '$progressid'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 1) {
							echo "Your progress has been updated.";
						}
						else 
							echo "An error has occured and your progress was not updated.";
				
						mysql_close();
					}
					else 
						echo "You must enter a date.";
				}
				else 
					echo "You must enter a KVI value greater than 0.";
			}
			else
				echo "You must enter a description.";
		}
		
		echo "<form action='./editprogress.php?goalid=$goalid&milestoneid=$milestoneid&progressid=$progressid&oldkvivalue=$oldkvivalue&goalname=$goalname' method='post'>
		<table>
		<tr>
			<td><br />Progress Description:</td>
			<td><input type='text' name='description' value='$progressdesc'></td>
		</tr>
		<tr>
			<td>KVI Value:</td>
			<td><input type='number' name='kvivalue' value='$oldkvivalue'></td>
		</tr>
		<tr>
			<td>Date:</td>
			<td><input type='text' id='datepicker' name='occurancedate' value='$occurdate'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='setprogressbtn' value='Submit'></td>
		</tr>
		
		</form>";
		
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";

	?>

</body>

</html>