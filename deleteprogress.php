<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$goalid = $_GET['goalid'];
$goalname = $_GET['goalname'];
$milestoneid = $_GET['milestoneid'];
$progressid = $_GET['progressid'];
$kvivalue = $_GET['kvivalue'];

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
		
		if ($_POST['confirmdelete']) {
			// get the form data
			$yesorno = $_POST['yesorno'];
			$kvivalue = $_GET['kvivalue'];
			
			//make sure all data was entered
			if ($yesorno) {
				if ($yesorno == 'yes') {

					//connect to db
					require("./connect.php");

					//delete the progress
					mysql_query("DELETE FROM `progress` WHERE `progress_id` = '$progressid'");
					
					//Remove  KVIs from goal and milestone
					if ($kvivalue > 0) {
						
						mysql_query("UPDATE `goal` SET `current_kvi` = `current_kvi`- '$kvivalue' WHERE `goal_id` = '$goalid'");
						mysql_query("UPDATE `milestone` SET `current_kvi` = `current_kvi`- '$kvivalue' WHERE `milestone_id` = '$milestoneid'");
					}
					
					//make sure the progress was added was changed
					$query = mysql_query("SELECT * FROM `progress` WHERE `progress_id` = '$progressid'");
					$numrows = mysql_num_rows($query);
					
					if ($numrows == 0) {
						echo "This progress has been deleted.";
					}
					else 
						echo "An error has occured and your progress was not deleted.";

					mysql_close();
				}
				else 
					echo "Progress will not be deleted.";
			}
			else
				echo "Please select Yes or NO.";
		}
		
		echo "<form action='./deleteprogress.php?progressid=$progressid&milestoneid=$milestoneid&goalname=$goalname&goalid=$goalid&kvivalue=$kvivalue' method='post'>
		<table>
		<tr>
			<td><br />Delete this Progress?:</td>
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