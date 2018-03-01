<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	if(isset($_GET['fromDate']) && isset($_GET['toDate']))
	{
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];				
	}
	else
	{
		$sqlDates = mysqli_query($con, "SELECT from_date,to_date FROM special_target_date ORDER BY to_date DESC LIMIT 1") or die(mysqli_error($con));		 
		$dates = mysqli_fetch_array($sqlDates,MYSQLI_ASSOC);
		$fromDate = $dates['from_date'];
		$toDate = $dates['to_date'];		
	}	

	$sql = "SELECT ar_name, special_target FROM ar_calculation_special_target WHERE fromDate='$fromDate' AND toDate='$toDate' order by ar_name asc";
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
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<title>View Special Target</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
<h1>Special Target Details<h1>
<select onchange="javascript:location.href = this.value;">
    <?php
    $queryDates = "SELECT from_date,to_date FROM special_target_date ORDER BY to_date ASC";
    $db = mysqli_query($con,$queryDates);
    while ( $row=mysqli_fetch_assoc($db)) 
	{
		$value = date('d-M-Y',strtotime($row['from_date'])).'&nbsp&nbsp&nbsp&nbspTO&nbsp&nbsp&nbsp&nbsp'.date('d-M-Y',strtotime($row['to_date']));									
		$urlValue = "updatePage.php?fromDate=".$row['from_date']."&toDate=".$row['to_date']."";									?>
     <option <?php if($row['from_date'] == $fromDate) echo 'selected';?> value='<?php echo $urlValue;?>'><?php echo $value;?></option>   								<?php
	}
    ?>
</select>
</div>
<br><br>
<form method="post" action="updateServer.php">
<table align="center" class="responstable">
<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">SPECIAL TARGET</th></tr>					<?php
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
{
	$arname = $row['ar_name'];
	$special_target = $row['special_target'];
	?>				
	<tr>
	<td><label align="center"><?php echo $arname; ?></td>	
	<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-special_target';?>" value="<?php echo $special_target; ?>"></td>	
	</tr>																												<?php
}						
																								?>
<input type="hidden" name="fromDate" value="<?php echo $fromDate;?>">
<input type="hidden" name="toDate" value="<?php echo $toDate;?>">
</table>
<br><br>
<?php 
	$sql = "SELECT locked FROM lock_specialtarget WHERE from_date='$fromDate' AND to_date='$toDate' ";
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
	header("Location:loginPage.php");

?>