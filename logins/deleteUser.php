<?php
	require('../php-utility/connect.php');
	require('verifyAdmin.php');

	$sanitizedId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	$resultMessage = "";

	if(!$sanitizedId)
	{
		$resultMessage = "Received ID did not validate as an integer.";
		header("Location: adminIndex.php?error=1");
	}
	else
	{
		// Check if user exists
		$userQuery = "SELECT IsStaff 
									FROM users
									WHERE UserID = :id";
		$statement = $db->prepare($userQuery);   
		$statement->bindValue(':id', $sanitizedId);					

		$statement->execute();

		$userData = $statement->fetchAll();	

		if($statement->rowCount() == 0)
		{
			$resultMessage = "The user specified could not be found.";
			header("Location: adminIndex.php?error=2");
		}
		else
		{
			$userQuery = "DELETE FROM users 
										WHERE UserID = :id";
			$statement = $db->prepare($userQuery);   
			$statement->bindValue(':id', $sanitizedId);					

			$statement->execute();				

			$resultMessage = "Delete successful!";
			header("Location: adminIndex.php");
		}
	}
?>
<p><?=$resultMessage?></p>

