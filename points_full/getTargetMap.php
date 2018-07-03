<?php
function getTargetMap($arIds,$startYear)
{
	require '../connect.php';
	
	$targetObjects = mysqli_query($con,"SELECT ar_id, target, payment_perc,rate, year,month FROM target WHERE  Year >='$startYear' AND target >0 AND ar_id IN('$arIds')") or die(mysqli_error($con));		 
	foreach($targetObjects as $target)
	{
		$targetMap[$target['ar_id']][$target['year']][$target['month']]['target'] = $target['target'];
		$targetMap[$target['ar_id']][$target['year']][$target['month']]['rate'] = $target['rate'];
		$targetMap[$target['ar_id']][$target['year']][$target['month']]['payment_perc'] = $target['payment_perc'];
	}	

	return $targetMap;
}
?>	