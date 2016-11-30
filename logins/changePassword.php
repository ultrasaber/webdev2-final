<?php
	require('../php-utility/connect.php');
	require('verifyAdmin.php');

	$sanitizedId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if(!$sanitizedId)
	{
		header('Location: ../index.php');
	}

	if(isset($_GET['error']))
	{
		if($_GET['error'] == 1)
		{
			$errorMessage = "Password fields cannot be empty.";
		}
		else if($_GET['error'] == 2)
		{
			$errorMessage = "The password and confirm password fields must be the same.";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Change Password</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>Change Password</h1>
		<p><a href="adminIndex.php">Return to User Index</a></p>
		<form class="pure-form pure-form-aligned" method="post" action="updatePassword.php">
			<fieldset>
				<?php if(isset($errorMessage)): ?>
					<p><?=$errorMessage?></p>
				<?php endif ?>
				<input type="hidden" id="id" name="id" value="<?=$sanitizedId?>">
				<div class="pure-control-group">
					<label for="password">New Password</label>
					<input id="password" name="password" type="password" />
				</div>
				<div class="pure-control-group">
					<label for="password">Confirm New Password</label>
					<input id="password" name="confirmPassword" type="password" />
				</div>
				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Change Password</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
