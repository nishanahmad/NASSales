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
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<link rel="stylesheet" type="text/css" href="../css/glow_box.css">	
	<link rel="stylesheet" href="../css/greenButton.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">			
	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<script>
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(pickerOpts);
		
		var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
		$( "#toDate" ).datepicker(pickerOpts2);		

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
<table class="responstable" name="responstable" id="responstable" style="width:65% !important;">
<thead>
<tr>
	<th>AR</th>
	<th style="width:15%;">Phone</th>
	<th>Shop</th>
	<th style="width:5%;">SRP</th>
	<th style="width:5%;">SRH</th>
	<th style="width:5%;">F2R</th>
	<th style="width:7%;">Total</th>
</tr>
</thead>
<tbody>
<?php
	$salesList = mysqli_query($con, "SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r) FROM sales_entry WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' GROUP BY ar_id" ) or die(mysqli_error($con));
	$srp = 0;
	$srh = 0;
	$f2r = 0;
	$total = 0;
	foreach($salesList as $arSale)
	{
?>		<tr>
			<td><?php echo $arNameMap[$arSale['ar_id']];?></td>
			<td><?php echo $arPhoneMap[$arSale['ar_id']];?></td>
			<td><?php echo $arShopMap[$arSale['ar_id']];?></td>
			<td><?php echo $arSale['SUM(srp)'];?></td>
			<td><?php echo $arSale['SUM(srh)'];?></td>
			<td><?php echo $arSale['SUM(f2r)'];?></td>			
			<td><b><?php echo $arSale['SUM(srp)'] + $arSale['SUM(srh)'] + $arSale['SUM(f2r)'];?></b></td>			
		</tr>
<?php	
		$srp = $srp + $arSale['SUM(srp)'];
		$srh = $srh + $arSale['SUM(srh)'];
		$f2r = $f2r + $arSale['SUM(f2r)'];
		$total = $total + $arSale['SUM(srp)'] + $arSale['SUM(srh)'] + $arSale['SUM(f2r)'];
	}
?>	
	<tr>
		<td colspan="7"></td>
	</tr>
	<tr style="font-weight:bold;">
		<td colspan="3" style="text-align:right">TOTAL</td>
		<td><?php echo $srp;?></td>
		<td><?php echo $srh;?></td>
		<td><?php echo $f2r;?></td>
		<td><?php echo $total;?></td>
	</tr>
</body>	
</table>
<br><br><br><br><br><br>
</div>
</body>			
<?php
}
else
	header("Location:../index.php");	
?>