<?php
	require('../../php-utility/connect.php');
	require('../../php-utility/exists.php');

	$sanitizedQuestionID = filter_input(INPUT_POST, 'questionID', FILTER_VALIDATE_INT);
	$sanitizedAuthorID = filter_input(INPUT_POST, 'authorID', FILTER_VALIDATE_INT);
	$sanitizedContent = filter_input(INPUT_POST, 'answerContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	if(strlen($sanitizedContent) == 0)
	{
		header('Location: ../questions/question.php?id=' . $sanitizedQuestionID . '&emptyContentError');
	}
	else if(!userExists($sanitizedAuthorID))
	{
		header('Location: ../questions/question.php?id=' . $sanitizedQuestionID . '&userDoesNotExistError');
	}
	else if(!questionExists($sanitizedQuestionID))
	{
		header('Location: ../questions/question.php?id=' . $sanitizedQuestionID . '&questionDoesNotExistError');
	}
	else
	{
		$query     = "INSERT INTO answers (QuestionID, AuthorID, Content) values (:questionid, :authorid, :content)";
		$statement = $db->prepare($query);
		$statement->bindValue(':questionid', $sanitizedQuestionID);        
		$statement->bindValue(':authorid', $sanitizedAuthorID);
		$statement->bindValue(':content', $sanitizedContent);

		// Execute the INSERT.
		$statement->execute();

		// Update timestamp on the question.
		$query     = "UPDATE questions SET DateModified = CURRENT_TIMESTAMP WHERE QuestionID = :questionid";
		$statement = $db->prepare($query);
		$statement->bindValue(':questionid', $sanitizedQuestionID);   

		// Execute the INSERT.
		$statement->execute();

		header('Location: ../questions/question.php?id=' . $sanitizedQuestionID);
	}
  

?>
