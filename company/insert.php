<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	var_dump($_POST);
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$arCode = $_POST['ar'];
	$arObject = $result = mysqli_query($con, "SELECT ar_name FROM ar_details WHERE sap_code = '$arCode'") or die(mysqli_error($con));
	foreach($arObject as $ar)
		$arName = $ar['ar_name'];
	
	if($_POST['srp'] != '')
		$srp = $_POST['srp'];
	else
		$srp = 0;

	if($_POST['srh'] != '')
		$srh = $_POST['srh'];
	else
		$srh = 0;
	
	if($_POST['f2r'] != '')
		$f2r = $_POST['f2r'];
	else
		$f2r = 0;
	
	$remarks = $_POST['remarks'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	


	if( empty($srp) && empty($srh) && empty($f2r))
	{
		echo "ERROR : All 3 sales entry cannot be left blank";
		Echo "<div align='center'><a href=entryPage.php><b>CLICK HERE TO GO TO PREVIOUS PAGE</b></div></a>";
	}	
	
	
	else
	{	
		$sql="INSERT INTO company_sale (date, ar, srp, srh, f2r, remarks,entered_by,entered_on)
			 VALUES
			 ('$sqlDate', '$arName', '$srp', '$srh', '$f2r', '$remarks', '$entered_by', '$entered_on')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

		header( "Location: new.php" );

	}

	mysqli_close($con);
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 