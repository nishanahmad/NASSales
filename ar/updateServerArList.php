<?php
require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{	
	$updateMobile = "UPDATE ar_details SET mobile =(CASE ar_name ";
	$updatearea = "UPDATE ar_details SET area =(CASE ar_name ";
	$updatestatus = "UPDATE ar_details SET isActive =(CASE ar_name ";
	foreach($_POST as $key => $value)
	{
		$arr = explode("-",$key);
		$arName = str_replace('_',' ',$arr[0]);

 		if($arr[1] == 'mobile')
			$updateMobile = $updateMobile."WHEN '$arName' THEN '$value' ";

		else if($arr[1] == 'area')	
			$updatearea = $updatearea."WHEN '$arName' THEN '$value' ";
		else if($arr[1] == 'status')	
			$updatestatus = $updatestatus."WHEN '$arName' THEN '$value' ";
	}
	$updateMobile = $updateMobile."END)";
	$updatearea = $updatearea."END)";
	$updatestatus = $updatestatus."END)";
    
	$result1 = mysqli_query($con, $updateMobile) or die(mysqli_error($con));				   	
	$result2 = mysqli_query($con, $updatearea) or die(mysqli_error($con));				   	
	$result3 = mysqli_query($con, $updatestatus) or die(mysqli_error($con));				   	
	header( "Location: ar_detailList.php?message" );
}
else
	header( "Location: ../index.php" );
?>