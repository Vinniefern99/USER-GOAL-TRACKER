<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->data['page_title']; ?></title>
</head>
<body>
	<form action="/slim-rest-api/index.php/user/<?php echo $this->data['user']; ?>/visits" method="get">
		<input type="submit" value="Back">
	</form>
	<br />
	<br />
	<table>
		<tr>
			<th><?php echo $this->data['text_to_display']; ?></th>
		</tr>
	</table>
</body>
</html>