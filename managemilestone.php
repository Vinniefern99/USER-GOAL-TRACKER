<?php
error_reporting (E_ALL ^ E_NOTICE);
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$milestoneid = $_GET['milestoneid'];
$goalname = $_GET['goalname'];
$goalid = $_GET['goalid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Context-Type" content="text/html; charset=utf-8" />
	<title>Member System - Manage Goal</title>
</head>
<body>
	<?php 
	
	require ("./connect.php");
	
	$query = mysql_query("SELECT * FROM milestone WHERE milestone_id = '$milestoneid'");

	$numrows = mysql_num_rows($query);
	if ($numrows == 1) {
		$row = mysql_fetch_assoc($query);
		$dbmilestonename = $row['milestone_name'];
		$dbdescription = $row['Description'];
		$dbtargetkvi = $row['target_kvi'];
		$dbcurrentkvi = $row['current_kvi'];
		$dbtargetdate = $row['target_date'];
		$dbcompleteddate = $row['completed_date'];
	}
	else
		echo "Error getting information about this milestone.";
	
	mysql_close();
	
	$header = "
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
			<td><br /><b>Milestone: $dbmilestonename</b></td>
		</tr>
		<tr>
			<td>Goal: $goalname</td>
		</tr>
		<tr>
			<td>Description: $dbdescription</td>
		</tr>
		<tr>
			<td>Target KVI: $dbtargetkvi</td>
		</tr>
		<tr>
			<td>Current KVI Completed: $dbcurrentkvi</td>
		</tr>
		<tr>
			<td>Target Date: $dbtargetdate</td>
		</tr>
		<tr>
			<td>Completed Date: $dbcompleteddate</td>
		</tr>
		<tr>
			<td><a href='./completemilestone.php?milestoneid=$milestoneid'>Mark Milestone as Completed (add/edit Completed Date)</a></td>
		</tr>
		<tr>
			<td><br /><b>&nbsp;&nbsp;Progress for this milestone</b></td>
		</tr>
		</table>";
	
	if (!$username && !$userid)
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";
	else {
		echo "Welcome, <b>$username</b>";
		echo $header;
		
		require ("./connect.php");
		
		$query = mysql_query("SELECT * FROM progress WHERE milestone_id = '$milestoneid'");
		$numrows = mysql_num_rows($query);
		
		$fields_num = mysql_num_fields($query);
		if ($numrows > 0)  {
			
			echo "
			<table border=1'>
			<tr>
				<td><b>Progress Description</b></td>
				<td><b>Date</b></td>
				<td><b>KVI Value</b></td>
				<th colspan='3'>Modify</th>
			</tr>\n";
			
			while($row = mysql_fetch_assoc($query)) {	
				echo "
				<tr>
					<td>".$row['description']."</td>
					<td>".$row['occurance_date']."</td>
					<td align='center'>".$row['kvi_value']."</td>
					<td><a href='./editprogress.php?goalid=$goalid&goalname=$goalname&milestoneid=$milestoneid&progressid=".$row['progress_id']."&description=".$row['description']."&kvivalue=".$row['kvi_value']."&occurdate=".$row['occurance_date']."'>Edit</a></td>
					<td><a href='./deleteprogress.php?progressid=".$row['progress_id']."&milestoneid=$milestoneid&goalname=$goalname&goalid=$goalid&kvivalue=".$row['kvi_value']."'>Delete</a></td>
				</tr>\n";
			}
		}
			
		else 
			echo "You don't have any Progress. Click Add Progress to start.";
		
		echo "</table>";
		echo "<td><br /><a href='./addprogress.php?milestoneid=$milestoneid&goalid=$goalid&goalname=$goalname'>Add Progress</a></td>";
		
		mysql_close();
	}
		
	?>

</body>

</html>