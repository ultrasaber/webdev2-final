<?php
	require('../../php-utility/connect.php');
	require('../../logins/verifyAdmin.php');

	// Get user list.
	$hashQuery = "SELECT *
								FROM categories";
	$statement = $db->prepare($hashQuery);   

	$statement->execute();

	$categoryData = $statement->fetchAll();

	if(isset($_GET['validationFailed']))
	{
		$errorMessage = 'Category name validation failed.';
	}
	else if(isset($_GET['emptyCategoryName']))
	{
		$errorMessage = 'Category name must be at least one character long.';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Category Manager</title>
		<link rel="stylesheet" type="text/css" href="../../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/pure-override.css"/>
	</head>
	<body>
		<h1>Category Manager</h1>
		<p><a href="../../index.php">Home</a></p>
		<?php if(isset($errorMessage)): ?>
			<p><?=$errorMessage?></p>
		<?php endif ?>
		<form class="pure-form" action="insertCategory.php" method="post">
			<fieldset>
				<div class="pure-control-group">
					<legend>Create a category</legend>
					<label for="categoryName">Description</label>
					<input type="text" id="categoryName" name="categoryName"/>
					<button class="pure-button pure-button-primary" type="submit">Create</button>
				</div>
			</fieldset>
		</form>
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($categoryData as $category): ?>
					<tr>
						<td><?=$category['CategoryID']?></td>
						<td><?=$category['Description']?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</body>
</html>
