<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
  
	if(isset($_GET['month']))
		$month = (int)$_GET['month'];		
	else
		$month = (int)date("m") ;		

	if(isset($_GET['year']))		
		$year = (int)$_GET['year'];		
	else
		$year = (int)date("Y");		

	if($month == date("m") && $year == date("Y"))
		$day = (int)date("d");

	$arObjects = mysqli_query($con, "SELECT * FROM ar_details WHERE isActive = 1 ORDER BY ar_name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['ar_name'];
		$arCodeMap[$ar['id']] = $ar['sap_code'];
		$arShopMap[$ar['id']] = $ar['shop_name'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
		$targetMap[$ar['id']] = 0;
	}
	$array = implode("','",array_keys($arNameMap));		
	
	if($_POST)
	{
		header("Location:totalSalesAR.php?month=".$_POST['month']."&year=".$_POST['year']);	
	}	
	
	$targetObjects = mysqli_query($con, "SELECT * FROM target WHERE year=$year AND month = $month AND ar_id IN ('$array')" ) or die(mysqli_error($con));	
	foreach($targetObjects as $target)
	{
		$targetMap[$target['ar_id']] = $target['target'];
		$companyTargetMap[$target['ar_id']] = $target['company_target'];
	}	
	
	$salesList = mysqli_query($con, "SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE YEAR(entry_date) = $year AND MONTH(entry_date) = $month AND ar_id IN ('$array') GROUP BY ar_id" ) or die(mysqli_error($con));

	$mainarray = array();
	foreach($salesList as $arSale)
	{  
		$target = $targetMap[$arSale['ar_id']];
		$total = $arSale['SUM(srp)'] + $arSale['SUM(srh)'] + $arSale['SUM(f2r)'] - $arSale['SUM(return_bag)'];
		
		if(isset($day))
			$temp = $target*($day/25);
		else
			$temp = $target;
		
		if($temp != 0)
			$prorata = round($total/$temp*100);
		else
			$prorata = 0;
		
		if($target > 0)
			$percentage = round($total/$target*100);
		else
			$percentage = 0;
		
		$mainarray[$arSale['ar_id']] = array($target,$total,$prorata,$percentage);
	}
	
	foreach($arObjects as $ar)
	{
		if(!array_key_exists($ar['id'],$mainarray))
			$mainarray[$ar['id']] = array(0,0,0,0);
	}	
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<link rel="stylesheet" type="text/css" href="../css/glow_box.css">	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">			
	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript">
	function refresh()
	{
		var year = document.getElementById("year").options[document.getElementById("year").selectedIndex].value;

		var month=document.getElementById("month").value;

		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));

		window.location.href = hrf +"?year="+ year + "&month=" + month;
	}
	</script>	
</head>
<body>
<div align="center">
<br><br>
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<br><br><br><br>

	<select name="month" id="month" class="textarea" onChange="refresh();">
		<option value = "<?php echo $month;?>"><?php echo getMonth($month);?></option>			<?php	
		$monthList = mysqli_query($con, "SELECT DISTINCT month FROM target WHERE month <> $month ORDER BY month ASC" ) or die(mysqli_error($con));	
		foreach($monthList as $monthObj) 
		{
?>			<option value="<?php echo $monthObj['month'];?>"><?php echo getMonth($monthObj['month']);?></option>		<?php	
		}
?>	</select>
	
	&nbsp;&nbsp;
	
	<select name="year" id="year" required class="textarea" onChange="refresh();">
		<option value = "<?php echo $year;?>"><?php echo $year;?></option>			<?php	
		$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target  WHERE year <> $year ORDER BY year DESC") or die(mysqli_error($con));	
		foreach($yearList as $yearObj) 
		{
?>			<option value="<?php echo $yearObj['year'];?>"><?php echo $yearObj['year'];?></option>		<?php	
		}
?>	</select>

<br><br><br>

<table class="responstable" name="responstable" id="responstable" style="width:70% !important;">
<thead>
<tr>
	<th>AR</th>
	<th style="width:10%;">SAP</th>
	<th style="width:15%;">Phone</th>
	<th>Shop</th>
	<th style="width:5%;">Target</th>
	<th style="width:5%;">Total</th>
	<th style="width:5%;">Balance</th>
	<th style="width:7%;">ProRata%</th>
	<th style="width:7%;">Percentage</th>
</tr>
</thead>
<tbody>
<?php
	foreach($arObjects as $ar)
	{
?>		<tr>
			<td><?php echo $arNameMap[$ar['id']];?></td>
			<td><?php echo $arCodeMap[$ar['id']];?></td>			
			<td><?php echo $arPhoneMap[$ar['id']];?></td>
			<td><?php echo $arShopMap[$ar['id']];?></td>
			<td><?php echo $mainarray[$ar['id']][0];?></td>
			<td><?php echo $mainarray[$ar['id']][1];?></td>
			<td><?php echo $mainarray[$ar['id']][0] - $mainarray[$ar['id']][1];?></td>
			<td><?php echo $mainarray[$ar['id']][2];?></td>
			<td><?php echo $mainarray[$ar['id']][3];?></td>
		</tr>		<?php
	}				?>
</tbody>	
</table>																							
<br><br><br><br><br><br>
</div>
</body>			
<?php
}
else
	header("Location:../index.php");	
 