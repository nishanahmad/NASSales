<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$year = $_GET['year'];
	$month = $_GET['month'];
	$result = mysqli_query($con,"UPDATE lock_target SET locked='0' WHERE month='$month' AND year = '$year'") or die(mysqli_error($con));				 

	header("Location:lock_unlockPage.php?year=$year");
}
?>