<?php
	session_start();
	$sanitizedId = filter_var($_SESSION['UserID'], FILTER_VALIDATE_INT);

	// Check if user exists
	$userQuery = "SELECT IsStaff 
								FROM users
								WHERE UserID = :id
								AND IsStaff = 1";
	$statement = $db->prepare($userQuery);   
	$statement->bindValue(':id', $sanitizedId);			

	$statement->execute();

	$userData = $statement->fetchAll();	

	if($statement->rowCount() == 0)
	{
		header("Location: ../index.php?accessDenied");
	}
?>
