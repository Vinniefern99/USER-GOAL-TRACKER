<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->data['page_title']; ?></title>
</head>
<body>
	<br />
	<br />
	<table>
		<tr>
			<th>Select an Option:</th>
		</tr>
	</table>
	<form action="index.php/state" method="GET">
		Get a list of all <input type="submit" value="states">
	</form>

	<form action="index.php/user" method="GET">
		Get a list of all <input type="submit" value="users">
	</form>
</body>
</html>