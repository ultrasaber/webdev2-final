<?php
	function staffFlag()
	{
		if(isset($_SESSION['IsStaff']))
		{
			$staffFlag = $_SESSION['IsStaff'];
		}
		else
		{
			$staffFlag = false;
		}

		return $staffFlag;
	}
?>
