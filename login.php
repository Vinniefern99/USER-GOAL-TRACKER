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
	<title>Member System - Login</title>
</head>
<body>
	<?php
	
	$form = "<form action='./login.php' method='post'>
	<table>
	<tr>
		<td>Username:</td>
		<td><input type='text' name='user' /></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type='password' name='password' /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type='submit' name='loginbtn' value='Login'/></td>
	</tr>
	</table>
	</form>";
	
	if ($_POST['loginbtn']) {
		$user = $_POST['user'];
		$password = $_POST['password'];
		
		if ($user) {
			if ($password) {
				require("connect.php");
				
				//double password encryption with SALT
				$password = md5(md5("hg5sefg".$password."h43uihsd"));
				// make sure login info correct
				$query = mysql_query("SELECT * FROM users WHERE username = '$user'");
				$numrows = mysql_num_rows($query);
				
				if ($numrows == 1) {
					$row = mysql_fetch_assoc($query);
					$dbid = $row['id'];
					$dbuser = $row['username'];
					$dbpass = $row['password'];
					$dbactive = $row['active'];
					
					
					if ($password == $dbpass) {
						if ($dbactive == 1) {
							//set session info
							$_SESSION['userid'] = dbid;
							$_SESSION['username'] = $dbuser;
							
							echo "You have been loggin in as <b>$dbuser</b>. <a href='./member.php'>Click here</a> to go to the member page.";
						}
						else 
							echo "You must activate your account to login. $form";
					}
					else 
						echo "You did not enter the correct password. $form";
				}
				else 
					echo "The username you entered was not found. $form";
				
				mysql_close();
				
				//echo "$user - $password <hr /> $form";
			}
			else 
				echo "You must enter your password. $form";
		}
		else 
			echo "You must enter your username. $form";
	}
	else 
		echo $form;

	
	?>
</body>
</html>