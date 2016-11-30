<?php
	require("../../php-utility/connect.php");
	require("../../php-utility/getByID.php");
	require("../../php-utility/exists.php");
	require("../../php-utility/staffFlag.php");
	
	session_start();

	// sanitize user input.
	$sanitizedQuestionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if(!$sanitizedQuestionId)
	{
		header('Location: ../../index.php');
	}

	// validate user input.
	if(!questionExists($sanitizedQuestionId))
	{
		header('Location: ../../index.php');
	}

	// Get question.
	$question = getQuestionById($sanitizedQuestionId);

	// Determine if question can be edited.
	if(!($question['AuthorID'] == $_SESSION['UserID'] || staffFlag()))
	{
		header('Location: question.php?id=' . $question['QuestionID']);
	}

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
		<title>Edit question</title>
		<link rel="stylesheet" type="text/css" href="../../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/pure-override.css"/>
	</head>
	<body>
		<h1>Edit question</h1>
		<p><a href="../../index.php">Home</a></p>
		<form class="pure-form pure-form-aligned" method="post" action="updateQuestion.php" enctype="multipart/form-data">
			<fieldset>
				<legend>Edit question</legend>
				<?php if(isset($errorMessage)): ?>
					<p><?=$errorMessage?></p>
				<?php endif?>

				<input type="hidden" id="id" name="id" value="<?=$question['QuestionID']?>" />

				<div class="pure-control-group">
						<label for="title">Title</label>
						<input id="title" name="title" type="text" value="<?=$question['Title']?>"/>
				</div>

				<div class="pure-control-group">
						<label for="category">Category</label>
						<select id="category" name="category">
							<?php foreach($categoryList as $category): ?>
								<option value="<?=$category['CategoryID']?>" <?=(($question['CategoryID'] == $category['CategoryID']) ? "selected" : "")?>><?=$category['Description']?></option>
							<?php endforeach ?>
						</select>
				</div>

				<div class="pure-control-group">
						<label for="content">Content</label>
						<textarea id="content" name="content"/><?=$question['Content']?></textarea>
				</div>

				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Publish</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
