<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../functions/targetFormula.php';	

	$mainArray = array();
	if(isset($_GET['year']) && isset($_GET['month']))
	{
		$year = (int)$_GET['year'];
		$month = (int)$_GET['month'];		
	}	
	else
	{
		$year = (int)date("Y");
		$month = (int)date("m");
	}

	$zeroTargetList = mysqli_query($con,"SELECT ar_id FROM target WHERE year = '$year' AND month  = '$month' AND target = 0") or die(mysqli_error($con));		 
	foreach($zeroTargetList as $zeroTarget)
	{
		$zeroTargetMap[$zeroTarget['ar_id']] = null;
	}
	
	$zeroTargetIds = implode("','",array_keys($zeroTargetMap));	
	
	$arObjects =  mysqli_query($con,"SELECT id,ar_name,mobile,shop_name,sap_code FROM ar_details WHERE  isActive = 1 AND id NOT IN ('$zeroTargetIds') ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']]['name'] = $ar['ar_name'];
		$arMap[$ar['id']]['mobile'] = $ar['mobile'];
		$arMap[$ar['id']]['shop'] = $ar['shop_name'];
		$arMap[$ar['id']]['sap'] = $ar['sap_code'];
	}				
	
	$arIds = implode("','",array_keys($arMap));
	$targetObjects = mysqli_query($con,"SELECT ar_id, target, payment_perc,rate FROM target WHERE  month = '$month' AND Year='$year' AND ar_id IN('$arIds')") or die(mysqli_error($con));		 
	foreach($targetObjects as $target)
	{
		$targetMap[$target['ar_id']]['target'] = $target['target'];
		$targetMap[$target['ar_id']]['rate'] = $target['rate'];
		$targetMap[$target['ar_id']]['payment_perc'] = $target['payment_perc'];
	}
	


	$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE '$year' = year(`entry_date`) AND '$month' = month(`entry_date`) AND ar_id IN ('$arIds') GROUP BY ar_id") or die(mysqli_error($con));	

	$mainArray = array();
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
				$payment_points = -50;			

			$mainArray[$arId]['actual_sale'] = $total;
			$mainArray[$arId]['points'] = $points;
			$mainArray[$arId]['actual_perc'] = $actual_perc;
			$mainArray[$arId]['point_perc'] = $point_perc;
			$mainArray[$arId]['achieved_points'] = $achieved_points;
			$mainArray[$arId]['payment_points'] = $payment_points;			
		}
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
	window.location.href = hrf +"?year="+ year + "&month=" + month;
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
			<option value = "<?php echo $month;?>"><?php echo getMonth($month);?></option>			<?php	
			$monthList = mysqli_query($con, "SELECT DISTINCT month FROM target WHERE month <> $month ORDER BY month ASC" ) or die(mysqli_error($con));	
			foreach($monthList as $monthObj) 
			{	
	?>			<option value="<?php echo $monthObj['month'];?>"><?php echo getMonth($monthObj['month']);?></option>		<?php	
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsYear" name="jsYear" class="textarea" onchange="return rerender();">
			<option value = "<?php echo $year;?>"><?php echo $year;?></option>			<?php	
			$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target  WHERE year <> $year ORDER BY year DESC") or die(mysqli_error($con));	
			foreach($yearList as $yearObj) 
			{
?>				<option value="<?php echo $yearObj['year'];?>"><?php echo $yearObj['year'];?></option>											<?php	
			}
?>		</select>
		<br><br>
		
		<img src="../images/excel.png" id="button" height="50px" width="45ypx" />
		<br/><br/>

		<table id="Points" class="responstable" style="width:90% !important">
		<thead>
			<tr>
				<th style="width:20%;text-align:left;">AR</th>
				<th style="width:12%;">Mobile</th>
				<th style="width:25%;text-align:left;">Shop</th>
				<th style="width:10%;">SAP</th>
				<th>Target</th>
				<th>Sale</th>
				<th>Rate</th>
				<th>Points</th>
				<th>Actual%</th>	
				<th>Point%</th>	
				<th>Payment%</th>	
				<th>Achieved Pnts</th>
				<th>Points</th>	
			</tr>
		</thead>	
							
																																						<?php
			foreach($targetMap as $arId => $targetArray)
			{		
				$target = $targetArray['target'];
				$rate = $targetArray['rate'];
				$payment_perc = $targetArray['payment_perc'];
				if(!isset($mainArray[$arId]))
				{
					$mainArray[$arId]['actual_sale'] = null;
					$mainArray[$arId]['points'] = null;
					$mainArray[$arId]['actual_perc'] = null;
					$mainArray[$arId]['point_perc'] = null;
					$mainArray[$arId]['achieved_points'] = null;
					$mainArray[$arId]['payment_points'] = null;
				}																																	?>
				
				<tr align="center">
				<td style="text-align:left;"><?php echo $arMap[$arId]['name'];?></b></td>
				<td><?php echo $arMap[$arId]['mobile'];?></b></td>
				<td style="text-align:left;"><?php echo $arMap[$arId]['shop'];?></b></td>
				<td><?php echo $arMap[$arId]['sap'];?></b></td>
				<td><?php echo $target;?></td>
				<td><?php echo $mainArray[$arId]['actual_sale'];?></td>
				<td><?php echo $rate;?></td>
				<td><?php echo $mainArray[$arId]['points'];?></td>
				<td><?php echo $mainArray[$arId]['actual_perc'].'%';?></td>
				<td><?php echo $mainArray[$arId]['point_perc'].'%';?></td>
				<td><?php echo $payment_perc;?></td>
				<td><?php echo $mainArray[$arId]['achieved_points'];?></td>
				<td><?php echo '<b>'.$mainArray[$arId]['payment_points'].'</b>';?></td>
				</tr>																															<?php
			}																																	?>
		</table>
		<br/><br/><br/><br/>
	</div>
</body>
</html>
<?php
}
else
	header("../Location:index.php");
?>