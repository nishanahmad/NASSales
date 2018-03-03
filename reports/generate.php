<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=SalesReport.csv');

$output = fopen('php://output', 'w');

require '../connect.php';
  
	$fromdate = date("Y-m-d", strtotime($_POST['from']));
	$todate = date("Y-m-d", strtotime($_POST['to']));;
	
	$year = DateTime::createFromFormat("Y-m-d", $fromdate);
	$year =  $year->format("Y");
	
	$month = DateTime::createFromFormat("Y-m-d", $fromdate);
	$month =  $month->format("m");

	$day = DateTime::createFromFormat("Y-m-d", $todate);
	$day =  $day->format("d");	
 
	$result = mysqli_query($con, "SELECT * FROM sales_entry order by ar" ) or die(mysqli_error($con));

	$arQuery = mysqli_query($con, "SELECT ar_name,sap_code FROM ar_details order by ar_name" ) or die(mysqli_error($con));	
	while($ar = mysqli_fetch_array($arQuery,MYSQLI_ASSOC))
	{
		$arMap[trim($ar['ar_name'])] = $ar['sap_code'];
	}
	
	$arname = null;	
	$sum = 0;
	$sum_srp = 0;
	$sum_srh = 0;
	$sum_f2r = 0;
	$prorata = 0;
	$mainarray = array();
	$mainarray[] = array('AR NAME','SAP CODE','MOBILE','SRP','SRH','F2R','TARGET','TOTAL','BALANCE','PRORATA %','TOTAL %');
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		if ($row["entry_date"] >= $fromdate  && $row["entry_date"] <= $todate)
		{
				if(strcmp(trim($row["ar"]),$arname) != 0) 
				{   
					if($arname != null)
					{
						$sap = $arMap[$arname];
						
						$temp = $target*($day/25);
						if($temp != 0)
						$prorata = round($sum/$temp*100);
						else
						$prorata = 0;

						if($target > 0)
							$mainarray[] = array($arname,$sap,$mobile,$sum_srp,$sum_srh,$sum_f2r,$target,$sum,$target-$sum,$prorata,round($sum/$target*100));
						else
							$mainarray[] = array($arname,$sap,$mobile,$sum_srp,$sum_srh,$sum_f2r,$target,$sum,$target-$sum,$prorata,0);
					}
					$arname = trim($row["ar"]);
					$target = 0;
					$mobile = 0;
					$sum = $row["srp"] + $row["srh"] + $row["f2r"];
					$sum_srp = $row["srp"];
					$sum_srh = $row["srh"];
					$sum_f2r = $row["f2r"];
					$query2 = "SELECT target FROM ar_calculation where ar_name = '$arname' AND month = '$month' AND year = '$year'";
					$target_result = mysqli_query($con, $query2 ); 
					while($row = mysqli_fetch_array($target_result,MYSQLI_ASSOC))
					{
						$target = $row["target"];
					}	
					$query3 = "SELECT mobile FROM ar_details where ar_name = '$arname'";
					$mobile_result = mysqli_query($con, $query3 ); 
					while($row = mysqli_fetch_array($mobile_result,MYSQLI_ASSOC))
					{
						$mobile = $row["mobile"];
					}	
					
				}	
				else
				{
					$sum = $sum + $row["srp"] + $row["srh"] + $row["f2r"];
					$sum_srp = $sum_srp + $row["srp"];
					$sum_srh = $sum_srh + $row["srh"];
					$sum_f2r = $sum_f2r + $row["f2r"];
				}
			
		}

		
	}
						if($target > 0)
							$mainarray[] = array($arname,$mobile,$sum_srp,$sum_srh,$sum_f2r,$target,$sum,$target-$sum,$prorata,round($sum/$target*100));
						else
							$mainarray[] = array($arname,$mobile,$sum_srp,$sum_srh,$sum_f2r,$target,$sum,$target-$sum,$prorata,0);


//  *** Write array to csv
					
		foreach ($mainarray as $fields) 
		fputcsv($output, $fields);
?>	
 