<?php
	session_start();
	require('../php-utility/connect.php');

	//var_dump($_POST);
	$loginMessage = "";

	// Sanitize user input.
	$sanitizedUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Get password hash for authentication.
	$hashQuery = "SELECT UserID, Username, Password, IsStaff 
								FROM users 
								WHERE Username =  :username";
	$statement = $db->prepare($hashQuery);
	$statement->bindValue(':username', $sanitizedUsername);        

	$statement->execute();

	$userData = $statement->fetchAll();

	// Authenticate user.
	if($statement->rowCount() == 0)
	{
		$loginMessage = "The user with the specified username does not exist.";
		session_destroy();

		header("Location: login.php?error=1");
	}
	else if(password_verify($sanitizedPassword, $userData[0]['Password']))
	{
		$loginMessage = "Login successful!";

		$_SESSION['UserID'] = $userData[0]['UserID'];
		$_SESSION['Username'] = $userData[0]['Username'];
		$_SESSION['IsStaff'] = $userData[0]['IsStaff'];

		header("Location: ../index.php");
	}
	else
	{
		$loginMessage = "Incorrect password.";
		session_destroy();

		header("Location: login.php?error=2");
	}
?>
<p><?=$loginMessage?></p>
