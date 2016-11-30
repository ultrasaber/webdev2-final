<?php
	require("../php-utility/connect.php");

	$createResult = "";

	// Sanitize user input.
	$sanitizedName = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedConfirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Validate user input.
	if(strlen($sanitizedName) < 1 || strlen($sanitizedPassword) < 1)
	{
		$createResult = "Username and password fields cannot be empty.";
		header("Location: register.php?error=1");
	}
	else if($sanitizedPassword != $sanitizedConfirmPassword)
	{
		$createResult = "The password and confirm password must be the same.";
		header("Location: register.php?error=2");
	}
	else
	{
		// Add user to the database.
		// Build the parameterized SQL query and bind to the above sanitized values.
		$query     = "INSERT INTO users (Username, Password) values (:username, :password)";
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $sanitizedName);        
		$statement->bindValue(':password', password_hash($sanitizedPassword, PASSWORD_DEFAULT)); // Hashed password. use password_verify to authenticate
    
		// Execute the INSERT.
		$statement->execute();

		$createResult = "Registration successful!";
		header("Location: login.php?error=3");
	}
?>
<p><?=$createResult?></p>
