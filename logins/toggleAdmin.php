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
		// Get user's current admin status
		$userQuery = "SELECT IsStaff 
									FROM users
									WHERE UserID = :id";
		$statement = $db->prepare($userQuery);   
		$statement->bindValue(':id', $sanitizedId);					

		$statement->execute();

		$userData = $statement->fetchAll();	

		var_dump($userData);

		if($statement->rowCount() == 0)
		{
			$resultMessage = "The user specified could not be found.";
			header("Location: adminIndex.php?error=2");
		}
		else
		{
			if($userData[0]['IsStaff'])
			{
				$userQuery = "UPDATE users 
											SET IsStaff = b'0'
											WHERE UserID = :id";
				$statement = $db->prepare($userQuery);   
				$statement->bindValue(':id', $sanitizedId);					

				$statement->execute();				
			}
			else
			{
				$userQuery = "UPDATE users 
											SET IsStaff = b'1'
											WHERE UserID = :id";
				$statement = $db->prepare($userQuery);   
				$statement->bindValue(':id', $sanitizedId);					

				$statement->execute();		
			}
			$resultMessage = "Update successful!";
			header("Location: adminIndex.php");
		}
	}
?>
<p><?=$resultMessage?></p>
