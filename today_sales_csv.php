<?php

require 'library/sales_today_csv.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	require 'connect.php';


	$result = mysqli_query($con,"SELECT sales_id, entry_date,ar,truck_no,srp,srh,f2r,remarks, bill_no, customer_name, customer_phone, address1, address2
							FROM sales_entry WHERE entry_date >= CURDATE() order by bill_no asc  ") or die(mysqli_error($con));				 
			
	$csvArray = array();
	$csvArray[] = array('Date','AR','TRUCK NO','SRP','SRH','F2R','BILL NO','CUST. NAME','REMARKS');

	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$date = date("d-m-Y",strtotime($row["entry_date"]));
		$csvArray[] = array($date,$row["ar"],$row["truck_no"],$row["srp"],$row["srh"],
							$row["f2r"],$row["bill_no"],$row["customer_name"],$row["remarks"]);
	}
	
	sales_today_csv($csvArray);
}
else
	header("Location:loginPage.php");

?>