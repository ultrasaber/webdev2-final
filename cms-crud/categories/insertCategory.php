<?php
	require('../../php-utility/connect.php');
	require('../../logins/verifyAdmin.php');

	var_dump($_POST);

	$sanitizedCategoryName = filter_input(INPUT_POST, "categoryName", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if(!$sanitizedCategoryName)
	{
		header('Location: ./manageCategories.php?validationFailed');
	}
	else if(strlen($sanitizedCategoryName) == 0)
	{	
		header('Location: ./manageCategories.php?emptyCategoryName');
	}
	else
	{
		$categoryQuery = 'INSERT INTO categories (Description)
											VALUES (:categoryName)';
		$statement = $db->prepare($categoryQuery); 
		$statement->bindValue(':categoryName', $sanitizedCategoryName);     

		$statement->execute();

		header('Location: manageCategories.php');
	}
?>
