<?php
	require('../php-utility/connect.php');
	require('verifyAdmin.php');

	// Get user list.
	$hashQuery = "SELECT UserID, Username, Password, IsStaff 
								FROM users";
	$statement = $db->prepare($hashQuery);   

	$statement->execute();

	$userData = $statement->fetchAll();

	if(isset($_GET['error']))
	{
		if($_GET['error'] == 1)
		{
			$errorMessage = "Received ID did not validate as an integer.";
		}
		else if($_GET['error'] == 2)
		{
			$errorMessage = "The user specified could not be found.";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>User Index</title>
		<link rel="stylesheet" type="text/css" href="../css/pure-min.css"/>
		<link rel="stylesheet" type="text/css" href="../css/pure-override.css"/>
	</head>
	<body>
		<h1>User Index</h1>
		<p><a href="../index.php">Home</a></p>
		<?php if(isset($errorMessage)): ?>
			<p><?=$errorMessage?></p>
		<?php endif ?>
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Role</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($userData as $user): ?>
					<tr>
						<td><?=$user['UserID']?></td>
						<td><?=$user['Username']?></td>
						<td><?=($user['IsStaff']) ? 'Administrator' : 'User'?></td>
						<td><a href="toggleAdmin.php?id=<?=$user['UserID']?>">Toggle Admin</a> | <a href="changePassword.php?id=<?=$user['UserID']?>">Change Password</a> | <a href="deleteUser.php?id=<?=$user['UserID']?>">Delete User</a></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</body>
</html>
