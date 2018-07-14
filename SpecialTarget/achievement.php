<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require 'dropDownGenerator.php';
	
	$today = date("Y-m-d");
	
	if(isset($_GET['year']))
	{
		$year = (int)$_GET['year'];		

		$monthList = getMonths($year);
		if(isset($_GET['month']))
		{
			$month = (int)$_GET['month'];
			if(isset($_GET['dateString']))
				$dateString = $_GET['dateString'];
			else	
			{
				$stringList = getStrings($year,$month);					
				$dateString = end($stringList);					
			}				
		}
		else
		{
			$month = end($monthList);
			$stringList = getStrings($year,$month);					
			$dateString = end($stringList);					
		}
	}
	else
	{
		$year = (int)date("Y");	

		$monthList = getMonths($year);		
		$month = end($monthList);

		$stringList = getStrings($year,$month);					
		$dateString = end($stringList);							
	}
	
	$dateArray = explode(" to ",$dateString);
	$from = $dateArray[0];
	$to = $dateArray[1];
	$toString = $to.'-'.$month.'-'.$year;		
	$toDate = date("Y-m-d",strtotime($toString));	
	
	$fromString = $from.'-'.$month.'-'.$year;		
	$fromDate = date("Y-m-d",strtotime($fromString));
		
	$zeroTargetMap = null;
	$zeroTargetList = mysqli_query($con,"SELECT ar_id FROM special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate' AND special_target = 0") or die(mysqli_error($con));		 
	foreach($zeroTargetList as $zeroTarget)
	{
		$zeroTargetMap[$zeroTarget['ar_id']] = null;
	}
	
	$zeroTargetIds = implode("','",array_keys($zeroTargetMap));		
	
	$arList = mysqli_query($con,"SELECT id, ar_name, mobile, shop_name FROM ar_details WHERE isActive = 1 AND id NOT IN ('$zeroTargetIds') ") or die(mysqli_error($con));		 
	foreach($arList as $arObject)
	{
		$arNameMap[$arObject['id']] = $arObject['ar_name'];
		$arMobileMap[$arObject['id']] = $arObject['mobile'];
		$arShopMap[$arObject['id']] = $arObject['shop_name'];
		$arExtraMap[$arObject['id']] = 0;
	}
	$extraBagsList = mysqli_query($con,"SELECT ar_id,SUM(qty) FROM extra_bags WHERE date >= '$fromDate' AND date <= '$toDate' GROUP BY ar_id") or die(mysqli_error($con));											
	foreach($extraBagsList as $extraBag)
	{
		$arExtraMap[$extraBag['ar_id']] = $extraBag['SUM(qty)'];
	}	
	
	$array = implode("','",array_keys($arNameMap));	
	
	$arTarget = mysqli_query($con,"SELECT ar_id, special_target FROM special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate' AND ar_id IN ('$array')") or die(mysqli_error($con));		 
	foreach($arTarget as $splTarget)
	{
		$arTargetMap[$splTarget['ar_id']] = $splTarget['special_target']; 
	}
	
	$ar_detail = mysqli_query($con,"SELECT ar_id, special_target FROM special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate' AND ar_id IN ('$array')") or die(mysqli_error($con));		 
	
	if(isset($_GET['removeToday']) && $_GET['removeToday'] == 'true')
	{
		$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate'
											AND entry_date <= '$toDate' AND entry_date < CURDATE() 
											AND ar_id IN ('$array')
											GROUP BY ar_id")
											or die(mysqli_error($con));												
	}
	else
		$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate'
											AND entry_date <= '$toDate'
											AND ar_id IN ('$array')
											GROUP BY ar_id")
											or die(mysqli_error($con));								
	foreach($sales as $sale)
	{
		$lpp = $sale['SUM(srp)'];
		$hdpe = $sale['SUM(srh)'];
		$cstl = $sale['SUM(f2r)'];
		$return_bag = $sale['SUM(return_bag)'];
		$total = $lpp + $hdpe + $cstl - $return_bag;
		$arSaleMap[$sale['ar_id']] = $total;
	}
	if($noticeFlag)
		$dateString = null;
?>
<html>
<head>
	<style>
	.selected{
		background-color:#ffb3b3 !important;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="../css/loader.css">	
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">	
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery.floatThead.min.js"></script>
	<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		$("#loader").hide();	
		
		var checkbox = getUrlParameter('removeToday');
		if(checkbox =='true')
			$('#removeToday').prop('checked', true);
		else
			$('#removeToday').prop('checked', false);	
		
		var $table = $('.responstable');
		$table.floatThead();		
				
	} );
	function refresh()
	{
		var removeToday = $('#removeToday').is(':checked');
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("&removeToday="));
		
		window.location.href = hrf + "&removeToday=" + removeToday;
	}
	
	function refreshYear()
	{
		var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		
		window.location.href = hrf +"?year="+ year;
	}	
	
	function refreshMonth()
	{
		var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;
		var month=document.getElementById("jsMonth").value;
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		
		window.location.href = hrf +"?year="+ year + "&month=" + month;
	}
	
	function refreshString()
	{
		var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;
		var month=document.getElementById("jsMonth").value;
		var dateString = document.getElementById("jsDateString").options[document.getElementById("jsDateString").selectedIndex].value;
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		
		window.location.href = hrf +"?year="+ year + "&month=" + month + "&dateString=" + dateString;
	}
	
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

	
	$(function(){
	  $(".responstable tr").each(function(){
		var extra = $(this).find("td:eq(7)").text();   
		if (extra != '0'){
		  $(this).addClass('selected');
		}
	  });
	});		
	</script>
	<title>Special Target</title>
