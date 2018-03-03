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
<style>
.responstable {
  width: 35%;
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
<link href="../bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"dd-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>
<title>VARIANCE REPORT</title>
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
	<table align="center" class="responstable">
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