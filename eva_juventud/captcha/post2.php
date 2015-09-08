	<?php
	session_start();
	if(@strtolower($_REQUEST['code2']) == strtolower($_SESSION['random_number']))
	{
		// insert your name , email and text message to your table in db
		echo 1;// submitted 
	}
	else
	{
		echo 0; // invalid code
	}
	?>
