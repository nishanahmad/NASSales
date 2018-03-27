<?php
function getPointPercentage($actual_perc,$year,$month)
{
	if($year < 2017 || ($year == 2017 && $month <= 9))
	{
		if($actual_perc < 30)			
			$point_perc = 0;
		else if($actual_perc <= 40)		
			$point_perc = 20;
		else if($actual_perc <= 59)		
			$point_perc = 30;
		else if($actual_perc <= 69)		
			$point_perc = 40;
		else if($actual_perc <= 79)		
			$point_perc = 60;
		else if($actual_perc <= 89)		
			$point_perc = 80;
		else if($actual_perc <= 95)		
			$point_perc = 90;
		else if($actual_perc >= 96)		
			$point_perc = 100;										
	}
	else
	{
		if($actual_perc <= 70)			
			$point_perc = 0;
		else if($actual_perc <= 80)		
			$point_perc = 50;
		else if($actual_perc <= 95)		
			$point_perc = 70;
		else if($actual_perc >= 96)		
			$point_perc = 100;										
	}
			
	return $point_perc;
}
?>