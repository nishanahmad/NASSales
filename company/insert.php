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
		$sql="INSERT INTO company_sale (date, ar_id, srp, srh, f2r, remarks,entered_by,entered_on)
			 VALUES
			 ('$sqlDate', '$arId', '$srp', '$srh', '$f2r', '$remarks', '$entered_by', '$entered_on')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

		header( "Location: new.php" );

	}

	mysqli_close($con);
}
else
{
	header( "Location: ../index.php" );
}	
?> 