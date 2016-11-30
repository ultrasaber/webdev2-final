<?php
	require('../php-utility/connect.php');
	require('verifyAdmin.php');

	$sanitizedId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
	$sanitizedPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedConfirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$resultMessage = "";

	if(!$sanitizedId)
	{
		$resultMessage = "Received ID did not validate as an integer.";
		header("Location: adminIndex.php?error=1");
	}
	else if(strlen($sanitizedPassword) < 1)
	{
		$resultMessage = "Password fields cannot be empty.";
		header("Location: changePassword.php?id=" . $sanitizedId . "&error=1");
	}
	else if($sanitizedPassword != $sanitizedConfirmPassword)
	{
		$resultMessage = "The password and confirm password must be the same.";
		header("Location: changePassword.php?id=" . $sanitizedId . "&error=2");
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
			$userQuery = "UPDATE users 
										SET Password = :password
										WHERE UserID = :id";
			$statement = $db->prepare($userQuery);   
			$statement->bindValue(':id', $sanitizedId);		
			$statement->bindValue(':password', password_hash($sanitizedPassword, PASSWORD_DEFAULT)); // Hashed password. use password_verify to authenticate

			$statement->execute();				

			$resultMessage = "Password update successful!";
			header("Location: adminIndex.php");
		}
	}
?>
<p><?=$resultMessage?></p>

