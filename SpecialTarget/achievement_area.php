<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$mainArray = array();

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

	if(isset($_GET['removeToday']))
	
	$todayCheck = false;
	$today = date("Y-m-d");
	if($today >= $fromDate && $today <= $toDate)
		$todayCheck = true;
	
	
	$arList = mysqli_query($con,"SELECT id, ar_name, mobile, shop_name FROM ar_details WHERE isActive = 1") or die(mysqli_error($con));		 
	foreach($arList as $arObject)
	{
		$arNameMap[$arObject['id']] = $arObject['ar_name'];
		$arMobileMap[$arObject['id']] = $arObject['mobile'];
		$arShopMap[$arObject['id']] = $arObject['shop_name'];
	}


	$array = implode("','",array_keys($arNameMap));	
	$ar_detail = mysqli_query($con,"SELECT ar_id, special_target FROM special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate' AND ar_id IN ('$array')") or die(mysqli_error($con));		 

	foreach($ar_detail as $ar)
	{	
		$arId = $ar['ar_id'];
		$mainArray[$arId] = array();
		$mainArray[$arId]['special_target'] = $ar['special_target'];
		$mainArray[$arId]['actual_sales'] = 0;
		$mainArray[$arId]['percentage'] = 0;
		
		if(isset($_GET['removeToday']) && $_GET['removeToday'] == 'true')
		{
			$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate'
												AND entry_date <= '$toDate' AND entry_date <> CURDATE() 
												AND ar_id = '$arId'
												AND bill_no not like '%can%' GROUP BY ar_id")
												or die(mysqli_error($con));												
		}
		else
			$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate'
												AND entry_date <= '$toDate'
												AND ar_id = '$arId'
												AND bill_no not like '%can%' GROUP BY ar_id")
												or die(mysqli_error($con));								

		foreach($sales as $sale)
		{
			$lpp = $sale['SUM(srp)'];
			$hdpe = $sale['SUM(srh)'];
			$cstl = $sale['SUM(f2r)'];
			$return_bag = $sale['SUM(return_bag)'];
			$total = $lpp + $hdpe + $cstl - $return_bag;
			
			$mainArray[$arId]['actual_sales'] = $mainArray[$arId]['actual_sales'] + $total;
			
			if($mainArray[$arId]['special_target'] != 0)
				$mainArray[$arId]['percentage'] = round($mainArray[$arId]['actual_sales'] * 100 / $mainArray[$arId]['special_target'],0);
			else
				$mainArray[$arId]['percentage'] = 0;
			
			
		}
	}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/loader.css">	
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">	
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">	

	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" >
	$(document).ready(function() {

		$("#loader").hide();	
		
		var checkbox = getUrlParameter('removeToday');
		if(checkbox =='true')
			$('#removeToday').prop('checked', true);
		else
			$('#removeToday').prop('checked', false);	
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
			 <option value="achievement.php?">No Grouping</option>   								
			 <option value="achievement_user.php?">User Wise</option>
			 <option value="#" selected>Area Wise</option>   								
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
<?php				if($todayCheck)
		{
?>						<input type="checkbox" name="removeToday" id="removeToday" onchange="refresh();">Remove today's sales</input>				
<?php				}
?>
		<br><br>
		<table class="responstable" width="60% !important;">
			<thead>
				<tr align="center">
				<th style="text-align:left;width:220px;">AR</th>
				<th style="width:220px;">SHOP</th>
				<th style="width:120px;">MOBILE</th>
				<th>Special Target</th>
				<th>Actual Sales</th>
				<th>Balance</th>
				<th>Achieved %</th>	
				</tr>
			</thead>

			<tbody>
			<?php
			foreach($mainArray as $arId =>$subarray)
			{
			?>
				<tr align="center">
					<td style="text-align:left;"><?php echo $arNameMap[$arId];?></td>
					<td><?php echo $arShopMap[$arId];?></td>
					<td><?php echo $arMobileMap[$arId];?></td>
					<td><?php echo $subarray['special_target']?></td>
					<td><?php echo $subarray['actual_sales']?></td>
					<td><?php echo $subarray['special_target']-$subarray['actual_sales']; ?></td>							
					<td><?php echo $subarray['percentage']?></td>
				</tr>	
			<?php
			}
			?>
			</tbody>
		</table>
		</div>
</body>
</html>
<?php
}

else
	header("Location:../index.php");

?>