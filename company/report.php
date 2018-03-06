<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$nasQtyMap = array();
	$companyQtyMap = array();
	
	if(isset($_POST['date']))
	{
		$date = $_POST['date'];
		$sqlDate = date("Y-m-d", strtotime($date));
	}	
	else
	{
		$date = date("d-m-Y");
		$sqlDate = date("Y-m-d", strtotime($date));
	}	
	$nasQuery = "SELECT ar, SUM(srp), SUM(srh), SUM(f2r) FROM nas_sale WHERE entry_date ='$sqlDate' GROUP BY ar";
	$nasResult = mysqli_query($con, $nasQuery) or die(mysqli_error($con));
	while($nas = mysqli_fetch_array($nasResult,MYSQLI_ASSOC))
	{
		$nasQtyMap[$nas['ar']] = $nas['SUM(srp)'] + $nas['SUM(srh)'] + $nas['SUM(f2r)'];
	}
	
	$companyQuery = "SELECT ar, SUM(srp), SUM(srh), SUM(f2r) FROM company_sale WHERE date ='$sqlDate' GROUP BY ar";
	$companyResult = mysqli_query($con, $companyQuery) or die(mysqli_error($con));
	while($company = mysqli_fetch_array($companyResult,MYSQLI_ASSOC))
	{
		$companyQtyMap[$company['ar']] = $company['SUM(srp)'] + $company['SUM(srh)'] + $company['SUM(f2r)'];
	}	
	
	// Populate both maps with zeros if no sale is present for one and sale is present for other
	foreach($nasQtyMap as $ar => $qty)
	{
		if(!isset($companyQtyMap[$ar]))
			$companyQtyMap[$ar] = 0;
	}
	foreach($companyQtyMap as $ar => $qty)
	{
		if(!isset($nasQtyMap[$ar]))
			$nasQtyMap[$ar] = 0;
	}	
?>
<html>
<head>
	<title>VARIANCE REPORT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<script>
	$(function() {

	var pickerOpts = { dateFormat:"dd-mm-yy"}; 
				
	$( "#datepicker" ).datepicker(pickerOpts);

	});
	</script>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<div style="width:100%;">
	<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
	<br><br>
	<br>
	<form method="post" action="" >
		<input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo $date ?>" />
		<input type="submit" name="submit" value="Submit">
	</form>
	</div>
	<br><br>
	<table align="center" class="responstable" style="width:30%">
		<tr>
			<th style="width:30%;text-align:center;">AR</th>
			<th style="width:20%;text-align:center;">NAS QTY</th>
			<th style="width:20%;text-align:center;">COMPANY QTY</th>
			<th style="width:20%;text-align:center;">VARIANCE</th> 
		</tr>					<?php
		foreach($nasQtyMap as $ar => $nasQty)
		{
?>				
		<tr>
			<td style="text-align:left;"><?php echo $ar; ?></td>	
			<td style="text-align:center;"><?php echo $nasQty; ?></td>	
			<td style="text-align:center;"><?php echo $companyQtyMap[$ar]; ?></td>	
			<td style="text-align:center;"><?php echo $nasQty - $companyQtyMap[$ar]; ?></td>	
		</tr>																												<?php
		}						
																									?>	
	</table>
	<br><br>
	</div> 
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");

?>