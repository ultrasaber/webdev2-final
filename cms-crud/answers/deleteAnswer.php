<?php
	require('../../php-utility/connect.php');
	require('../../php-utility/exists.php');
	require('../../logins/verifyAdmin.php');

	$sanitizedId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	$sanitizedQuestionId = filter_input(INPUT_GET, 'questionId', FILTER_VALIDATE_INT);

	if(!$sanitizedId)
	{
		header("Location: ../questions/question.php?id=" . $sanitizedQuestionId . "&invalidAnswerId");
	}
	else
	{
		if(answerExists($sanitizedId))
		{
			$userQuery = "DELETE FROM answers 
										WHERE AnswerID = :id";
			$statement = $db->prepare($userQuery);   
			$statement->bindValue(':id', $sanitizedId);					

			$statement->execute();				

			header("Location: ../questions/question.php?id=" . $sanitizedQuestionId);
		}
		else
		{
			header("Location: ../questions/question.php?id=" . $sanitizedQuestionId . "&invalidAnswerId");
		}
	}
?>
