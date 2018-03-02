<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=TargetReport.csv');


session_start();
if(isset($_SESSION["user_name"]))
{
	$output = fopen('php://output', 'w');

	require '../connect.php';	

	$sql="SELECT * FROM ar_details";

	$result = mysqli_query($con,"SELECT ar_name, ar_id, target FROM ar_details order by ar_name asc") or die(mysqli_error($con));				 
		 
	$mainarray = array();
	$mainarray[] = array('AR NAME','TARGET','TOTAL', 'PRORATA');	
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arname = $row['ar_name'];
		$target = $row['target'];
		$total = 0;


		$result2 = mysqli_query($con,"SELECT ar,entry_date,lpp,hdpe,cstl
								 FROM sales_entry order by ar asc") or die(mysqli_error($con));				 

			
		while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
		{
			if($row['ar_name'] === $row2['ar'])
			{	
				$monthcurrent = date('m');
				$daycurrent = date('d');	
				$yearcurrent = date('Y');
				$month = date("m",strtotime($row2['entry_date']));
				$day = date("d",strtotime($row2['entry_date']));
				$year = date("Y",strtotime($row2['entry_date']));
				if(  ($month===$monthcurrent) &&  ($year == $yearcurrent)  &&  ($day <= $daycurrent)   )
					$total = $total + $row2['lpp'] + $row2['hdpe'] + $row2['cstl'];					
			}	
		}
		$temp = $target*(date('d')/date('t'));
		if($temp != 0)
			$prorata = round($total/$temp*100 , 2);
		else
			$prorata = 0;	
		$mainarray[] = array($arname, $target, $total, $prorata);
	}

	foreach ($mainarray as $fields) 
		fputcsv($output, $fields);
}
else
	header("../Location:loginPage.php");

?>