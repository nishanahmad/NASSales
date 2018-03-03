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

		$arObjects = mysqli_query($con, "SELECT id,ar_name FROM ar_details WHERE isActive = 1 ORDER BY ar_name asc") or die(mysqli_error($con)) or die(mysqli_error($con));		 						
		
		if(count($_POST) > 0)
		{
			foreach($_POST as $arId => $special_target)
			{
				if(is_numeric($arId))
				{
					$insertQuery = "INSERT INTO target_special_target (ar_id,fromDate,toDate,special_target) VALUES ('$arId','$fromDate','$toDate','$special_target')";
					$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));		 											
				}

			}
			$lock = mysqli_query($con, "INSERT INTO lock_specialtarget (from_date,to_date,locked) VALUES ('$fromDate','$toDate',1)") or die(mysqli_error($con));		 											
			header("Location:../index.php");
		}	
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
<title>Insert Special Target</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<br><br>
<h1><?php echo date('d-M-Y',strtotime($fromDate)).'&nbsp&nbsp&nbsp&nbspTO&nbsp&nbsp&nbsp&nbsp'.date('d-M-Y',strtotime($toDate));?><h1>
</div>
<br><br>
<form method="post" action="">
<table align="center" class="responstable">
<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">SPECIAL TARGET</th></tr>					<?php
foreach($arObjects as $ar) 
{
	?>				
	<tr>
	<td><label align="center"><?php echo $ar['ar_name']; ?></td>	
	<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $ar['id'];?>" value="0"></td>	
	</tr>																												<?php
}						
																								?>
<input type="hidden" name="fromDate" value="<?php echo $fromDate;?>">
<input type="hidden" name="toDate" value="<?php echo $toDate;?>">
</table>
<br><br>
<div align="center"><input type="submit" name="submit" value="Submit"></div>		
<br><br>  
</body>
</html>
<?php
}
else
	header("../index.php");

?>