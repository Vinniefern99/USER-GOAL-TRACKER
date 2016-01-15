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
			<th>State</th>
			<th>Click to view all cities:</th>
		</tr>
		<?php foreach ($this->data['state_results_array'] as $item): ?>
		<tr>
			<td><?php echo $item['name'] ?></td>
			<td>
				<form action="state/<?php echo $item['name'] ?>/cities" method="GET">
					<input type="submit" value="Cities">
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>