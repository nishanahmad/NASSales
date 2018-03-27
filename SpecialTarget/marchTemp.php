<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$today = date("Y-m-d");
	
	$fromDate = date("Y-m-d",strtotime('25-03-2018'));
	$toDate = date("Y-m-d",strtotime("31-03-2018"));	
	
	$arList = mysqli_query($con,"SELECT id, ar_name, mobile, shop_name, user_id FROM ar_details WHERE isActive = 1") or die(mysqli_error($con));		 
	foreach($arList as $arObject)
	{
		$arNameMap[$arObject['id']] = $arObject['ar_name'];
		$arMobileMap[$arObject['id']] = $arObject['mobile'];
		$arShopMap[$arObject['id']] = $arObject['shop_name'];
		$arExtraMap[$arObject['id']] = 0;
		$userNameMap[$arObject['user_id']] = null;				
	}

	$userIds = implode("','",array_keys($userNameMap));	
	
	$userObjects = mysqli_query($con,"SELECT user_id, user_name FROM users WHERE user_id IN ('$userIds') ") or die(mysqli_error($con));		 
	foreach($userObjects as $user)
	{
		$userNameMap[$user['user_id']] = $user['user_name'];
	}		
	
	$extraBagsList = mysqli_query($con,"SELECT ar_id,SUM(qty) FROM extra_bags WHERE date >= '$fromDate' AND date <= '$toDate' GROUP BY ar_id") or die(mysqli_error($con));											
	foreach($extraBagsList as $extraBag)
	{
		$arExtraMap[$extraBag['ar_id']] = $extraBag['SUM(qty)'];
	}	
	
	$array = implode("','",array_keys($arNameMap));	
	
	$arTarget = mysqli_query($con,"SELECT ar_id, special_target FROM special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate' AND ar_id IN ('$array') AND special_target > 0 ") or die(mysqli_error($con));		 
	foreach($arTarget as $splTarget)
	{
		if(!isset($arTargetMap1[$splTarget['ar_id']]))
			$arTargetMap1[$splTarget['ar_id']] = $splTarget['special_target']; 
		else
			$arTargetMap2[$splTarget['ar_id']] = $splTarget['special_target']; 			
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
		var extra = $(this).find("td:eq(8)").text();   
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
		<h1>MARCH LAST SPECIAL TARGET</h1>
		<br><br>

<?php				if($today >= $fromDate && $today <= $toDate)
					{
?>						<input type="checkbox" name="removeToday" id="removeToday" onchange="refresh();">Show yesterday's closing</input>				
<?php				}
?>
		<br><br>																																				<?php
	foreach($userNameMap as $userId =>$userName)
	{
		if($userId == $_SESSION['user_id'] || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager')												
		{																																						?>
		<table class="responstable" style="width:65% !important;">
			<thead>
				<tr style="line-height: 30px;">
					<th colspan="9" style="text-align:center;font-size:20px;"><?php echo $userName; ?></th>
				</tr>									
				<tr>
					<th style="text-align:left;width:24%;">AR</th>
					<th style="text-align:left;width:27%;">SHOP</th>
					<th style="width:14%;">MOBILE</th>
					<th style="width:8%;">Actual Sale</th>					
					<th style="width:8%;">Spcl Target1</th>
					<th style="width:8%;">Balance1</th>
					<th style="width:8%;">Spcl Target2</th>					
					<th style="width:8%;">Balance2</th>					
					<!--th style="width:3%;">Achieved%</th-->
					<th style="width:5%;">Extra Bags</th>				
				</tr>																																
			</thead>																																							<?php
			$targetTotal = 0;
			$saleTotal = 0;
			$extraTotal = 0;
			$balanceTotal = 0;
			foreach($arNameMap as $arId =>$arName)
			{		
				if(isset($arTargetMap1[$arId]))
					$spclTarget1 = $arTargetMap1[$arId];
				else
					$spclTarget1 = 0;
				
				if(isset($arTargetMap2[$arId]))
					$spclTarget2 = $arTargetMap2[$arId];
				else
					$spclTarget2 = 0;
				
				if(isset($arSaleMap[$arId]))
					$sale = $arSaleMap[$arId];
				else
					$sale = 0;
				
				if(isset($arExtraMap[$arId]))
					$extraBags = $arExtraMap[$arId];
				else
					$extraBags = 0;																													
				
				/*if($spclTarget != 0)
					$percentage = round(  ($sale + $extraBags) * 100 / $spclTarget,0);
				else
					$percentage = 0;*/

				$balance1 = $spclTarget1-$sale-$extraBags;
				if($balance1 < 0)
					$balance1 = 0;
				
				$balance2 = $spclTarget2-$sale-$extraBags;
				if($balance2 < 0)
					$balance2 = 0;																													?>				
				
				<tr align="center">
					<td style="text-align:left;"><?php echo $arName;?></td>
					<td style="text-align:left;"><?php echo $arShopMap[$arId];?></td>
					<td><?php echo $arMobileMap[$arId];?></td>
					<td><?php echo $sale;?></td>					
					<td><?php echo $spclTarget1;?></td>
					<td><?php echo $balance1 ?></td>						
					<td><?php echo $spclTarget2;?></td>
					<td><?php echo $balance2 ?></td>												
					<td><?php echo $extraBags;?></td>
				</tr>																																<?php
				//$targetTotal = $targetTotal + $spclTarget;
				//$saleTotal = $saleTotal + $sale;
				//$extraTotal = $extraTotal + $arExtraMap[$arId];
				//$balanceTotal = $balanceTotal + $balance;																														
			}
			//$percentageTotal = round(  ($saleTotal + $extraTotal) * 100 / $targetTotal,0);																?>
		</table>
		<br><br><br><br>																																<?php
		}
	}																																					?>
		</div>
</body>
</html>
<?php
}

else
	header("Location:../index.php");

?>