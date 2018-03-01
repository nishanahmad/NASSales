<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$originalDate = $_POST['date'];
	$newDate = date("Y-m-d", strtotime($originalDate));
	$arId = $_POST['ar'];
	$truck = $_POST['truck'];
	$srp = $_POST['srp'];
	$srh = $_POST['srh'];
	$f2r = $_POST['f2r'];
	$return = $_POST['return'];
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$entered_by = $_SESSION["user_name"];


	if( empty($srp) && empty($srh) && empty($f2r))
	{
		echo "ERROR : All 3 sales entry cannot be left blank";
		Echo "<div align='center'><a href=new.php><b>CLICK HERE TO GO TO PREVIOUS PAGE</b></div></a>";
	}	
	
	
	else
	{
		$total = $srp + $srh + $f2r;		
		$entered_on = date('Y-m-d H:i:s');	
		$sql="INSERT INTO sales_entry (entry_date, ar_id, truck_no, srp, srh, f2r, return_bag, remarks, bill_no, customer_name, customer_phone, address1, address2,entered_by,entered_on)
			 VALUES
			 ('$newDate', '$arId', '$truck', '$srp', '$srh', '$f2r', '$return', '$remarks', '$bill', '$customerName', '$customerPhone', '$address1', '$address2', '$entered_by', '$entered_on')";

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