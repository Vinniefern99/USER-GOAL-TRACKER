<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$milestoneid = $_GET['milestoneid'];
$goalid = $_GET['goalid'];
$goalname = $_GET['goalname'];
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
		</table>
		<table>
		<tr>
			<td><br /><b>Enter your new Progress:</b></td>
		</tr>
		</table>";
	
	if ($username && $userid) {
		
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		if ($_POST['setprogressbtn']) {
			// get the form data
			$description = $_POST['description'];
			$kvivalue = $_POST['kvivalue']; 
			$date = $_POST['occurance_date'];
			
			//make sure all data was entered
			
			if ($description) {
				if ($kvivalue > 0) {
					if ($kvivalue > 0) {

						//connect to db
						require("./connect.php");
							
						//add the goal to the database
						mysql_query("INSERT INTO `progress` (`progress_id`, `milestone_id`, `description`, `kvi_value`, `occurance_date`)
								VALUES (NULL, '$milestoneid', '$description', '$kvivalue', '$date')");
							
						//update the currentt kvi values for this milestone and goal
						mysql_query("UPDATE `milestone` SET `current_kvi` = `current_kvi` + $kvivalue WHERE `milestone_id` = '$milestoneid'");
						mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi` + $kvivalue WHERE `goal_id` = '$goalid'");
							
						//make sure the progress was added was changed
						$query = mysql_query("SELECT * FROM progress WHERE milestone_id = '$milestoneid' AND description = '$description'
								AND kvi_value = '$kvivalue' AND occurance_date = '$date'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 1) {
							echo "Your progress has been added.";
						}
						else
							echo "An error has occured and your progress.";
						
							mysql_close();
					}
					else 
						echo "You must enter a date.";
				}
				else 
					echo "You must enter a KVI value greater than 0.";
			}
			else
				echo "You must enter a descriptopm for your progress.";
		}
		
		echo "<form action='./addprogress.php?goalid=$goalid&milestoneid=$milestoneid' method='post'>
		<table>
		<tr>
			<td>Progress Description:</td>
			<td><input type='text' name='description'></td>
		</tr>
		<tr>
			<td>KVI Value:</td>
			<td><input type='number' name='kvivalue'></td>
		</tr>
		<tr>
			<td>Date:</td>
			<td><input type='text' id='datepicker' name='occurance_date'></td>
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