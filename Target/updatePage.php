<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	//require 'insertNewMonthPoints.php'; 
	
	$year = $_GET['year'];
	$month = $_GET['month'];

	$arObjects = mysqli_query($con, "SELECT id,ar_name FROM ar_details WHERE isActive = 1 ORDER BY ar_name ASC") or die(mysqli_error($con));
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['ar_name'];
	}	
	
	$array = implode("','",array_keys($arMap));	
	
	$sql = "SELECT ar_id, target, rate, payment_perc, company_target FROM target WHERE year='$year' AND Month='$month' AND ar_id IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));		 
?>

<html>
<script type="text/javascript">
function rerender()
{
var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;

var month=document.getElementById("jsMonth").value;

var hrf = window.location.href;
hrf = hrf.slice(0,hrf.indexOf("?"));

window.location.href = hrf +"?year="+ year + "&month=" + month;
}
</script>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/responstable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<title>AR List</title>
</head>
<body>
	<div style="width:100%;">
	<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
	<br><br>
	<font size="5px"><b><?php echo $year;?></b></font>
	<br>
	<select id="jsYear" name="jsYear" onchange="return rerender();">
		<option <?php if($year==2016) echo 'Selected';?> value="2016">2016</option>
		<option <?php if($year==2017) echo 'Selected';?> value="2017">2017</option>
		<option <?php if($year==2018) echo 'Selected';?> value="2018">2018</option>
		<option <?php if($year==2019) echo 'Selected';?> value="2019">2019</option>
		<option <?php if($year==2020) echo 'Selected';?> value="2020">2020</option>	
	</select>
	<select id="jsMonth" name="jsMonth" onchange="return rerender();">
		<option <?php if($month==1) echo 'Selected';?> value="1">January</option>
		<option <?php if($month==2) echo 'Selected';?> value="2">Februaury</option>
		<option <?php if($month==3) echo 'Selected';?> value="3">March</option>
		<option <?php if($month==4) echo 'Selected';?> value="4">April</option>
		<option <?php if($month==5) echo 'Selected';?> value="5">May</option>
		<option <?php if($month==6) echo 'Selected';?> value="6">June</option>
		<option <?php if($month==7) echo 'Selected';?> value="7">July</option>	
		<option <?php if($month==8) echo 'Selected';?> value="8">August</option>
		<option <?php if($month==9) echo 'Selected';?> value="9">September</option>
		<option <?php if($month==10) echo 'Selected';?> value="10">October</option>
		<option <?php if($month==11) echo 'Selected';?> value="11">November</option>
		<option <?php if($month==12) echo 'Selected';?> value="12">December</option>	
	</select>
	</div>
	<br><br>
	<form name="arBulkUpdate" method="post" action="updateServer.php">
	<table align="center" class="responstable">
		<tr>
			<th style="width:20%">AR NAME</th>
			<th style="width:20%;text-align:center;">TARGET</th>
			<th style="width:20%;text-align:center;">RATE</th>
			<th style="width:20%;text-align:center;">PAYMENT PERCENTAGE</th> 
			<th style="width:20%;text-align:center;">COMPANY TARGET</th> 
		</tr>					<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arId = $row['ar_id'];
		$target = $row['target'];
		$rate = $row['rate'];
		$pp = $row['payment_perc'];
		$company_target = $row['company_target'];

		?>				
		<tr>
			<td><label align="center"><?php echo $arMap[$arId]; ?></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-target';?>" value="<?php echo $target; ?>"></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-rate';?>" value="<?php echo $rate; ?>"></td>		
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-pp';?>" value="<?php echo $pp; ?>"></td>		
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-companyTarget';?>" value="<?php echo $company_target; ?>"></td>					
		</tr>																												<?php
	}						
																									?>
	<input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="month" value="<?php echo $month;?>">
	</table>
	<br><br>
	<?php 
		$sql = "SELECT locked FROM target_locker WHERE year='$year' AND Month='$month' ";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		if($row['locked'] != true && count($row) > 0)
		{
	?>		<div align="center"><input type="submit" name="submit" value="Submit"></div>		
	<?php	
		}		
	?>
	<br><br> 
	</div> 
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");

?>