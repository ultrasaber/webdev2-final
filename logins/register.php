<?php
	if(isset($_GET['error']))
	{
		if($_GET['error'] == 1)
		{
			$errorMessage = "All fields are required.";
		}
		else if($_GET['error'] == 2)
		{
			$errorMessage = "The password and confirm password fields must match.";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>Register</h1>
		<p><a href="../index.php">Home</a></p>
		<form class="pure-form pure-form-aligned" method="post" action="createUser.php">
			<fieldset>
				<?php if(isset($errorMessage)): ?>
					<p><?=$errorMessage?></p>
				<?php endif ?>
				<div class="pure-control-group">
					<label for="username">Username</label>
					<input id="username" name="username" type="text" />
				</div>
				<div class="pure-control-group">
					<label for="password">Password</label>
					<input id="password" name="password" type="password" />
				</div>
				<div class="pure-control-group">
					<label for="confirmPassword">Confirm Password</label>
					<input id="confirmPassword" name="confirmPassword" type="password" />
				</div>
				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Register</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
