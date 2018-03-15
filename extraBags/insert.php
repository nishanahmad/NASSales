<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$arId = $_POST['ar'];
	$qty = $_POST['qty'];
	$remarks = $_POST['remarks'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	


	$sql="INSERT INTO extra_bags (date, ar_id, qty, remarks,entered_by,entered_on)
		  VALUES
		  ('$sqlDate', '$arId', '$qty', '$remarks', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: new.php" );

	mysqli_close($con);
}
else
{
	header( "Location: ../index.php" );
}	
?> 