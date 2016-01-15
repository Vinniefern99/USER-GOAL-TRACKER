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
	
	if ($username && $userid) {
		echo "Welcome <b>$username</b>, <a href='./logout.php'>Logout</a>";
		
	}
	else
		echo "Please login to access this page. <a href='./login.php'>Login here</a>";



	?>

</body>

</html>