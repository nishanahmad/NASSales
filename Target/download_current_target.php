<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../library/target_csv.php';

	$sql="SELECT salesforce_id,target FROM ar_details";

	$result = mysqli_query($con,"SELECT salesforce_id,ar_name, target FROM ar_details WHERE salesforce_id NOT LIKE '%GENERAL%' order by ar_name asc") or die(mysqli_error($con));
	
	$target = array();
	$target[] = array('Id','AR Name','Current Target');
	foreach ($result as $ar)
	{
		$target[] = array($ar['salesforce_id'],trim($ar['ar_name']),$ar['target']);
	}
	target_csv($target);
}
else
	header("../Location:loginPage.php");

?>