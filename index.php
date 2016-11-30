<?php
	session_start();

	require('php-utility/staffFlag.php');

	$name = (isset($_SESSION['Username'])) ? $_SESSION['Username'] : 'Guest';
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Material Workshop Community Help Centre</title>
		<link rel="stylesheet" type="text/css" href="css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="css/pure-override.css"/>
	</head>
	<body>
		<h1>Material Workshop Community Help Centre</h1>
		<?php if(isset($_GET['accessDenied'])): ?>
			<p>Access Denied: Not an administrator.</p>
		<?php endif ?>
		<p>Hello, <?=$name?>!<?php if(isset($_SESSION['UserID'])): ?> (<a href="logins/logout.php">Logout</a>)<?php endif?></p>
		<ul>
			<?php if(!isset($_SESSION['UserID'])): ?>
				<li><a href="./logins/login.php">Log in</a></li>
				<li><a href="./logins/register.php">Register</a></li>
			<?php endif ?>
			<?php if(isset($_SESSION['UserID'])): ?>
				<li><a href="./cms-crud/questions/createQuestion.php">Ask a new question</a></li>
			<?php endif ?>
				<li><a href="./cms-crud/questions/questionIndex.php">Browse questions</a></li>
				<li><a href="./search/searchForm.php">Search questions</a></li>
			<?php if(staffFlag()): ?>
				<li><a href="./logins/adminIndex.php">Manage users</a></li>
				<li><a href="./cms-crud/categories/manageCategories.php">Manage categories</a></li>
			<?php endif ?>
		</ul>
	</body>
</html>