</head>
<body>
	<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<div class="circle"></div>
		<div class="circle1"></div>
		<br>
		<font style="color:white;font-weight:bold">Calculating ......</font>
	</div>
		
		
		<div align="center" style="width:100%;">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
		<h1>SPECIAL TARGET ACHIEVEMENT</h1>
		<br><br>
		<select name="grouping" id="grouping" onchange="location.href= this.value ">
			 <option selected value="#">No Grouping</option>   								
			 <option value="achievement_user.php?">User Wise</option>
			 <option value="achievement_area.php?">Area Wise</option>   								
		</select>		
		<br><br>
		
		<select id="jsYear" name="jsYear" class="textarea" onchange="return refreshYear();">
			<option value = "<?php echo $year;?>"><?php echo $year;?></option>																									<?php	
			$yearList = getYears();	

			foreach($yearList as $yr)
			{
				if($year != $yr)
				{																																			?>
					<option value="<?php echo $yr;?>"><?php echo $yr;?></option>																			<?php										
				}
			} 			
?>		</select>
			&nbsp;&nbsp;
			
		<select id="jsMonth" name="jsMonth" class="textarea" onchange="return refreshMonth();">	
			<option value = "<?php echo $month;?>"><?php echo getMonth($month);?></option>																						<?php	
			if(!isset($monthList))
				$monthList = getMonths($year);	
			foreach($monthList as $mnth) 
			{	
				if($month != $mnth)
				{																																			?>			
					<option value="<?php echo $mnth;?>"><?php echo getMonth($mnth);?></option>																<?php						
				}
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsDateString" name="jsDateString" class="textarea" onchange="return refreshString();">
			<option value = "<?php echo $dateString;?>"><?php echo $dateString;?></option>																									<?php	
			if(!isset($stringList))
				$stringList = getStrings($year,$month);
			foreach($stringList as $string) 
			{
				if($dateString != $string)
				{																																															?>
					<option value="<?php echo $string;?>"><?php echo $string;?></option>																			<?php						
				}
			}																																					?>
																														
		</select>
		<br><br>
		
		&emsp;&emsp;&emsp;																																		<?php	
		if($today >= $fromDate && $today <= $toDate)
		{																																						?>
			<input type="checkbox" name="removeToday" id="removeToday" onchange="refresh();">Show yesterday's closing</input>									<?php	
		}																																						?>
		<br><br>
		<table class="responstable" style="width:65% !important;">
			<thead>
				<tr>
					<th style="text-align:left;width:24%;">AR</th>
					<th style="text-align:left;width:27%;">SHOP</th>
					<th style="width:14%;">MOBILE</th>
					<th style="width:8%;">Spcl Target</th>
					<th style="width:8%;">Actual Sale</th>
					<th style="width:8%;">Balance</th>
					<th style="width:3%;">Achieved%</th>
					<th style="width:8%;">Extra Bags</th>				
				</tr>																																
			</thead>																																							<?php
			$targetTotal = 0;
			$saleTotal = 0;
			$extraTotal = 0;
			$balanceTotal = 0;
			foreach($arNameMap as $arId =>$arName)
			{		
				if(isset($arTargetMap[$arId]))
					$spclTarget = $arTargetMap[$arId];
				else
					$spclTarget = 0;
				if(isset($arSaleMap[$arId]))
					$sale = $arSaleMap[$arId];
				else
					$sale = 0;
				if(isset($arExtraMap[$arId]))
					$extraBags = $arExtraMap[$arId];
				else
					$extraBags = 0;																													
				
				if($spclTarget != 0)
					$percentage = round(  ($sale + $extraBags) * 100 / $spclTarget,0);
				else
					$percentage = 0;
				$balance = $spclTarget-$sale-$extraBags;
				if($balance < 0)
					$balance = 0;																													?>
				
				<tr align="center">
					<td style="text-align:left;"><?php echo $arName;?></td>
					<td style="text-align:left;"><?php echo $arShopMap[$arId];?></td>
					<td><?php echo $arMobileMap[$arId];?></td>
					<td><?php echo $spclTarget;?></td>
					<td><?php echo $sale;?></td>
					<td><?php echo $balance ?></td>							
					<td><?php echo $percentage.'%';?></td>
					<td><?php echo $extraBags;?></td>
				</tr>																																<?php
				$targetTotal = $targetTotal + $spclTarget;
				$saleTotal = $saleTotal + $sale;
				$extraTotal = $extraTotal + $arExtraMap[$arId];
				$balanceTotal = $balanceTotal + $balance;																														
			}
			$percentageTotal = round(  ($saleTotal + $extraTotal) * 100 / $targetTotal,0);																?>
			<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
				<td colspan="3" style="text-align:right;font-size:20px;">Total</td>
				<td style="font-size:15px;"><?php echo $targetTotal;?></td>
				<td style="font-size:15px;"><?php echo $saleTotal;?></td>
				<td style="font-size:15px;"><?php echo $balanceTotal;?></td>
				<td style="font-size:15px;"><?php echo $percentageTotal.'%';?></td>
				<td style="font-size:15px;"><?php echo $extraTotal;?></td>				
			</tr>
		</table>
		<br><br><br><br>
		</div>
</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>
