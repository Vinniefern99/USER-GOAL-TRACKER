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
	<title>Member System - Forgot Password</title>
</head>
<body>
	<?php 
	if (!$username && !$userid){
		if ($_POST['resetbtn']) {
			//get the form data
			$user = $_POST['user'];
			$email = $_POST['email'];
			
			//make sure info provided
			if ($user) {
				if ($email) {
					if ((strlen($email) >= 7) && (strstr($email,"@")) && (strstr($getemail,".")) ){
						// connect
						require("./connect.php");
						
						$query = mysql_query("SELECT * FROM users WHERE username = '$user'");
						$numrows = mysql_num_rows($query);
						if ($numrows == 1) {
							// get info about account
							$row = mysql_fetch_assoc($query);
							$dbemail = $row['email'];
							
							//make sure the email is correct
							if ($email == $dbemail) {
								// generate password
								$pass = rand();
								$pass = md5($pass);
								$pass = substr($pass, 1, 15);
								$password = md5(md5("hg5sefg".$password."h43uihsd"));
								
								//update db with new password
								mysql_query("UPDATE users SET password = '$password' WHERE username = '$user'");
								
								//make sure the passwrod was changed
								$query = mysql_query("SELECT * FROM users WHERE username = '$user' AND password = '$password'");
								$numrows = mysql_num_rows($query);
								if ($numrows ==1) {
									//create email vars
									$webmaster = "vincefernald <admin@vincefernald.com>";
									$headers = "From: $webmaster";
									$subject = "Your new Password";
									$message = "Hello. Your password has been reset. Your new password is below.\n";
									$message .= "Password: $pass\n";
									
									if ( mail($email, $subject, $message, $headers)) {
										echo "Your password has been reset. An email has been sent with your new password.";
									}
									else
										echo "An error has occured and your email was not sent containing your new password.";
								}
								else 
									echo "An error has occured and the password was not reset.";
								
							}
							else 
								echo "You have entered the wrong email address";
						}
						else
							echo "The username was not found.";
						
						mysql_close();
					}
					else 
						echo "Please enter a valid email address.";
				}
				else 
					echo "Please enter your email.";
			}
			else
				echo "Please enter your username.";
		}
		
		echo "<form action='./forgotpass.php' method='post'>
		<table>
		<tr>
			<td>Username:</td>
			<td><input type='text' name='user'/></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type='text' name='email'/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='resetbtn' value='Reset Password'/></td>
		</tr>
		</table>
		</form>";
	}
	else
		echo "Please logoug to veiw this page.";
		


	?>
</body>
</html>