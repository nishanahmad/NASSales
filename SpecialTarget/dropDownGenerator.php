<?php

function getYears()
{
	require '../connect.php';

	$sql = mysqli_query($con,"SELECT YEAR(from_date) FROM special_target_date ORDER BY from_date DESC") or die(mysqli_error($con));		 
	foreach($sql as $row)
	{
		$yearList[] = (int)$row['YEAR(from_date)'];
	}
	$yearList = array_unique($yearList);					
	
	return($yearList);
}

function getMonths($year)
{
	require '../connect.php';

	$sql = mysqli_query($con,"SELECT MONTH(from_date) FROM special_target_date WHERE  YEAR(from_date) = '$year' ORDER BY from_date ASC") or die(mysqli_error($con));		 
	foreach($sql as $row)
	{
		$monthList[] = (int)$row['MONTH(from_date)'];
	}
	$monthList = array_unique($monthList);					
	
	return($monthList);
}

function getStrings($year,$month)
{
	require '../connect.php';

	$sql = mysqli_query($con,"SELECT from_date,to_date FROM special_target_date WHERE  YEAR(from_date) = '$year' AND MONTH(from_date) = '$month' ORDER BY from_date ASC") or die(mysqli_error($con));		 
	foreach($sql as $row)
	{
		$string = date("d",strtotime($row['from_date'])) .' to '. date("d",strtotime($row['to_date']));	;	
		$stringList[] = $string;
	}
	
	return($stringList);
}
?>