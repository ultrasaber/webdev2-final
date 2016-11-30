<?php
	require('../php-utility/connect.php');
	require('../php-utility/exists.php');
	require('../php-utility/getByID.php');

	// sanitize input
	$sanitizedSearchTerms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sanitizedCategory = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
	$sanitizedPageNumber = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

	$pageNumber = ($sanitizedPageNumber) ? $sanitizedPageNumber : 1;
	var_dump($pageNumber);


	// validate input
	if(!$sanitizedSearchTerms)
	{
		header('Location: searchForm.php');
	}

	// construct query
	if(!$sanitizedCategory || !categoryExists($sanitizedCategory))
	{
		$query = "SELECT *
							FROM questions
							WHERE Title LIKE CONCAT('%', :terms, '%')";

		$statement = $db->prepare($query); 
		$statement->bindValue(':terms', $sanitizedSearchTerms);
	}
	else
	{
		$query = "SELECT *
							FROM questions
							WHERE Title LIKE CONCAT('%', :terms, '%')
							AND CategoryID = :category";

		$statement = $db->prepare($query); 
		$statement->bindValue(':terms', $sanitizedSearchTerms);
		$statement->bindValue(':category', $sanitizedCategory);
	}

	$statement->execute();

	$results = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Search Results</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>Search Results</h1>
		<p><a href="../index.php">Home</a></p>
		<p><a href="searchForm.php">Back to search form</a></p>
		<hr />
		<ul>
			<?php foreach($results as $question):?>
				<li>
					<p>(<?=$question['Rating']?>) (<?=getCategoryByID($question['CategoryID'])['Description']?>) (<?=($question['IsSolved']) ? 'Solved' : 'Unsolved' ?>) <a href="question.php?id=<?=$question['QuestionID']?>"><?=$question['Title']?></a></p>
					<p>Created by <?=getUserByID($question['AuthorID'])['Username']?> on <?=$question['DateCreated']?>, last updated on <?=$question['DateModified']?></p>
				</li>
			<?php endforeach ?>
		</ul>
	</body>
</html>
