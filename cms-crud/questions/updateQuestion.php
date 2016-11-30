<?php
	// Modules.
	require("../../php-utility/connect.php");
	require("../../php-utility/getByID.php");
	require("../../php-utility/exists.php");
	require("../../php-utility/staffFlag.php");
	session_start();


	// Sanitize input.
	$sanitizedQuestionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
	$sanitizedCategoryID = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
	$sanitizedTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	var_dump($_POST);

	// Validate input.
	if(!$sanitizedQuestionId)
	{
		header('Location: ../../index.php');
	}

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

	if(strlen($sanitizedTitle) == 0 || strlen($sanitizedContent) == 0)
	{
		header('Location: ./editQuestion.php?requiredFieldsError');
	}
	else if(!categoryExists($sanitizedCategoryID))
	{
		header('Location: ./editQuestion.php?categoryDoesNotExistError');
	}
	else
	{
		// Input is valid.
		$insertQuery = 'UPDATE questions
										SET Title = :title,
										    CategoryID = :category,
												Content = :content,
												DateModified = CURRENT_TIMESTAMP
										WHERE QuestionID = :questionid';
		$statement = $db->prepare($insertQuery); 
		$statement->bindValue(':questionid', $sanitizedQuestionId);
		$statement->bindValue(':category', $sanitizedCategoryID);      
		$statement->bindValue(':title', $sanitizedTitle);   
		$statement->bindValue(':content', $sanitizedContent);   

		$statement->execute();


		header('Location: question.php?id=' . $sanitizedQuestionId);
	}
?>

