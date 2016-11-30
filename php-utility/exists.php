<?php
	require('connect.php');

	function userExists($userID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM users
							WHERE UserID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $userID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $statement->rowCount() != 0;
	}

	function categoryExists($categoryID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS);

		$query = "SELECT *
							FROM categories
							WHERE CategoryID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $categoryID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $statement->rowCount() != 0;
	}

	function answerExists($answerID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM answers
							WHERE AnswerID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $answerID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $statement->rowCount() != 0;
	}

	function questionExists($questionID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM questions
							WHERE QuestionID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $questionID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $statement->rowCount() != 0;
	}
?>
