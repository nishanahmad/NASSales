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
	$sql = "SELECT ar_name, target, rate, payment_perc FROM ar_calculation WHERE year='$oldyear' AND Month='$oldmonth' order by ar_name asc";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
	if(mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$arName = $row['ar_name'];
			$rate = $row['rate'];
			$target = $row['target'];
			$pp = $row['payment_perc'];
		

			$sql1="INSERT INTO ar_calculation (ar_name, target,rate,payment_perc, month, year)
			VALUES
			('$arName', '$target',$rate, '$pp' ,'$month', '$year')";							
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
