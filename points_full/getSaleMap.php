<?php
function getSaleMap($arIds,$startYear,$endYear)
{
	require '../connect.php';
		
	for($year=$startYear; $year<=$endYear; $year++)
	{
		$sales = mysqli_query($con,"SELECT ar_id,
									  sum(if(month(entry_date) = 1, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '1',
									  sum(if(month(entry_date) = 2, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '2',
									  sum(if(month(entry_date) = 3, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '3',
									  sum(if(month(entry_date) = 4, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '4',
									  sum(if(month(entry_date) = 5, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '5',
									  sum(if(month(entry_date) = 6, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '6',
									  sum(if(month(entry_date) = 7, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '7',
									  sum(if(month(entry_date) = 8, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '8',
									  sum(if(month(entry_date) = 9, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0))  AS '9',
									  sum(if(month(entry_date) = 10, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0)) AS '10',
									  sum(if(month(entry_date) = 11, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0)) AS '11',
									  sum(if(month(entry_date) = 12, IFNULL(srp, 0) + IFNULL(srh, 0) + IFNULL(f2r, 0) - IFNULL(return_bag, 0), 0)) AS '12'
										FROM nas_sale WHERE YEAR(entry_date) = '$year' AND ar_id IN('$arIds')
										GROUP BY ar_id")  or die(mysqli_error($con));		 
		foreach($sales as $sale)
		{
			for($month=1;$month<=12;$month++)
			{
				if(isset($sale[$month]))
					$saleMap[$sale['ar_id']][$year][$month] = (int)$sale[$month];	
				else
					$saleMap[$sale['ar_id']][$year][$month] = 0;	
			}
		}			
	}

	return $saleMap;
}
?>	