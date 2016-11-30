<?php
	require('connect.php');

	function getQuestionByID($questionID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM questions
							WHERE QuestionID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $questionID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $query[0];
	}

	function getCategoryByID($categoryID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM categories
							WHERE CategoryID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $categoryID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $query[0];
	}

	function getUserByID($userID)
	{
		$db = new PDO(DB_DSN, DB_USER, DB_PASS); 

		$query = "SELECT *
							FROM users
							WHERE UserID = :id";
		$statement = $db->prepare($query);   
		$statement->bindValue(':id', $userID);					

		$statement->execute();

		$query = $statement->fetchAll();	

		return $query[0];
	}
?>
