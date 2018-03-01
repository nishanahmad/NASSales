<html>
<body>
<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{	//var_dump($_POST);
	$month = $_POST['month'];
	$year = $_POST['year'];	

	foreach($_POST as $key => $value)
	{
		$arr = explode("-",$key);
		$arName = str_replace('_',' ',$arr[0]);
		if($arr[1] == 'target')
		{
			$sql="UPDATE ar_calculation SET target = '$value' WHERE ar_name = '$arName' AND month = '$month' AND year = '$year' ";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
		}
		else if($arr[1] == 'rate')	
		{
			$sql="UPDATE ar_calculation SET rate = '$value' WHERE ar_name = '$arName' AND month = '$month' AND year = '$year' ";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   			
		}	
		else if($arr[1] == 'pp')	
		{
			$sql="UPDATE ar_calculation SET payment_perc = '$value' WHERE ar_name = '$arName' AND month = '$month' AND year = '$year' ";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   			
		}			
		else if($arr[1] == 'companyTarget')	
		{
			$sql="UPDATE ar_calculation SET company_target = '$value' WHERE ar_name = '$arName' AND month = '$month' AND year = '$year' ";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   			
		}					
	}

	$lockAR ="UPDATE lock_target SET locked = 1 WHERE  month = '$month' AND year = '$year' ";
	$LockARresult = mysqli_query($con, $lockAR) or die(mysqli_error($con));				   	
	
	header( "Location: ../index.php" );

	mysqli_close($con); 
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
</body>
</html>