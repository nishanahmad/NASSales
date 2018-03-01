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

	$sql = "SELECT ar_name, target, rate, payment_perc, company_target FROM ar_calculation WHERE year='$year' AND Month='$month' order by ar_name asc";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));		 
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
		$arname = $row['ar_name'];
		$target = $row['target'];
		$rate = $row['rate'];
		$pp = $row['payment_perc'];
		$company_target = $row['company_target'];

		?>				
		<tr>
			<td><label align="center"><?php echo $arname; ?></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-target';?>" value="<?php echo $target; ?>"></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-rate';?>" value="<?php echo $rate; ?>"></td>		
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-pp';?>" value="<?php echo $pp; ?>"></td>		
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-companyTarget';?>" value="<?php echo $company_target; ?>"></td>					
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
	</div> 
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");

?>