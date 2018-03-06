<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$arObjects = mysqli_query($con,"SELECT id,ar_name,shop_name FROM ar_details WHERE ar_name IS NOT NULL AND shop_name IS NOT NULL");
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['ar_name'];
		$shopNameMap[$ar['id']] = $ar['shop_name'];
	}	
	
	$nasQtyMap = array();
	$companyQtyMap = array();
	
	if(isset($_GET['fromDate']) && isset($_GET['toDate']))
	{
		$fromDate = $_GET['fromDate'];
		$sqlfromDate = date("Y-m-d", strtotime($fromDate));
		
		$toDate = $_GET['toDate'];
		$sqltoDate = date("Y-m-d", strtotime($toDate));		
	}	
	else
	{
		$fromDate = date("d-m-Y");
		$sqlfromDate = date("Y-m-d", strtotime($fromDate));
		
		$toDate = date("d-m-Y");
		$sqltoDate = date("Y-m-d", strtotime($toDate));				
	}	
	
	$array = implode("','",array_keys($arNameMap));			
	$nasQuery = "SELECT ar_id, SUM(srp), SUM(srh), SUM(f2r) FROM nas_sale WHERE entry_date >='$sqlfromDate' AND entry_date <= '$sqltoDate' AND ar_id IN('$array') GROUP BY ar_id";
	$nasResult = mysqli_query($con, $nasQuery) or die(mysqli_error($con));
	while($nas = mysqli_fetch_array($nasResult,MYSQLI_ASSOC))
	{
		$nasQtyMap[$nas['ar_id']] = $nas['SUM(srp)'] + $nas['SUM(srh)'] + $nas['SUM(f2r)'];
	}
	
	$companyQuery = "SELECT ar_id, SUM(srp), SUM(srh), SUM(f2r) FROM company_sale WHERE date >='$sqlfromDate' AND date <= '$sqltoDate' GROUP BY ar_id";
	$companyResult = mysqli_query($con, $companyQuery) or die(mysqli_error($con));
	while($company = mysqli_fetch_array($companyResult,MYSQLI_ASSOC))
	{
		$companyQtyMap[$company['ar_id']] = $company['SUM(srp)'] + $company['SUM(srh)'] + $company['SUM(f2r)'];
	}	
	
	// Populate both maps with zeros if no sale is present for one and sale is present for other
	foreach($nasQtyMap as $arId => $qty)
	{
		if(!isset($companyQtyMap[$arId]))
			$companyQtyMap[$arId] = 0;
	}
	foreach($companyQtyMap as $arId => $qty)
	{
		if(!isset($nasQtyMap[$arId]))
			$nasQtyMap[$arId] = 0;
	}	
?>
<html>
<head>
	<title>VARIANCE REPORT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<script>
	$(function() {
		var fromDate = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(fromDate);
		
		var toDate = { dateFormat:"dd-mm-yy"}; 
		$( "#toDate" ).datepicker(toDate);		

	});
	
	function refresh()
	{
		var fromDate = document.getElementById("fromDate").value;
		var toDate = document.getElementById("toDate").value;
		console.log(fromDate);
		
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		window.location.href = hrf +"?fromDate="+ fromDate + "&toDate=" + toDate;
	}
	
	</script>
</head>
<body>
	<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
	<br><br>
	<h2>NAS-COMPANY VARIANCE</h2>	
	<br>
		<input type="text" id="fromDate" name="fromDate" class="textarea" required value="<?php echo $fromDate ?>" onchange="refresh();"/>	&emsp;<b>to</b>&emsp;
		<input type="text" id="toDate" name="toDate" class="textarea" required value="<?php echo $toDate ?>" onchange="refresh();" />		
	</div>
	<br><br>
	<table align="center" class="responstable" style="width:50%">
		<tr>
			<th style="width:30%;text-align:center;">AR</th>
			<th style="width:30%;text-align:center;">SHOP</th>
			<th style="width:20%;text-align:center;">NAS QTY</th>
			<th style="width:20%;text-align:center;">COMPANY QTY</th>
			<th style="width:20%;text-align:center;">VARIANCE</th> 
		</tr>					<?php
		foreach($nasQtyMap as $ar => $nasQty)
		{
?>				
		<tr>
			<td style="text-align:left;"><?php echo $arNameMap[$ar]; ?></td>	
			<td style="text-align:left;"><?php echo $shopNameMap[$ar]; ?></td>	
			<td style="text-align:center;"><?php echo $nasQty; ?></td>	
			<td style="text-align:center;"><?php echo $companyQtyMap[$ar]; ?></td>	
			<td style="text-align:center;"><?php echo $nasQty - $companyQtyMap[$ar]; ?></td>	
		</tr>																												<?php
		}						
																									?>	
	</table>
	<br><br>
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");

?>