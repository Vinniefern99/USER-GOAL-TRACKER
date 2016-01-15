<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->data['page_title']; ?></title>
</head>
<body>
	<button onclick="history.go(-1);">Back</button>
	<br />
	<br />
	<table>
		<tr>
			<th><?php echo $this->data['text_to_display']; ?></th>
		</tr>
	</table>
</body>
</html>