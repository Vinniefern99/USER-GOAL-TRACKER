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
	<title>Member System - Members</title>
</head>
<body>
	<?php 
	echo $_SESSION['goal_name'];
	
	$headerform = "<form action='./member.php' method='post'>
		<table>
		<tr>
			<td><a href='./logout.php'>Logout</a></td>
			<td><a href='./resetpass.php'>Reset Your Password</a></td>
		</tr>
		<table>
		<table>
		<tr>
			<td><br /><b>Homepage</b></td>
		</tr>
		<table>
		</table>
		<tr>
			<td><br /><b>Goals:</b> </td>
			<td><br />(Click a goal to manage goal (milestones, completion date, etc))</td>
		</tr>
		</table>
		</form>";
	
	$footerform = "<form action='./member.php' method='post'>
		<table>
		<tr>
			<td><a href='./addgoal.php'>Add a Goal</a></td>
		</tr>
		</table>
		</form>";
	
	if (!$username && !$userid)
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";
	else {
		echo "Welcome, <b>$username</b>";
		echo $headerform;
		
		require ("./connect.php");
		
		$query = mysql_query("SELECT * FROM goal WHERE user_id = '$userid'");
		$numrows = mysql_num_rows($query);
		
		$fields_num = mysql_num_fields($query);
		
		if ($numrows > 0) {
			
			echo "
			<table border=1'>
			<tr>
				<td><b>Goal</b></td>
				<td><b>Date Started</b></td>
				<td><b>Date Completed</b></td>
				<td><b>Target KVI</b></td>
				<td><b>Current KVI</b></td>
				<td><b>% Complete</b></td>
				<th colspan='3'>Modify</th>
			</tr>\n";
			
			while($row = mysql_fetch_assoc($query)) {	
				echo "
				<tr>
					<td><a href='./managegoal.php?goalid=".$row['goal_id']."'>".$row['goal_name']."</a></td>
					<td>".$row['goal_start']."</td>
					<td>".$row['goal_complete']."</td>
					<td align='center'>".$row['target_kvi']."</td>
					<td align='center'>".$row['current_kvi']."</td>
					<td align='center'>".(($row['current_kvi']/$row['target_kvi'])*100)."%</td>	
					<td><a href='./editgoal.php?goalid=".$row['goal_id']."&goalname=".$row['goal_name']."&goalstart=".$row['goal_start']."&targetkvi=".$row['target_kvi']."&currentkvi=".$row['current_kvi']."'>Edit</a></td>
					<td><a href='./deletegoal.php?goalid=".$row['goal_id']."'>Delete</a></td>
				</tr>\n";
			}	
		}
				
		else 
			echo "You don't have any goals. Click Add Goals to start.";
		
		echo "</table>";
		echo "<td><br /><a href='./addgoal.php'>Add a Goal</a><br /><br /><br /></td>";
		
		echo "<td><b>Directions:</b><br /></td>";
		echo "I used the concept of KVI (key-value indicators) to create this goal-tracking system. 
			You start with a goal and assign it a KVI value (like 50 or 100). You then create 
			Milestones that each have a KVI value also...The KVIs of all Milestones should add up to 
			the KVI of the parent goal. Broken down even further, each Milestone is broken down into 
			Progress logs that have KVI values. Each time you complete a progress (which would be a single action), 
			you add a progress log and assign a KVI value to it.";
		
		mysql_close();
	}
		
	?>

</body>

</html>