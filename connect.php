<?php	
	$con=mysqli_connect("localhost","nishan","darussalam123.","nas");
	// Check connection
	if ($con->connect_error) 
	{
		die("Connection failed: " . $con->connect_error);
	}
?>