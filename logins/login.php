<?php
	if(isset($_GET['error']))
	{
		if($_GET['error'] == 1)
		{
			$errorMessage = "The user specified does not exist.";
		}
		else if($_GET['error'] == 2)
		{
			$errorMessage = "Incorrect password.";
		}
		else if($_GET['error'] == 3)
		{
			$errorMessage = "Registration successful! Log in using the form below.";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Log In</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>Log In</h1>
		<p><a href="../index.php">Home</a></p>
		<form class="pure-form pure-form-aligned" method="post" action="authenticate.php">
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
				<div class="pure-controls">
					<button class="pure-button pure-button-primary" type="submit">Log In</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
