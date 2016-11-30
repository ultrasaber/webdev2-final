<?php
	session_start();
	require('../../php-utility/connect.php');
	require('../../php-utility/exists.php');
	require('../../php-utility/getByID.php');
	require('../../php-utility/staffFlag.php');

	// Sanitize input.
	$sanitizedQuestionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if(!$sanitizedQuestionId)
	{
		header('Location: ../../index.php');
	}

	// Validate input.
	if(!questionExists($sanitizedQuestionId))
	{
		header('Location: ../../index.php');
	}

	// Get question via QuestionID.
	$question = getQuestionByID($sanitizedQuestionId);

	// Check if current user is the author of that question, or is a staff member.
	if($_SESSION['UserID'] === $question['AuthorID'] || staffFlag())
	{
		// If the above conditions are met, delete the question.
		unlink('uploads/' . $question['ImageLocation']);

		$query = 'DELETE FROM questions
							WHERE QuestionID = :questionid';
		$statement = $db->prepare($query);   
		$statement->bindValue(':questionid', $sanitizedQuestionId);		

		$statement->execute();		

		header('Location: questionIndex.php');
	}
	else
	{
		header('Location: question.php?id=' . $sanitizedQuestionId);
	}


?>

