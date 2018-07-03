<?php
function getSpecialTargetMap($arIds,$endDate)
{
	require '../connect.php';
	
	$specialTargetMap = array();
	
	$specialTargetObjects = mysqli_query($con,"SELECT ar_id, fromDate, toDate,special_target FROM special_target WHERE  toDate <= '$endDate' AND fromDate >= '2018-01-01' AND special_target >0 AND ar_id IN('$arIds')") or die(mysqli_error($con));		 
	foreach($specialTargetObjects as $specialTarget)
	{
		$specialTargetMap[$specialTarget['ar_id']][$specialTarget['fromDate']] = $specialTarget['special_target'];
	}	

	return $specialTargetMap;
}
?>	