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
	$points = $_POST['points'];
	$remarks = $_POST['remarks'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	

	var_dump($_POST);
	
 	$insert ="INSERT INTO redemption (date, ar_id, points, remarks,entered_by,entered_on)
		 VALUES
		 ('$sqlDate', '$arId', '$points' , '$remarks', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $insert) or die(mysqli_error($con));				 

	header( "Location: new.php?success" ); 
}
else
{
	header( "Location: ../index.php" );
}	
?> 