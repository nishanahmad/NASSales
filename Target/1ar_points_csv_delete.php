<?php
session_start();
if(isset($_SESSION["user_name"]))
{

require 'connect.php';
	
$name = $path.date("d-M-Y").'.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename='. $name);
header('Pragma: no-cache');
header("Expires: 0");
	
$output = fopen('php://output', 'w');
	
$result_current_month = mysqli_query($con,"SELECT name FROM current_month") or die(mysqli_error($con));				 
$cm = mysqli_fetch_array($result_current_month,MYSQLI_ASSOC);
$current_month = $cm["name"]*1;


 if($current_month == 1)
 {
	$result = mysqli_query($con,"SELECT * FROM ar_details order by ar_name asc") or die(mysqli_error($con));				 
 }
 else if($current_month == 2)  
 {
	$result = mysqli_query($con,"SELECT * FROM ar_details2 order by ar_name asc") or die(mysqli_error($con));				 
 }

$mainarray = array();
$mainarray[] = array('NAME', 'TARGET', 'ACTUAL SALES', 'RATE', 'POINTS', 'ACTUAL%', 'POINT%',  'ACHIEVED POINTS');
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
{
	$arname = $row['ar_name'];
	$target = $row["target"];
	$actual_sales = $row["actual_sales"];
	$rate = $row["rate"];
	$points = $row["points"];
	$actual_perc = $row["actual_perc"];
	$point_perc = $row["point_perc"];
	$achieved_points = $row["achieved_points"];


	$mainarray[] = array($arname, $target, $actual_sales, $rate, $points, $actual_perc, $point_perc,  $achieved_points);
}

		foreach ($mainarray as $fields) 
				fputcsv($output, $fields);

 
}	

else
	header("../Location:loginPage.php");

?>