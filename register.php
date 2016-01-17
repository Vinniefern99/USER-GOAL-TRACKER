<?php
error_reporting (E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Context-Type" content="text/html; charset=utf-8" />
	<title>Member System - Register</title>
</head>
<body>
<?php 

//checks for register buttons via post method
if ( $_POST['registerbtn']) {
	$getuser = $_POST['user'];
	$getemail = $_POST['email'];
	$getpass = $_POST['pass'];
	$getretypepass = $_POST['retypepass'];
	
	if($getuser) {
		if ($getemail){
			if ($getpass){
				if ($getretypepass){
					//three equal signs to make sure passwords have same caps
					if ($getpass === $getretypepass){
						//simple email validation
						if ( (strlen($getemail) >= 7) && (strstr($getemail,"@")) && (strstr($getemail,"."))){
							require ("./connect.php");
							
							$query = mysql_query("SELECT * FROM users WHERE username = '$getuser'");
							$numrows = mysql_num_rows($query);
							if ($numrows == 0) {
								$query = mysql_query("SELECT * FROM users WHERE email = '$getemail'");
								$numrows = mysql_num_rows($query);
								if ($numrows == 0) {
									
									$password = md5(md5("hg5sefg".$getpass."h43uihsd"));
									$date = date("F d, Y");
									$randcode = md5(rand());
									$code = substr($randcode, 1, 25);
									
									mysql_query("INSERT INTO users VALUES (
										'', '$getuser', '$password', '$getemail', '0', '$code', '$date'	
									)");
									
									$query = mysql_query("SELECT * FROM users WHERE username = '$getuser'");
									$numrows = mysql_num_rows($query);
									if ($numrows == 1) {
										
										// make sure $site is set to correct server location
										$site = "http://localhost/USER-GOAL-TRACKER";
										$webmaster = "vincefernald <admin@vincefernald.com>";
										$headers = "From: $webmaster";
										$subject = "Activate Your Account";
										$message = "Thanks for registering. Click the link below to activate your account.\n";
										$message .= "$site/activate.php?user=$getuser&code=$code\n";
										$message .= "You must activate your account to login.";
																			
										if (mail($getemail, $subject, $message, $headers)) {
											$errormsg = "You have been registered. You must activate your account from the activation link sent to <b>$getemail</b>.";
											$getuser = "";
											$getemail = "";
										}
										else 
											$errormsg = "An error has occured. Your activation email was not sent.";
									}
									else 
										$errormsg = "An error has occured. Your account was not created.";
								}
								else
									$errormsg = "There is already a user with that email.";
							}
							else 
								$errormsg = "There is already a user with that username.";
							
							mysql_close();
							
						}
						else
							$errormsg = "You must enter a valid email address to register.";
					}
					else
						$errormsg = "Your passwords did not match.";
				}
				else $errormsg = "You must retype your password to register.";
			}
			else 
				$errormsg = "You must enter your password to register.";
		}
		else
			$errormsg = "You must enter your email to register.";
	}
	else 
		$errormsg = "You must enter your username to register.";
}


//once the user has clicked register button, display form
//if confirm password is wrong, pre-fill in form so they don't have to re-enter username/email
$form = "<form action='./register.php' method='post'>
<table>
<tr>
	<td></td>
	<td><font color='red'>$errormsg</td>
</tr>
<tr>
	<td>Username:</td>
	<td><input type='text' name='user' value='$getuser'/></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input type='text' name='email' value='$getemail'/></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type='password' name='pass' value=''/></td>
</tr>
<tr>
	<td>Retype:</td>
	<td><input type='password' name='retypepass' value=''/></td>
</tr>
<tr>
	<td></td>
	<td><input type='submit' name='registerbtn' value='Register'/></td>
</tr>
</table>		
</form>";

echo $form;
	

?>
</body>
</html>