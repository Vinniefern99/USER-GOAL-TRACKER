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
		</table>
		<tr>
			<td><br /><b>Goals:</b> </td>
			<td><br />(Click a goal to manage milestones and progress)</td>
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
				<td><b>% Complete</b></td>
			</tr>\n";
			
			
			while($row = mysql_fetch_assoc($query)) {	
				echo "
				<tr>
					<td><a href='./managegoal.php?goalid=".$row['goal_id']."'>".$row['goal_name']."</a></td>
					<td>".$row['goal_start']."</td>
					<td align='center'>".(($row['current_kvi']/$row['target_kvi'])*100)."%</td>	
				</tr>\n";
			}	
		}
				
		else 
			echo "You don't have any goals. Click Add Goals to start.";
		
		echo "</table>";
		echo "<td><br /><a href='./addgoal.php'>Add a Goal</a></td>";
		
		mysql_close();
	}
		
	?>

</body>

</html>