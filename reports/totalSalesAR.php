<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
  
	if(isset($_GET['from']))
		$fromDate = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");		

	if(isset($_GET['to']))		
		$toDate = date("Y-m-d", strtotime($_GET['to']));		
	else
		$toDate = date("Y-m-d");		

	$arObjects = mysqli_query($con, "SELECT * FROM ar_details order by ar_name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['ar_name'];
		$arCodeMap[$ar['id']] = $ar['sap_code'];
		$arShopMap[$ar['id']] = $ar['shop_name'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
	}
	
	if($_POST)
	{
		header("Location:totalSalesAR.php?from=".$_POST['fromDate']."&to=".$_POST['toDate']);	
	}	
?>
<html>
<head>
	<title>AR Sale Date Wise</title>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<link rel="stylesheet" type="text/css" href="../css/glow_box.css">	
	<link rel="stylesheet" href="../css/greenButton.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">			
	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery.floatThead.min.js"></script>
	<script>
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(pickerOpts);
		
		var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
		$( "#toDate" ).datepicker(pickerOpts2);		

	});
	
	$(document).ready(function() {	
		var $table = $('.responstable');
		$table.floatThead();	
	});		

	</script>	
</head>
<body>
<div align="center">
<br><br>
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<br><br><br><br>
<form method="post" action="">
	<input type="text" id="fromDate" class="textarea" name="fromDate" required value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>" />
	&nbsp;&nbsp;to&nbsp;&nbsp;
	<input type="text" id="toDate" class="textarea" name="toDate" required value="<?php echo date('d-m-Y',strtotime($toDate)); ?>" />
	<br><br>
	<input type="submit" class="btn" name="submit" value="Update">	
</form>
<br>
<table class="responstable" name="responstable" id="responstable" style="width:70% !important;">
<thead>
	<tr>
		<th style="text-align:left;">AR</th>
		<th style="text-align:left;">Shop</th>	
		<th style="width:12%;">SAP</th>	
		<th style="width:15%;">Phone</th>
		<th style="width:7%;">SRP</th>
		<th style="width:7%;">SRH</th>
		<th style="width:7%;">F2R</th>
		<th style="width:10%;">Total</th>
	</tr>
</thead>
<?php
	$salesList = mysqli_query($con, "SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' GROUP BY ar_id" ) or die(mysqli_error($con));
	$srp = 0;
	$srh = 0;
	$f2r = 0;
	$total = 0;
	foreach($salesList as $arSale)
	{
?>		<tr>
			<td style="text-align:left;"><?php echo $arNameMap[$arSale['ar_id']];?></td>
			<td style="text-align:left;"><?php echo $arShopMap[$arSale['ar_id']];?></td>			
			<td><?php echo $arCodeMap[$arSale['ar_id']];?></td>			
			<td><?php echo $arPhoneMap[$arSale['ar_id']];?></td>
			<td><?php echo $arSale['SUM(srp)'];?></td>
			<td><?php echo $arSale['SUM(srh)'];?></td>
			<td><?php echo $arSale['SUM(f2r)'];?></td>			
			<td><b><?php echo $arSale['SUM(srp)'] + $arSale['SUM(srh)'] + $arSale['SUM(f2r)'] - $arSale['SUM(return_bag)'];?></b></td>			
		</tr>
<?php	
		$srp = $srp + $arSale['SUM(srp)'];
		$srh = $srh + $arSale['SUM(srh)'];
		$f2r = $f2r + $arSale['SUM(f2r)'];
		$total = $total + $arSale['SUM(srp)'] + $arSale['SUM(srh)'] + $arSale['SUM(f2r)'] + - $arSale['SUM(return_bag)'];
	}
?>	
	<tr>
		<td colspan="8"></td>
	</tr>
	<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
		<td colspan="4" style="text-align:right">TOTAL</td>
		<td><?php echo $srp;?></td>
		<td><?php echo $srh;?></td>
		<td><?php echo $f2r;?></td>
		<td><?php echo $total;?></td>
	</tr>
</table>
<br><br><br><br><br><br>
</div>
</body>			
<?php
}
else
	header("Location:../index.php");	
?>