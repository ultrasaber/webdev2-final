<?php
	require('../../php-utility/connect.php');
	require('../../php-utility/getByID.php');

	define('SORT_BY_TITLE', 0);
	define('SORT_BY_CREATION_TIME', 1);
	define('SORT_BY_UPDATE_TIME', 2);

	function getSortSetting()
	{
		return (isset($_GET['sortSetting'])) ? $_GET['sortSetting'] : SORT_BY_CREATION_TIME;
	}

	function getAscendingDescending()
	{
		return (isset($_GET['ascending'])) ? $_GET['ascending'] : 0;
	}

	function getCategorySetting()
	{
		return (isset($_GET['category'])) ? '&category=' . $_GET['category'] : '';
	}

	function getQueryableSortSettings()
	{
		$column = "";

		if(getSortSetting() == SORT_BY_TITLE)
		{
			$column = "Title";
		}
		else if(getSortSetting() == SORT_BY_CREATION_TIME)
		{
			$column = "DateCreated";
		}
		else if(getSortSetting() == SORT_BY_UPDATE_TIME)
		{
			$column = "DateModified";
		}

		return $column . ((getAscendingDescending() == 1) ? ' ASC' : ' DESC');
	}

	if(isset($_GET['category']))
	{
		$sanitizedCategoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);

		if(!$sanitizedCategoryId)
		{
			header('Location: ../../index.php');
		}
	}

	if(isset($_GET['category']))
	{
		$query = 'SELECT *
							FROM questions
							WHERE CategoryID = :categoryid
							ORDER BY ' . getQueryableSortSettings();
	}
	else
	{
		$query = 'SELECT *
							FROM questions
							ORDER BY ' . getQueryableSortSettings();
	}

	$statement = $db->prepare($query);   
	// Oddly enough, binding entire clauses doesn't really work in this context. Pray that this is passable, since no real user data is entered on this page.
	//$statement->bindValue(':sortsetting', getQueryableSortSettings());

	if(isset($sanitizedCategoryId))
	{
		$statement->bindValue(':categoryid', $sanitizedCategoryId);
	}

	$statement->execute();

	$questions = $statement->fetchAll();


	$categoryQuery = "SELECT *
										FROM categories";
	$statement = $db->prepare($categoryQuery);   

	$statement->execute();

	$categoryData = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Question Index</title>
		<link rel="stylesheet" type="text/css" href="../../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/pure-override.css"/>
	</head>
	<body>
		<h1>Question Index</h1>
		<p><a href="../../index.php">Home</a></p>
		<p>Sort options:</p>
		<ul>
			<li><a href="./questionIndex.php?sortSetting=0&ascending=<?=getAscendingDescending()?><?=getCategorySetting()?>">Sort by title</a></li>
			<li><a href="./questionIndex.php?sortSetting=1&ascending=<?=getAscendingDescending()?><?=getCategorySetting()?>">Sort by creation time</a></li>
			<li><a href="./questionIndex.php?sortSetting=2&ascending=<?=getAscendingDescending()?><?=getCategorySetting()?>">Sort by update time</a></li>
			<li><a href="./questionIndex.php?sortSetting=<?=getSortSetting()?>&ascending=0<?=getCategorySetting()?>">Sort descending</a></li>
			<li><a href="./questionIndex.php?sortSetting=<?=getSortSetting()?>&ascending=1<?=getCategorySetting()?>">Sort ascending</a></li>
		</ul>
		<p>Categories:</p>
		<ul>
			<li><a href="./questionIndex.php?sortSetting=<?=getSortSetting()?>&ascending=<?=getAscendingDescending()?>">All categories</a></li>
			<?php foreach($categoryData as $category): ?>
				<li><a href="./questionIndex.php?sortSetting=<?=getSortSetting()?>&ascending=<?=getAscendingDescending()?>&category=<?=$category['CategoryID']?>"><?=$category['Description']?></a></li>
			<?php endforeach ?>
		</ul>
		<hr />
		<ul>
			<?php foreach($questions as $question):?>
				<li>
					<p>(<?=$question['Rating']?>) (<?=getCategoryByID($question['CategoryID'])['Description']?>) (<?=($question['IsSolved']) ? 'Solved' : 'Unsolved' ?>) <a href="question.php?id=<?=$question['QuestionID']?>"><?=$question['Title']?></a></p>
					<p>Created by <?=getUserByID($question['AuthorID'])['Username']?> on <?=$question['DateCreated']?>, last updated on <?=$question['DateModified']?></p>
				</li>
			<?php endforeach ?>
		</ul>
	</body>
</html>
