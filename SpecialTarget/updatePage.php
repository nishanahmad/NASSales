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

	$arObjects = mysqli_query($con, "SELECT id,ar_name FROM ar_details WHERE isActive = 1 ORDER BY ar_name ASC") or die(mysqli_error($con));
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['ar_name'];
	}	
	
	$array = implode("','",array_keys($arMap));
	$sql = "SELECT ar_id, special_target FROM special_target WHERE fromDate='$fromDate' AND toDate='$toDate' AND ar_id IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));		 
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/responstable.css" rel="stylesheet">
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
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
	<table align="center" class="responstable" style="width:50px;">
	<tr><th style="width:25%">AR NAME</th><th style="width:25%;text-align:center;">SPECIAL TARGET</th></tr>					<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arId = $row['ar_id'];
		$special_target = $row['special_target'];
		?>				
		<tr>
		<td><label align="center"><?php echo $arMap[$arId]; ?></td>	
		<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-special_target';?>" value="<?php echo $special_target; ?>"></td>	
		</tr>																												<?php
	}						
																									?>
	<input type="hidden" name="fromDate" value="<?php echo $fromDate;?>">
	<input type="hidden" name="toDate" value="<?php echo $toDate;?>">
	</table>
	<br><br>
	<?php 
		$sql = "SELECT locked FROM special_target_locker WHERE from_date='$fromDate' AND to_date='$toDate' ";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		if($row['locked'] != true && count($row) > 0)
		{
	?>		<div align="center"><input type="submit" name="submit" value="Update"></div>		
	<?php	
		}		
	?>
	<br><br>  
</body>
</html>
<?php
}
else
	header("../Location:index.php");

?>