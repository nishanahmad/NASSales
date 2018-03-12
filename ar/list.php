<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$queryAreas = "SELECT area, number FROM area order by number asc ";
	$areaObjects = mysqli_query($con, $queryAreas) or die(mysqli_error($con));
	foreach($areaObjects as $areaObj)
	{
		$areaMap[$areaObj['area']] = $areaObj['number'];
	}	
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/loader.css">
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<title>AR List</title>
</head>
<body>
<div id="main" class="main">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
</div>
<table align="center" class="responstable" style="width:90%;">
<?php
foreach($areaMap as $area => $number) 
{	
	$sql = "SELECT id,ar_name, mobile, shop_name, sap_code, area, isActive FROM ar_details WHERE area='".$area."' order by ar_name asc ";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));?>
	<tr><th colspan="6" style="text-align:center;"><?php echo $area;?></th></tr>
	<tr>
		<th style="width:20%">Name</th>
		<th style="width:20%">Shop</th>
		<th style="text-align:center;width:8%">SAP</th>
		<th>Mobile</th>
		<th>Area</th>
		<th>Status</th>
	</tr>
	<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arId = $row['id'];
		$arname = $row['ar_name'];
		$shopName = $row['shop_name'];
		$sapCode = $row['sap_code'];
		$area = $row['area'];
		$mobile = $row['mobile'];
		$status = $row['isActive'];
	?>	
	<tr>
	<td><?php echo $arname; ?></td>	
	<td><?php echo $shopName; ?></td>	
	<td style="text-align:center;width:8%"><label align="center"><?php echo $sapCode; ?></td>	
	<td style="text-align:center;width:10%"><?php echo $mobile;?></td>		
	<td style=""><?php echo $area;?></td>	
	<td style="text-align:center;width:8%"><?php if($status == 1 ) echo 'Active'; else echo 'InActive';?></td>
	</tr>																													<?php
	}																														
}																															?>
</table>
<br><br>
<div align="center"><input type="submit" name="submit" value="Submit" onclick=" return showLoader()"></div>		
</div>
</body>
</html>																														<?php

}
else
	header("Location:../index.php");
