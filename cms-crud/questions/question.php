<?php
	session_start();
	require('../../php-utility/connect.php');
	require('../../php-utility/exists.php');
	require('../../php-utility/getByID.php');
	require('../../php-utility/staffFlag.php');

	$loggedInUserID = (isset($_SESSION['UserID'])) ? $_SESSION['UserID'] : false;

	$sanitizedQuestionID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if(!$sanitizedQuestionID || !questionExists($sanitizedQuestionID))
	{
		header('Location: ../../index.php');
	}

	$question = getQuestionByID($sanitizedQuestionID);

	$query = 'SELECT *
						FROM answers
						WHERE QuestionID = :questionid
						ORDER BY AnswerID DESC'; // TODO: implement timestamps on answers.
	$statement = $db->prepare($query); 
	$statement->bindValue(':questionid', $sanitizedQuestionID);
	$statement->execute();

	$answers = $statement->fetchAll();	

	if(isset($_GET['invalidAnswerId']))
	{
		$errorMessage = 'Invalid answer ID.';
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$question['Title']?></title>
		<link rel="stylesheet" type="text/css" href="../../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/pure-override.css"/>
	</head>
	<body>
		<h1>(<?=($question['IsSolved']) ? 'Solved' : 'Unsolved'?>) <?=$question['Title']?></h1>
		<p>Category: <?=getCategoryByID($question['CategoryID'])['Description']?></p>
		<p>Created by <?=getUserByID($question['AuthorID'])['Username']?> on <?=$question['DateCreated']?>, last updated on <?=$question['DateModified']?></p>
		<p><a href="../../index.php">Home</a></p>
		<p><a href="questionIndex.php">Back to question index</a></p>
		<p>Rating: <?=$question['Rating']?> ( <a href="#">Vote up</a> | <a href="#">Vote down</a> )</p>
		<?php if($loggedInUserID == $question['AuthorID'] || staffFlag()): ?>
			<p>Administrative options:</p>
			<ul>
				<li><a href="#">Mark as Solved</a></li>
				<li><a href="editQuestion.php?id=<?=$question['QuestionID']?>">Edit this question</a></li>
				<li><a href="deleteImage.php?id=<?=$question['QuestionID']?>">Delete image</a></li>
				<li><a href="deleteQuestion.php?id=<?=$question['QuestionID']?>">Delete this question</a></li>
			</ul>
		<?php endif ?>
		<pre><?=$question['Content']?></pre>
		<?php if($question['ImageLocation'] != null):?>
			<p>Attached image:</p>
			<img src="uploads/<?=$question['ImageLocation']?>" alt="Attached image."/>
		<?php endif ?>
		<hr />
		<?php if(isset($_SESSION['UserID'])): ?>
			<form class="pure-form pure-form-aligned" method="post" action="../answers/postAnswer.php">
				<input type="hidden" id="questionID" name="questionID" value="<?=$question['QuestionID']?>"/>
				<input type="hidden" id="authorID" name="authorID" value="<?=$_SESSION['UserID']?>"/>
				<fieldset>
					<div class="pure-control-group">
						<label for="answerContent">Answer this question:</label>
						<textarea id="answerContent" name="answerContent"></textarea>
					</div>
					<div class="pure-controls">
						<button class="pure-button pure-button-primary" type="submit">Submit answer</button>
					</div>
				</fieldset>
			</form>
		<?php endif ?>
		<p>Answers:</p>
		<?php if(isset($errorMessage)): ?>
			<p><?=$errorMessage?></p>
		<?php endif?>
		<ul>
			<?php foreach($answers as $answer): ?>
				<li>
					<p><?=getUserByID($answer['AuthorID'])['Username']?> | Rating: <?=$answer['Rating']?> ( <a href="#">Vote up</a> | <a href="#">Vote down</a> )</p>
					<pre><?=$answer['Content']?></pre>
					<?php if(staffFlag()): ?>
						<p>Moderation: <a href="../answers/deleteAnswer.php?id=<?=$answer['AnswerID']?>&questionId=<?=$sanitizedQuestionID?>">Delete this answer</a></p>
					<?php endif ?>
				</li>
			<?php endforeach ?>
		</ul>
	</body>
</html>
