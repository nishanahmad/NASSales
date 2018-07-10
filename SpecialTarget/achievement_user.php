<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$today = date("Y-m-d");
	
	if(isset($_GET['fromDate']) && isset($_GET['toDate']))
	{
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];				
	}
	else
	{
		$sqlDates = mysqli_query($con, "SELECT from_date,to_date FROM special_target_date ORDER BY to_date DESC LIMIT 1") or die(mysqli_error($con));		 
		$dates = mysqli_fetch_array($sqlDates,MYSQLI_ASSOC);
		$fromDate = $dates['from_date'];
		$toDate = $dates['to_date'];		
	}	
	
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
		var range = document.getElementById("range").value;
		var removeToday = $('#removeToday').is(':checked');
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		
		$("#main").hide();
		$("#loader").show();
	
		window.location.href = hrf +"?"+ range + "&removeToday=" + removeToday;
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
		<select name="range" id="range" onchange="refresh();">
			<?php						
			$queryDates = "SELECT from_date,to_date FROM special_target_date ORDER BY to_date ASC";
			$dates = mysqli_query($con,$queryDates);
			while ( $row=mysqli_fetch_assoc($dates)) 
			{
				$value = date('d-M-Y',strtotime($row['from_date'])).'&emsp;TO&emsp;'.date('d-M-Y',strtotime($row['to_date']));									
				$urlValue = "fromDate=".$row['from_date']."&toDate=".$row['to_date']."";									?>
			 <option <?php if($row['from_date'] == $fromDate) echo 'selected';?> value='<?php echo $urlValue;?>'><?php echo $value;?></option>   								<?php
			}
			?>
		</select>
		&emsp;&emsp;&emsp;
<?php				if($today >= $fromDate && $today <= $toDate)
					{
?>						<input type="checkbox" name="removeToday" id="removeToday" onchange="refresh();">Show yesterday's closing</input>				
<?php				}
?>
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
