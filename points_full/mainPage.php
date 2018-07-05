<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../functions/targetFormula.php';
	require 'getTargetMap.php';
	require 'getSaleMap.php';
	require 'getSpecialTargetMap.php';	
	
	$mainArray = array();
	if(isset($_GET['year']) && isset($_GET['month']) && isset($_GET['dateString']))
	{
		$year = (int)$_GET['year'];
		$month = (int)$_GET['month'];
		$dateString = $_GET['dateString'];
	}	
	else
	{
		$year = (int)date("Y");
		$month = (int)date("m");
		$dateString = 'FULL';
	}
	
	$arObjects =  mysqli_query($con,"SELECT id,ar_name,mobile,shop_name,sap_code FROM ar_details WHERE isActive = 1 ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']]['name'] = $ar['ar_name'];
		$arMap[$ar['id']]['mobile'] = $ar['mobile'];
		$arMap[$ar['id']]['shop'] = $ar['shop_name'];
		$arMap[$ar['id']]['sap'] = $ar['sap_code'];
	}				
	
	$prevMap = getPrevPoints(array_keys($arMap),$year,$month,$dateString);
	
	//var_dump($prevMap);
	
	$arIds = implode("','",array_keys($arMap));
	
	if($dateString == 'FULL')
	{	
		$targetObjects = mysqli_query($con,"SELECT ar_id, target, payment_perc,rate FROM target WHERE  month = '$month' AND Year='$year' AND target > 0 AND ar_id IN('$arIds')") or die(mysqli_error($con));		 
		foreach($targetObjects as $target)
		{
			$targetMap[$target['ar_id']]['target'] = $target['target'];
			$targetMap[$target['ar_id']]['rate'] = $target['rate'];
			$targetMap[$target['ar_id']]['payment_perc'] = $target['payment_perc'];
		}
		
		$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE '$year' = year(`entry_date`) AND '$month' = month(`entry_date`) AND ar_id IN ('$arIds') GROUP BY ar_id") or die(mysqli_error($con));	
		foreach($sales as $sale)
		{
			$arId = $sale['ar_id'];
			$total = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
			if(isset($targetMap[$arId]))
			{
				$points = round($total * $targetMap[$arId]['rate'],0);
				$actual_perc = round($total * 100 / $targetMap[$arId]['target'],0);
				$point_perc = getPointPercentage($actual_perc,$year,$month);			
				$achieved_points = round($points * $point_perc/100,0);
				
				if($total > 0)		
					$payment_points = round($achieved_points * $targetMap[$arId]['payment_perc']/100,0);
				else
					$payment_points = 0;			
				$pointMap[$arId]['points'] = $payment_points;			
			}
			else
			{
				$pointMap[$arId]['points'] = 0;
			}	
		}			
	}
	else
	{
		$dateArray = explode(" to ",$dateString);
		$from = $dateArray[0];
		$to = $dateArray[1];
		$toString = $to.'-'.$month.'-'.$year;		
		$toDate = date("Y-m-d",strtotime($toString));	
		
		$fromString = $from.'-'.$month.'-'.$year;		
		$fromDate = date("Y-m-d",strtotime($fromString));			
		
		$specialTargetObjects = mysqli_query($con,"SELECT ar_id, fromDate, toDate,special_target FROM special_target WHERE  toDate = '$toDate' AND fromDate = '$fromDate' AND special_target >0 AND ar_id IN('$arIds')") or die(mysqli_error($con));		 
		foreach($specialTargetObjects as $specialTarget)
		{
			$arId = $specialTarget['ar_id'];
			$start = $specialTarget['fromDate'];
			$end = $specialTarget['toDate'];
			$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$start' AND entry_date <= '$end' AND ar_id = '$arId' GROUP BY ar_id") or die(mysqli_error($con));	
			foreach($sales as $sale)
			{
				$total = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
				$extraBags = mysqli_query($con,"SELECT ar_id,qty FROM extra_bags WHERE date >= '$start' AND date <= '$end' AND ar_id = '$arId' GROUP BY ar_id") or die(mysqli_error($con));	
				foreach($extraBags as $extraBag)
					$total = $total + $extraBag['qty'];

				if($total >= ($specialTarget['special_target']*.9))
					$pointMap[$arId]['points'] = $total;
				else
					$pointMap[$arId]['points'] = 0;			
			}
		}		
	}
	
	$currentRedemption = mysqli_query($con,"SELECT ar_id,SUM(points) FROM redemption WHERE '$year' = year(`date`) AND '$month' = month(`date`) AND ar_id IN ('$arIds') GROUP BY ar_id") or die(mysqli_error($con));	
	foreach($currentRedemption as $redemption)
	{
		$redemptionMap[$redemption['ar_id']] = $redemption['SUM(points)'];
	}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/loader.css">	
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.floatThead.min.js"></script>
<script src="../js/fileSaver.js"></script>
<script src="../js/tableExport.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$("#loader").hide();
 	$("#button").click(function(){
		$("table").tableExport({
				formats: ["xls"],    // (String[]), filetypes for the export
				bootstrap: false,
				ignoreCSS: ".ignore"   // (selector, selector[]), selector(s) to exclude from the exported file
		});
	});		
	var $table = $('.responstable');
	$table.floatThead();				
} );
function rerender()
{
	var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;
	var month=document.getElementById("jsMonth").value;
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	$("#main").hide();
	$("#loader").show();
	window.location.href = hrf +"?year="+ year + "&month=" + month + "&dateString=FULL";
}
function rerender2()
{
	var dateString = document.getElementById("jsDateString").options[document.getElementById("jsDateString").selectedIndex].value;
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("&dateString"));
	$("#main").hide();
	$("#loader").show();
	window.location.href = hrf + "&dateString=" + dateString;
}
</script>

