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
					$insertQuery = "INSERT INTO special_target (ar_id,fromDate,toDate,special_target) VALUES ('$arId','$fromDate','$toDate','$special_target')";
					$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));		 											
				}

			}
			$lock = mysqli_query($con, "INSERT INTO special_target_locker (from_date,to_date,locked) VALUES ('$fromDate','$toDate',1)") or die(mysqli_error($con));		 											
			header("Location:../index.php");
		}	
	}
?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/responstable.css" rel="stylesheet">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
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
	<table align="center" class="responstable" style="width:30%;">
		<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">SPECIAL TARGET</th></tr>					<?php
		foreach($arObjects as $ar) 
		{									?>				
			<tr>
				<td><label align="center"><?php echo $ar['ar_name']; ?></td>	
				<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $ar['id'];?>" value="0"></td>	
			</tr>																												<?php
		}																								?>
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