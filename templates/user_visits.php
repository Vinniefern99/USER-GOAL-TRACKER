<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->data['page_title']; ?></title>
</head>
<body>
	<form action="/slim-rest-api/index.php/user" method="get">
		<input type="submit" value="Back">
	</form>
	<br />
	<br />
	<table>
		<tr>
			<th align="left">Cities travelled to by <?php echo $this->data['full_name']; ?>:</th>
		</tr>
		<?php foreach ($this->data['visit_results_array'] as $item): ?>
		<tr>
			<td><?php echo $item['city'] . ", " . $item['state_abbreviation']?></td>
			<td>
				<form action="visit/<?php echo $item['visit_id']?>" method="post">
					<input type="hidden" name="visit_id"
						value="<?php echo $item['visit_id'] ?>"> <input type="submit"
						value="Click to Delete">
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<br />
	<table>
		<tr>
			<th align="left">
				<form action="visits" method="POST">
					Add the following city: <select name="city" id="city">
		    			<?php foreach ($this->data['city_results_array'] as $item): ?>
  						<option value="<?php echo $item['name'] ?>"><?php echo $item['name'] . ", " . $item['state']?></option>
  						<?php endforeach; ?>
					</select> Verify state: <select name="state" id="state">
		    			<?php foreach ($this->data['state_results_array'] as $item): ?>
  						<option value="<?php echo $item['abbreviation'] ?>"><?php echo $item['abbreviation']?></option>
  						<?php endforeach; ?>
					</select> <input type="submit" value="Submit" />

				</form>
			</th>
		</tr>
		<tr>
			<th align="left"><br />
				<form action="visits/states" method="GET">
					View a list of all States this user has visited by clicking <input
						type="submit" value="here" />
				</form></th>
		</tr>
	</table>
</body>
</html>