<title><?php echo getMonth($month); echo " "; echo $year; ?></title>
</head>
<body>
	<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<div class="circle"></div>
		<div class="circle1"></div>
		<br>
		<font style="color:white;font-weight:bold">Calculating ......</font>
	</div>
	<div align="center">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
		<br><br>
		<select id="jsMonth" name="jsMonth" class="textarea" onchange="return rerender();">	
			<option value = "<?php echo $month;?>"><?php echo getMonth($month);?></option>																						<?php	
			$monthList = mysqli_query($con, "SELECT DISTINCT month FROM target WHERE month <> $month ORDER BY month ASC" ) or die(mysqli_error($con));	
			foreach($monthList as $monthObj) 
			{	
	?>			<option value="<?php echo $monthObj['month'];?>"><?php echo getMonth($monthObj['month']);?></option>															<?php	
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsYear" name="jsYear" class="textarea" onchange="return rerender();">
			<option value = "<?php echo $year;?>"><?php echo $year;?></option>																									<?php	
			$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target  WHERE year <> $year ORDER BY year DESC") or die(mysqli_error($con));	
			foreach($yearList as $yearObj) 
			{
?>				<option value="<?php echo $yearObj['year'];?>"><?php echo $yearObj['year'];?></option>																			<?php	
			}
?>		</select>
			&nbsp;&nbsp;

		<select id="jsDateString" name="jsDateString" class="textarea" onchange="return rerender2();">
			<option value = "<?php echo $dateString;?>"><?php echo $dateString;?></option>																									<?php	
			$dateList = mysqli_query($con, "SELECT from_date,to_date FROM special_target_date WHERE YEAR(from_date) = $year AND MONTH(from_date) = $month" ) or die(mysqli_error($con));	
			foreach($dateList as $dateObj) 
			{
?>				<option value="<?php echo date('d', strtotime($dateObj['from_date'])).' to '.date('d', strtotime($dateObj['to_date']));?>"><?php echo date('d', strtotime($dateObj['from_date'])).' to '.date('d', strtotime($dateObj['to_date']));?></option>																			<?php	
			}
			if($dateString != 'FULL')?>
				<option value = "FULL">FULL</option>																										
		</select>
		<br><br>
		
		<img src="../images/excel.png" id="button" height="50px" width="45px" />
		<br/><br/>

		<table id="Points" class="responstable" style="width:70% !important">
		<thead>
			<tr>
				<th style="width:20%;text-align:left;">AR</th>
				<th style="width:12%;">Mobile</th>
				<th style="width:25%;text-align:left;">Shop</th>
				<th style="width:10%;">SAP</th>
				<th>Opng Pnts</th>
				<th>Current Pnts</th>	
				<th>Redeemed Pnts</th>	
				<th>Balance</th>	
			</tr>
		</thead>	
							
																																												<?php
			foreach($arMap as $arId => $detailMap)
			{		
				if(!isset($targetMap[$arId]))
					$targetMap[$arId]['target'] = 0;						
				if(!isset($pointMap[$arId]))	
					$pointMap[$arId]['points'] = 0;
				if(!isset($redemptionMap[$arId]))	
					$redemptionMap[$arId] = 0;																																	?>
				
				
				<tr align="center">
				<td style="text-align:left;"><?php echo $detailMap['name'];?></b></td>
				<td><?php echo $detailMap['mobile'];?></b></td>
				<td style="text-align:left;"><?php echo $detailMap['shop'];?></b></td>
				<td><?php echo $detailMap['sap'];?></b></td>
				<td><?php echo $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'];?></b></td>
				<td><?php echo $pointMap[$arId]['points'];?></td>
				<td><?php echo $redemptionMap[$arId];?></td>
				<td><?php echo $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'] + $pointMap[$arId]['points'] - $redemptionMap[$arId];?></td>
				</tr>																																							<?php
			}																																									?>
		</table>
		<br/><br/><br/><br/>
	</div>
</body>
</html>																																											<?php
}
else
	header("../Location:index.php");


