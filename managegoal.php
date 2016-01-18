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
	<title>Member System - Manage Goal</title>
</head>
<body>
	<?php 
	
	require ("./connect.php");
	$dbgoalname = "";
	
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
	
	$header = "
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
		</table>
		<table>
		<tr>
			<td><br /><b>Goal: $dbgoalname</b></td>
		</tr>
		<tr>
			<td>Date Started: $dbgoalstart</td>
		</tr>
		<tr>
			<td>Date Completed: $dbgoalcomplete</td>
		</tr>
		<tr>
			<td>Target KVI: $dbgoaltargetkvi</td>
		</tr>
		<tr>
			<td>Current KVI Completed: $dbgoalcurrentkvi</td>
		</tr>
		<tr>
			<td><a href='./completegoal.php?goalid=$goalid'>Mark Goal as Completed (add/edit Completed Date)</a></td>
		</tr>
		<tr>
			<td><br /><b>&nbsp;&nbsp;Milestones for this Goal</b></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;(Click a milestone to manage progress)</td>
		</tr>
		</table>";
	
	if (!$username && !$userid)
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";
	else {
		echo "Welcome, <b>$username</b>";
		echo $header;
		
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
				<td><b>Target KVI</b></td>
				<td><b>Current KVI</b></td>
				<td><b>% Complete</b></td>
				<td><b>Target Date</b></td>
				<td><b>Completed Date</b></td>
				<th colspan='3'>Modify</th>
			</tr>\n";
			
			while($row = mysql_fetch_assoc($query)) {	
				echo "
				<tr>
					<td><a href='./managemilestone.php?milestoneid=".$row['milestone_id']."&goalname=$dbgoalname&goalid=$goalid'>".$row['milestone_name']."</a></td>
					<td>".$row['Description']."</td>
					<td align='center'>".$row['target_kvi']."</td>
					<td align='center'>".$row['current_kvi']."</td>
					<td align='center'>".(($row['current_kvi']/$row['target_kvi'])*100)."%</td>	
					<td>".$row['target_date']."</td>
					<td>".$row['completed_date']."</td>
					<td><a href='./editmilestone.php?goalid=$goalid&milestoneid=".$row['milestone_id']."&milestonename=".$row['milestone_name']."&milestonedesc=".$row['Description']."&targetkvi=".$row['target_kvi']."&currentkvi=".$row['current_kvi']."&targetdate=".$row['target_date']."'>Edit</a></td>
					<td><a href='./deletemilestone.php?milestoneid=".$row['milestone_id']."&currentkvi=".$row['current_kvi']."&goalid=$goalid'>Delete</a></td>
				</tr>\n";
			}
		}
			
		else 
			echo "You don't have any Milestones. Click Add Milestones to start.";
		
		echo "</table>";
		echo "<td><br /><a href='./addmilestone.php?goalid=$goalid'>Add a Milestone</a></td>";
		
		mysql_close();
	}
		
	?>

</body>

</html>