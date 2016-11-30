<?php
	if(!defined('DB_DSN'))
	{
		define('DB_DSN','mysql:host=localhost;dbname=webd2_finalproj;charset=utf8');
	}

	if(!defined('DB_USER'))
	{
		define('DB_USER','serveruser');
	}

	if(!defined('DB_PASS'))
	{
		define('DB_PASS','gorgonzola7!'); 
	}

  // Create a PDO object called $db.
  $db = new PDO(DB_DSN, DB_USER, DB_PASS); 
?>
