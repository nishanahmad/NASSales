<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$sqlDate = date("Y-m-d");
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$area = $_POST['area'];
	$qty = (int)$_POST['qty'];

	$sql="INSERT INTO sheets (date, name, phone, qty, area)
		 VALUES
		 ('$sqlDate', '$name', '$phone', $qty, '$area')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: new.php" );

}
else
	header( "Location: ../index.php" );
?> 