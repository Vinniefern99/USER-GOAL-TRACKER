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
	<title>Member System - Manage Goal</title>
</head>
<body>
	<?php 
	$goalid = $_GET['goalid'];
	
	require ("./connect.php");
	
	$query = mysql_query("SELECT * FROM goal WHERE goal_id = '$goalid'");

	$numrows = mysql_num_rows($query);
	if ($numrows == 1) {
		$row = mysql_fetch_assoc($query);
		$dbgoalname = $row['goal_name'];
		$dbgoalstart = $row['goal_start'];
		$dbgoalcomplete = $row['goal_complete'];
		$dbgoaltargetkvi = $row['target_kvi'];
		$dbgoalcurrentkvi = $row['current_kvi'];
	}
	else
		echo "Error getting information about this goal.";
	
	mysql_close();
	
	$headerform = "<form action='./member.php' method='post'>
		<table>
		<tr>
			<td><a href='./logout.php'>Logout</a></td>
			<td><a href='./resetpass.php'>Reset Your Password</a></td>
		</tr>
		</table>
		<table>
		<tr>
			<td><br /><b>Goal:</b> $dbgoalname</td>
		</tr>
		<tr>
			<td><b>Date Started:</b> $dbgoalstart</td>
		</tr>
		<tr>
			<td><b>Date Completed:</b> $dbgoalcomplete</td>
		</tr>
		<tr>
			<td><b>Target KVI:</b> $dbgoaltargetkvi</td>
		</tr>
		<tr>
			<td><b>Current KVI Completed:</b> $dbgoalcurrentkvi</td>
		</tr>
		<tr>
			<td><br /><b>Milestones:</b></td>
		</tr>
		</table>
		</form>";
	
	if (!$username && !$userid)
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";
	else {
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		
		
		require ("./connect.php");
		
		$query = mysql_query("SELECT * FROM milestone WHERE goal_id = '$goalid'");
		$numrows = mysql_num_rows($query);
		
		$fields_num = mysql_num_fields($query);
		if ($numrows > 0)  {
			
			echo "
			<table border=1'>
			<tr>
				<td><b>Milestone</b></td>
				<td><b>Description</b></td>
				<td><b>KVI Type</b></td>
				<td><b>Target KVI</b></td>
				<td><b>Current KVI</b></td>
				<td><b>% Complete</b></td>
				<td><b>Target Date</b></td>
				<td><b>Completed Date</b></td>
			</tr>\n";
			
			while($row = mysql_fetch_assoc($query)) {	
				echo "
				<tr>
					<td><a href='./managegoal.php'>".$row['goal_name']."</a></td>
					<td>".$row['goal_start']."</td>
					<td align='center'>".(($row['current_kvi']/$row['target_kvi'])*100)."%</td>	
				</tr>\n";
			}
		}
			
		else 
			echo "You don't have any Milestones. Click Add Milestones to start.";
		
		echo "</table>";
		echo "<td><br /><a href='./addmilestone.php'>Add a Milestone</a></td>";
		
		mysql_close();
	}
		
	?>

</body>

</html>