function getPrevPoints($arList,$endYear,$endMonth,$dateString)
{
	require '../connect.php';
	
	$startYear = 2018; 
	$startMonth = 1;
	
	if($dateString == 'FULL')
	{
		$string = cal_days_in_month(CAL_GREGORIAN,$endMonth,$endYear).'-'.$endMonth.'-'.$endYear;		
	}
	else
	{
		$dateArray = explode(" to ",$dateString);
		$from = $dateArray[0];
		$to = $dateArray[1];
		$string = $to.'-'.$endMonth.'-'.$endYear;		
	}
	
	$endDate = date("Y-m-d",strtotime($string));
	
	foreach($arList as $arId)
	{
		$arMap[$arId]['prevPoints'] = 0;	
		$arMap[$arId]['prevRedemption'] = 0;			
	}
	
	$arIds = implode("','",array_keys($arMap));	
	
	
	//call targetMap and saleMap from helper functions 
	
	$targetMap = getTargetMap($arIds,$startYear);		// arId => year => month => target
	$saleMap = getSaleMap($arIds,$startYear,$endYear);	    // arId => year => month = sale

	// Add points based on monthly targets
	foreach($targetMap as $arId => $yearMonthArray)
	{
		foreach($yearMonthArray as $year => $monthArray)
		{
			if($year <= $endYear)
			{
				foreach($monthArray as $month => $detailArray)
				{
					if($month < $endMonth && $year == $endYear)
					{
						if(isset($saleMap[$arId][$year][$month]))
							$sale = $saleMap[$arId][$year][$month];
						else
							$sale = 0;

						$points = round($sale * $detailArray['rate'],0);
						$actual_perc = round($sale * 100 / $detailArray['target'],0);
						$point_perc = getPointPercentage($actual_perc,$year,$month);			
						$achieved_points = round($points * $point_perc/100,0);
						
						if($sale > 0)		
							$payment_points = round($achieved_points * $detailArray['payment_perc']/100,0);
						else if(isset($detailArray))
							$payment_points = 0;			
						
						$arMap[$arId]['prevPoints'] = $arMap[$arId]['prevPoints'] + $payment_points;												
					}
				}				
			}
		}
	}
	
	// Add points based on special targets
	$specialTargetMap = getSpecialTargetMap($arIds,$endDate);		// arId => fromDate => special_target
	//var_dump($specialTargetMap);
	
	$stDates = mysqli_query($con,"SELECT from_date,to_date FROM special_target_date WHERE to_date <= '$endDate' ") or die(mysqli_error($con));	
	foreach($stDates as $stDate)
	{
		$start = $stDate['from_date'];
		$end = $stDate['to_date'];
		$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$start' AND entry_date <= '$end' AND ar_id = '$arId' GROUP BY ar_id") or die(mysqli_error($con));	
		foreach($sales as $sale)
		{
			$arId = $sale['ar_id'];
			$total = $sale['SUM(srp)'] + $sale['SUM(srh)'] + $sale['SUM(f2r)'] - $sale['SUM(return_bag)'];
			$extraBags = mysqli_query($con,"SELECT ar_id,qty FROM extra_bags WHERE date >= '$start' AND date <= '$end' AND ar_id = '$arId' GROUP BY ar_id") or die(mysqli_error($con));	
			foreach($extraBags as $extraBag)
				$total = $total + $extraBag['qty'];

			if($total >= ($specialTargetMap[$arId][$start]*.9))
				$arMap[$arId]['prevPoints'] = $arMap[$arId]['prevPoints'] + $total;			
		}			
	}
	
	$redemptionList = mysqli_query($con,"SELECT * FROM redemption WHERE  date <= '$endDate' AND ar_id IN('$arIds')") or die(mysqli_error($con));		 	
	foreach($redemptionList as $redemption)
	{
		$arMap[$redemption['ar_id']]['prevRedemption'] = $arMap[$redemption['ar_id']]['prevRedemption'] + $redemption['points'];			
	}

	return $arMap;
}
?>
