<?php

function insertNewMonthPoints($month,$year) 
{
   	require '../connect.php';
	if($month != 1)
	{
		$oldmonth = $month - 1;
		$oldyear = $year;
	}	
	else
	{
		$oldmonth = 12;
		$oldyear = $year - 1;
	}
	
	$arObjects = mysqli_query($con, "SELECT id,ar_name FROM ar_details WHERE isActive = 1 ORDER BY ar_name asc") or die(mysqli_error($con)) or die(mysqli_error($con));		 								
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['ar_name'];
	}
	
	$array = implode("','",array_keys($arMap));		
	$sql = "SELECT ar_id, target, rate, payment_perc FROM ar_calculation WHERE year='$oldyear' AND Month='$oldmonth' AND ar_id IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
	if(mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$arId = $row['ar_id'];
			$rate = $row['rate'];
			$target = $row['target'];
			$pp = $row['payment_perc'];
		

			$sql1="INSERT INTO ar_calculation (ar_id, target,rate,payment_perc, month, year)
			VALUES
			('$arId', '$target',$rate, '$pp' ,'$month', '$year')";							
			$result1 = mysqli_query($con, $sql1) or die(mysqli_error($con));				   			
		}	
	}	
	else
	{
?>  <html>
	<div align="center" style="font-size:40px"><br><br>No data was found for the previous month also
	<br><br>
	<button onclick="window.location.href='../index.php'">Click here to go home</button>
	</div>
<?php	
	exit;		
	}	
}

?>
