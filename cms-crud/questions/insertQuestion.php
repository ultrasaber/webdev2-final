<?php
	// Modules.
	require("../../php-utility/connect.php");
	require("../../php-utility/exists.php");
	session_start();

  function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') 
	{
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
  }

  function file_is_allowed($temporary_path, $new_path) 
	{
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
    $actual_file_extension   = strtolower(pathinfo($new_path, PATHINFO_EXTENSION));
    $actual_mime_type        = $_FILES['image']['type'];
        
    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
	}

	// Sanitize input.
	$sanitizedCategoryID = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
	$sanitizedTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Validate input.
	if(strlen($sanitizedTitle) == 0 || strlen($sanitizedContent) == 0)
	{
		header('Location: ./createQuestion.php?requiredFieldsError');
	}
	else if(!categoryExists($sanitizedCategoryID))
	{
		
		header('Location: ./createQuestion.php?categoryDoesNotExistError');
	}
	else
	{
		$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

		if ($image_upload_detected) 
		{
			$image_filename       = $_FILES['image']['name'];
			$temporary_image_path = $_FILES['image']['tmp_name'];
			$new_image_path       = file_upload_path($image_filename);

			$pathInfo = pathinfo($new_image_path);

			$dbFilename = $pathInfo['filename'];

			var_dump($new_image_path);
		}
		else
		{
			$dbFilename = null;
		}

		if($image_upload_detected)
		{
			if(!file_is_allowed($temporary_image_path, $new_image_path))
			{
				header('Location: ./createQuestion.php?invalidFileError');
				die();
			}
		}

		// Input is valid.
		$insertQuery = 'INSERT INTO questions (CategoryID, AuthorID, Title, Content)
											VALUES (:category, :authorid, :title, :content)';
		$statement = $db->prepare($insertQuery); 
		$statement->bindValue(':category', $sanitizedCategoryID);     
		$statement->bindValue(':authorid', $_SESSION['UserID']);   
		$statement->bindValue(':title', $sanitizedTitle);   
		$statement->bindValue(':content', $sanitizedContent);   

		$statement->execute();

		$insert_id = $db->lastInsertId();

		if($image_upload_detected)
		{
			if(file_is_allowed($temporary_image_path, $new_image_path))
			{
				$segments = join(DIRECTORY_SEPARATOR, [$pathInfo['dirname'], $pathInfo['filename'] . '_' . $insert_id . '.' . $pathInfo['extension']]);

				move_uploaded_file($temporary_image_path, $segments);

				$path = $pathInfo['filename'] . '_' . $insert_id . '.' . $pathInfo['extension'];

				$updateQuery = 'UPDATE questions
												SET ImageLocation = :imagepath
											  WHERE QuestionID = :insertid';
				$statement = $db->prepare($updateQuery); 
				$statement->bindValue(':imagepath', $path);   
				$statement->bindValue(':insertid', $insert_id);

				$statement->execute();
			}
		}

		header('Location: question.php?id=' . $insert_id);
	}
?>
