<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$fromDate = $_POST['fromDate'];
	$toDate = $_POST['toDate'];	

	foreach($_POST as $key => $value)
	{
		$arr = explode("-",$key);
		$arId = $arr[0];
		if($arr[1] == 'special_target')
		{
			$sql="UPDATE special_target SET special_target = '$value' WHERE ar_id = '$arId' AND fromDate = '$fromDate' AND toDate = '$toDate' ";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
		}
	}

	$lockAR ="UPDATE special_target_locker SET locked = 1 WHERE  from_Date = '$fromDate' AND to_Date = '$toDate' ";
	$LockARresult = mysqli_query($con, $lockAR) or die(mysqli_error($con));				   	
	
	header( "Location: ../index.php" );

	mysqli_close($con); 
}
else
{
	header( "Location: ../index.php" );
}	
