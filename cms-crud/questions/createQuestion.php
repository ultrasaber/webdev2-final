<?php
	require("../../php-utility/connect.php");
	session_start();

	$categoryQuery = "SELECT CategoryID, Description
										FROM categories";
	$statement = $db->prepare($categoryQuery); 

	$statement->execute();

	$categoryList = $statement->fetchAll();

	if(isset($_GET['requiredFieldsError']))
	{
		$errorMessage = 'Title and Content are required.';
	}
	else if(isset($_GET['categoryDoesNotExistError']))
	{
		$errorMessage = 'Provided category does not exist.';
	}
	else if(isset($_GET['invalidFileError']))
	{
		$errorMessage = 'Invalid file given.';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ask a new question</title>
		<link rel="stylesheet" type="text/css" href="../../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/pure-override.css"/>
	</head>
	<body>
		<h1>Ask a new question</h1>
		<p><a href="../../index.php">Home</a></p>
		<p>Asking as <?=$_SESSION['Username']?></p>
		<form class="pure-form pure-form-aligned" method="post" action="insertQuestion.php" enctype="multipart/form-data">
			<fieldset>
				<legend>New question</legend>
				<?php if(isset($errorMessage)): ?>
					<p><?=$errorMessage?></p>
				<?php endif?>
				<div class="pure-control-group">
						<label for="title">Title</label>
						<input id="title" name="title" type="text" />
				</div>
				<div class="pure-control-group">
						<label for="category">Category</label>
						<select id="category" name="category">
							<?php foreach($categoryList as $category): ?>
								<option value="<?=$category['CategoryID']?>"><?=$category['Description']?></option>
							<?php endforeach ?>
						</select>
				</div>
				<div class="pure-control-group">
						<label for="content">Content</label>
						<textarea id="content" name="content"/></textarea>
				</div>

				<div class="pure-control-group">
					<p>Additionally, you may upload an image to assist in describing your problem. GIFs, JPGs, and PNGs are supported.</p>
					<label for="image">Filename:</label>
					<input type="file" name="image" id="image"/>
				</div>

				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Publish question</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
