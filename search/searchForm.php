<?php
	require("../php-utility/connect.php");
	session_start();

	$categoryQuery = "SELECT CategoryID, Description
										FROM categories";
	$statement = $db->prepare($categoryQuery); 

	$statement->execute();

	$categoryList = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Search questions</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>Search questions</h1>
		<p><a href="../index.php">Home</a></p>
		<form class="pure-form pure-form-aligned" method="post" action="searchResults.php">
			<fieldset>
				<legend>Search for a question</legend>
				<?php if(isset($errorMessage)): ?>
					<p><?=$errorMessage?></p>
				<?php endif?>
				<div class="pure-control-group">
						<label for="terms">Search terms</label>
						<input id="terms" name="terms" type="text" />
				</div>
				<div class="pure-control-group">
						<label for="category">Category (optional)</label>
						<select id="category" name="category">
							<option value="undefined" selected>All categories</option>
							<?php foreach($categoryList as $category): ?>
								<option value="<?=$category['CategoryID']?>"><?=$category['Description']?></option>
							<?php endforeach ?>
						</select>
				</div>

				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Search</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>

