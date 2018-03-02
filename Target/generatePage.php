<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require 'insertNewMonthPoints.php'; 
	
	$year = $_GET['year'];
	$month = $_GET['month'];

	$arObjects = mysqli_query($con, "SELECT id,ar_name FROM ar_details WHERE isActive = 1 ORDER BY ar_name asc") or die(mysqli_error($con).' LINE 13');
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['ar_name'];
	}
	
	$array = implode("','",array_keys($arMap));	
	$sql = "SELECT ar_id, target, rate, payment_perc FROM ar_calculation WHERE year='$year' AND Month='$month' AND ar_id IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 21');		

	if(mysqli_num_rows($result) > 0)
	{
?>  	<html>
		<div align="center" style="font-size:40px"><br><br>Data already genrated for the month you selected. If you want to update contact admin to unlock the table
		<br><br>
		<button onclick="window.location.href='generateDateSelectPage.php'">Click here to go back</button>
		</div>
<?php
	exit;	
	}
	else
	{
		insertNewMonthPoints($month,$year);
		$unlock = mysqli_query($con, "INSERT INTO lock_target (month,year,locked) VALUES ('$month','$year','0') ") or die(mysqli_error($con).' LINE 36');				
		
		$sql = "SELECT ar_id, target, rate, payment_perc FROM ar_calculation WHERE year='$year' AND Month='$month'  AND ar_id IN ('$array')";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 39');				
	}	
?>

<html>
<style>
.responstable {
  width: 50%;
  overflow: hidden;
  background: #FFF;
  color: #024457;
  border-radius: 10px;
  border: 1px solid #167F92;
}
.responstable tr {
  border: 1px solid #D9E4E6;
}
.responstable tr:nth-child(odd) {
  background-color: #EAF3F3;
}
.responstable th {
  display: none;
  border: 1px solid #FFF;
  background-color: #167F92;
  color: #FFF;
  padding: 1em;
}
.responstable th:first-child {
  display: table-cell;
  text-align: left;
}
.responstable th:nth-child(2) {
  display: table-cell;
}
.responstable th:nth-child(2) span {
  display: none;
}
.responstable th:nth-child(2):after {
  content: attr(data-th);
}
@media (min-width: 480px) {
  .responstable th:nth-child(2) span {
    display: block;
  }
  .responstable th:nth-child(2):after {
    display: none;
  }
}
.responstable td { 
  display: block;
  word-wrap: break-word;
  max-width: 3em;
}
.responstable td:first-child {
  display: table-cell;
  text-align: left;
  border-right: 1px solid #D9E4E6;
}
@media (min-width: 480px) {
  .responstable td {
    border: 1px solid #D9E4E6;
  }
}
.responstable th, .responstable td {
  text-align: left;
  margin: .5em 1em;
}
@media (min-width: 480px) {
  .responstable th, .responstable td {
    display: table-cell;
    padding: .3em;
  }
}

body {
  font-family: Arial, sans-serif;
  color: #024457;
  background: #f2f2f2;
}

h1 {
  font-family: Verdana;
  font-weight: normal;
  color: #024457;
}
h1 span {
  color: #167F92;
}
</style>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<title>AR List</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
<font size="5px"><b><?php echo $year;?></b></font>
<br>
</div>
<br><br>
<form name="arBulkUpdate" method="post" action="updateServer.php">
<table align="center" class="responstable">
<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">TARGET</th>
	<th style="width:25%;text-align:center;">RATE</th><th style="width:25%;text-align:center;">PAYMENT PERCENTAGE</th> </tr>					<?php
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
{
	$arId = $row['ar_id'];
	$target = $row['target'];
	$rate = $row['rate'];
	$pp = $row['payment_perc'];
	?>				
	<tr>
	<td><label align="center"><?php echo $arMap[$arId]; ?></td>	
	<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-target';?>" value="<?php echo $target; ?>"></td>	
	<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-rate';?>" value="<?php echo $rate; ?>"></td>		
	<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-pp';?>" value="<?php echo $pp; ?>"></td>		
	</tr>																												<?php
}						
																								?>
<input type="hidden" name="year" value="<?php echo $year;?>">
<input type="hidden" name="month" value="<?php echo $month;?>">
</table>
<br><br>
<?php 
	$sql = "SELECT locked FROM lock_target WHERE year='$year' AND Month='$month' ";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($row['locked'] != true && count($row) > 0)
	{
?>		<div align="center"><input type="submit" name="submit" value="Submit"></div>		
<?php	
	}		
?>
<br><br>  
</body>
</html>
<?php
}
else
	header("../Location:loginPage.php");

?>